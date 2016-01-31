<?php
require( "./credentials.php" );

// 見つからなかった時用の出力内容
$contenttype = 'text/plain';
$r = $_SERVER['SERVER_NAME']; //'No img.';

$img_id = $_GET['img_id'];
if( $img_id ){
  try{
    // img_id が指定された画像を取り出す
    $dbh = new PDO( $dsn, $username, $password );
    if( $dbh != null ){
      $sql = 'select img from imgs where id = ' . $img_id;
      $stmt = $dbh->prepare($sql);
      $stmt->execute();

      if( $result = $stmt->fetch( PDO::FETCH_ASSOC ) ){
        // 見つかったらそのバイナリを取り出し、出力用の Content-Type を変更
        $contenttype = 'image/png';
        $r = $result['img'];
      }
    }
  }catch( PDOException $e ){
    print( 'Error: ' . $e->getMessage() );
    die();
  }
}

header( 'Content-Type: ' . $contenttype );
echo( $r );

@ob_flush();
@flush();

exit();
?>