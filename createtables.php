<?php
require( "./credentials.php" );

try{
  $dbh = new PDO( $dsn, $username, $password );
  $r = "";
  if( $dbh != null ){
    // imetas テーブル作成
    try{
      $sql = "create table if not exists imetas(id int auto_increment primary key,img_id int,filename varchar(255),created datetime);";
      $stmt = $dbh->prepare( $sql );
      $r = $stmt->execute();
    }catch( Exception $e ){
      $r = $e->getMessage();
    }
    echo( "create table imetas ->" . $r . "\n" );

    // imgs テーブル作成
    try{
      $sql = "create table if not exists imgs(id int auto_increment primary key,img mediumblob);";
      $stmt = $dbh->prepare( $sql );
      $r = $stmt->execute();
    }catch( Exception $e ){
      $r = $e->getMessage();
    }
    echo( "create table imgs ->" . $r . "\n" );

    // tags テーブル作成
    try{
      $sql = "create table if not exists tags(id int auto_increment primary key,imeta_id int,tag varchar(255),score float);";
      $stmt = $dbh->prepare( $sql );
      $r = $stmt->execute();
    }catch( Exception $e ){
      $r = $e->getMessage();
    }
    echo( "create table tags ->" . $r . "\n" );
  }
}catch( PDOException $e ){
  print( 'Error: ' . $e->getMessage() );
  die();
}
?>