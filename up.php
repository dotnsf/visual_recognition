<?php
require( "./credentials.php" );

// アップロードファイルを取得
$name = $_FILES["file"]["name"]; // ファイル名
$mimetype = $_FILES["file"]["type"]; // Content-Type
$filesize = $_FILES["file"]["size"]; // ファイルサイズ
$tmpname = $_FILES["file"]["tmp_name"]; // 一時ファイル名（ここに実体がある）

if( $tmpname ){
  try{
    // アップロードファイル（画像）のデータを取得
    $fp = fopen( $tmpname, "rb" );
    $imgdata = fread( $fp, $filesize );
    fclose( $fp );

    $dbh = new PDO( $dsn, $username, $password );
    if( $dbh != null ){
      // imgs テーブルに画像を格納
      $sql = "insert into imgs(img) values(:img)";
      $stmt = $dbh->prepare( $sql );
      $stmt->bindParam( ':img', $imgdata, PDO::PARAM_STR );

      $r = $stmt->execute(); //. 成功すると1
      if( $r == 1 ){
        // 格納した画像の ID を取得する
        $sql = "select last_insert_id() as img_id from imgs";
        $stmt = $dbh->prepare( $sql );
        $stmt->execute();
        if( $result = $stmt->fetch( PDO::FETCH_ASSOC ) ){
          $img_id = $result['img_id'];
          
          // imetas テーブルに情報を格納
          $created = date( "Y/m/d H:i:s" );
          $sql = "insert into imetas(img_id,filename,created) values(:img_id,:filename,:created)";
          $stmt = $dbh->prepare( $sql );
          $stmt->bindParam( ':img_id', $img_id, PDO::PARAM_INT );
          $stmt->bindParam( ':filename', $name, PDO::PARAM_STR );
          $stmt->bindParam( ':created', $created, PDO::PARAM_STR );
          $r = $stmt->execute(); //. 成功すると1
          if( $r == 1 ){
            // 格納した画像の ID を取得する
            $sql = "select last_insert_id() as imeta_id from imetas";
            $stmt = $dbh->prepare( $sql );
            $stmt->execute();
            if( $result = $stmt->fetch( PDO::FETCH_ASSOC ) ){
              $imeta_id = $result['imeta_id'];
          
              // Watson Visual Recognition API
              $apiurl = 'https://gateway.watsonplatform.net/visual-recognition-beta/api/v2/classify?version=2015-12-02';
              $text = httpFilePost( $apiurl, $watson_username, $watson_password, null, $imgdata );
              $json = json_decode( $text );
              $images = $json->images;
              if( count( $images ) ){
                for( $i = 0; $i < count( $images ); $i ++ ){
                  $image = $images[$i];
                  try{
                    if( $image->scores ){
                      $scores = $image->scores;
                      for( $j = 0; $j < count( $scores ); $j ++ ){
                        $score = $scores[$j];
                        $score_name = $score->name;
                        $score_score = $score->score;
                        
                        //. tags テーブルに情報を格納
                        $sql = "insert into tags(imeta_id,tag,score) values(:imeta_id,:tag,:score)";
                        $stmt = $dbh->prepare( $sql );
                        $stmt->bindParam( ':imeta_id', $imeta_id, PDO::PARAM_INT );
                        $stmt->bindParam( ':tag', $score_name, PDO::PARAM_STR );
                        $stmt->bindParam( ':score', $score_score, PDO::PARAM_STR );
                        $stmt->execute();
              
                        if( $score_name == "Manhole" ){
//                          print_r( "Manhole!!!" );
                        }
                      }
                    }
                  }catch( Exception $e ){
                  }
                }
              }
            }
          }
        }
      }
      
      $dbh = null;
      //print( 'r = ' . $r );
      header( 'location: /' );
    }
  }catch( PDOException $e ){
    print( 'Error: ' . $e->getMessage() );
    die();
  }
}else{
  print( 'No tmpname' );
}

function httpFilePost( $url, $username, $password, $params, $file_img ){
  $isMultipart = true;
  if( $isMultipart ){
    $boundary = '-------------------'.time();
    $contentType = 'Content-Type: multipart/form-data; boundary=' . $boundary;
    $data = '';
    if( $params ){
      foreach( $params as $key => $value ){
        $data .= "--$boundary" . "\r\n";
        $data .= 'Content-Disposition: form-data; name=' . $key . "\r\n\r\n";
        $data .= $value . "\r\n";
      }
    }

    $data .= "--$boundary" . "\r\n";
    $data .= sprintf( 'Content-Disposition: form-data; name="%s"; filename="%s"%s', 'UploadFile', "image.png", "\r\n" );
//    $data .= 'Content-Type: application/octed-stream' . "\r\n\r\n";
//    $data .= base64_encode($file_img) . "\r\n";
    $data .= 'Content-Type: image/png' . "\r\n\r\n";
    $data .= $file_img . "\r\n";

    $data .= "--$boundary--" . "\r\n";
  }else{
    $contentType = 'Content-Type: application/x-www-form-urlencoded';
    $data = http_build_query( $params );
  }
  
  //var_dump($data);
  
  $headers = array( $contentType, 'Content-Length: '.strlen( $data ), 'Authorization: Basic '.base64_encode($username.":".$password) );
  $options = array( 'http' => array( 'method'=>'POST', 'content'=>$data, 'header'=>implode( "\r\n", $headers ) ) );
  $contents = file_get_contents( $url, false, stream_context_create( $options ) );
  
  return $contents;
}
?>