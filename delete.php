<?php
require( "./credentials.php" );

if( isset( $_GET['id'] ) && $_GET['id'] ){
  try{
    $id = $_GET['id'];

    $dbh = new PDO( $dsn, $username, $password );
    if( $dbh != null ){
      // tags テーブルから削除
      $sql = "delete from tags where imeta_id = :imeta_id";
      $stmt = $dbh->prepare( $sql );
      $stmt->bindParam( ':imeta_id', $id, PDO::PARAM_INT );
      $r = $stmt->execute(); //. 成功すると1

      // imgs テーブルから削除
      $sql = "delete from imgs where imgs.id = ( select img_id from imetas where imetas.id = :imeta_id )";
      $stmt = $dbh->prepare( $sql );
      $stmt->bindParam( ':imeta_id', $id, PDO::PARAM_INT );
      $r = $stmt->execute(); //. 成功すると1

      // imetas テーブルから削除
      $sql = "delete from imetas where id = :id";
      $stmt = $dbh->prepare( $sql );
      $stmt->bindParam( ':id', $id, PDO::PARAM_INT );
      $r = $stmt->execute(); //. 成功すると1
      
      $dbh = null;
      
      header( 'Location: http://' . $_SERVER['SERVER_NAME'] . '/index.php' );
      exit();
    }
  }catch( PDOException $e ){
    print( 'Error: ' . $e->getMessage() );
    die();
  }
}else{
  print( 'No tmpname' );
}
?>