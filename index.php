<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html> 
<head> 
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="viewport" content="initial-scale=1.0, user-scalable=no"/>
<meta charset="utf-8"/>
<title>画像アップローダーサンプル</title> 

<!--
<meta property="og:title" content="FlashAir x Bluemix 連携サンプル"/>
<meta property="og:type" content="website"/>
<meta property="og:url" content="http://dotnsf-flashair.mybluemix.net/"/>
<meta property="og:image" content="./logo.png"/>
<meta property="og:site_name" content="FlashAir x Bluemix 連携サンプル"/>
<meta property="og:description" content="FlashAir の画像を自動アップロードして、自動タグ付けして、一覧表示するサンプル"/>
-->

<link rel="shortcut icon" href="./favicon.png" type="image/png" />
<link rel="icon" href="./favicon.png" type="image/png" />

<script type="text/javascript" src="//code.jquery.com/jquery-2.0.3.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css"/>
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css"/> -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.5/cerulean/bootstrap.min.css"/>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

<link href="./colorbox.css" rel="stylesheet"/> 
<script type="text/javascript" src="./jquery.colorbox-min.js"></script>

<script type="text/javascript">
function delImeta( id ){
  if( confirm( "本当に削除しますか？" ) ){
    location.href = "./delete.php?id=" + id;
  }
}

$(function(){
  $('.iframe').colorbox({
    iframe: true,
    width: "90%",
    height: "90%"
  });
});
</script>
<style>
html, body, {
  background-color: #ddddff;
  height: 100%;
  margin: 0px;
  padding: 0px
}
</style>
</head> 
<body> 
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="./">FlashAir Uploader</a>
      </div>
      <div>
      </div>
    </div>
  </nav>

<?php
require( "./credentials.php" );
?>

  <div class="container" style="padding:60px 0">
    <table class='table table-bordered table-hover table-condensed'>
      <tr><th>#</th><th>登録日</th><th>削除</th></tr>

<?php
try{
  $dbh = new PDO( $dsn, $username, $password );
  if( $dbh != null ){
    $sql = "select id, img_id, created from imetas order by created desc";
    $stmt = $dbh->query( $sql );
    while( $row = $stmt -> fetch( PDO::FETCH_ASSOC ) ){
      $id = $row['id'];
      $img_id = $row['img_id'];
      $created = $row['created'];
        
      $tr = "<tr><td><a class='iframe cboxElement' rel='external' href='./image.php?id=" . $id . "'><img src='http://" . $_SERVER['SERVER_NAME'] . "/loadimg.php?img_id=" . $img_id . "' width='32' height='32'/></a></td>"
          . "<td>" . $created . "</td>"
          . "<td><input type='button' value='削除' onClick='delImeta(" . $id . ")'/></td></tr>\n";
      echo $tr;
    }
    
    $dbh = null;
  }
}catch( PDOException $e ){
  print( 'Error: ' . $e->getMessage() );
  die();
}
?>
    </table>
  </div>
</body>
</html>
