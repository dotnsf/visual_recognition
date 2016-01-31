<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html> 
<head> 
<title>画像アップローダーサンプル</title> 

<meta http-equiv="viewport" content="width=device-width, initial-scale=1" />
<meta http-equiv="apple-mobile-web-app-capable" content="yes" />
</head> 
<body> 
<?php
require( "./credentials.php" );

try{
  if( isset( $_GET['id'] ) && $_GET['id'] ){
    $id = $_GET['id'];
    $dbh = new PDO( $dsn, $username, $password );
    if( $dbh != null ){
      // 画像を取り出す
      $sql = "select img_id from imetas where id = " . $id;
      $stmt = $dbh->query( $sql );
      $stmt->execute();
      if( $row = $stmt -> fetch( PDO::FETCH_ASSOC ) ){
        $img_id = $row['img_id'];
        
        // <img>タグにして出力
        echo "<img src='./loadimg.php?img_id=" . $img_id . "'/><br/>";
      }
      
      // タグ情報を取り出す
      echo "<ul>";
      $sql = "select tag,score from tags where imeta_id = " . $id;
      $stmt = $dbh->query( $sql );
      $stmt->execute();
      while( $row = $stmt -> fetch( PDO::FETCH_ASSOC ) ){
        $tag = $row['tag'];
        $score = $row['score'];
        echo "<li>" . $tag . "(" . $score . ")</li>";
      }
      echo "</ul>";
    }
    
    $dbh = null;
  }
}catch( PDOException $e ){
  print( 'Error: ' . $e->getMessage() );
  die();
}
?>
</body>
</html>
