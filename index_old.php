<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html> 
<head> 
<title>画像アップローダーサンプル</title> 

<meta http-equiv="viewport" content="width=device-width, initial-scale=1" />
<meta http-equiv="apple-mobile-web-app-capable" content="yes" />
<script>
function delImeta( id ){
  if( confirm( "本当に削除しますか？" ) ){
    location.href = "./delete.php?id=" + id;
  }
}
</script>
</head> 
<body> 
  <h1>アップロード画像一覧</h1>
  <br/>
  <table border="1">
  <tr><th>#</th><th>ファイル名</th><th>登録日</th><th>削除</th></tr>

<?php
require( "./credentials.php" );

try{
  $dbh = new PDO( $dsn, $username, $password );
  if( $dbh != null ){
    $sql = "select id, img_id, filename, created from imetas order by created desc";
    $stmt = $dbh->query( $sql );
    while( $row = $stmt -> fetch( PDO::FETCH_ASSOC ) ){
      $id = $row['id'];
      $img_id = $row['img_id'];
      $filename = $row['filename'];
      $created = $row['created'];
        
      $tr = "<tr><td><a target='_blank' href='./image.php?id=" . $id . "'><img src='http://" . $_SERVER['SERVER_NAME'] . "/loadimg.php?img_id=" . $img_id . "' width='32' height='32'/></a></td>"
          . "<td>" . $filename . "</td>"
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
</body>
</html>
