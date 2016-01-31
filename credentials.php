<?php
// 以下の Watson API と MySQL の接続情報を自身の環境に併せて編集する

// Watson Visual Recognition API
$watson_username = '(WATSON USERNAME)';
$watson_password = '(WATSON PASSWORD)';

// MySQL
$hostname = 'hostname';
$port = 3306;
$dbname = 'dbname';
$username = 'username';
$password = 'password';

// Bluemix 環境であれば上記の設定は不要
if( getenv( 'VCAP_SERVICES' ) ){
  $vcap = json_decode( getenv( 'VCAP_SERVICES' ), true );
  
  try{
    $credentials1 = $vcap['visual_recognition'][0]['credentials'];
    if( $credentials1 != NULL ){
      $watson_username = $credentials1['username'];
      $watson_password = $credentials1['password'];
    }
  }catch(Exception $e){
  }
  
  try{
    $credentials2 = $vcap['cleardb'][0]['credentials'];
    if( $credentials2 != NULL ){
      $hostname = $credentials2['hostname'];
      $dbname = $credentials2['name'];
      $port = $credentials2['port'];
      $username = $credentials2['username'];
      $password = $credentials2['password'];
    }
  }catch(Exception $e){
  }
}


// ここは編集不要
$dsn = 'mysql:dbname='.$dbname.';host='.$hostname.';port='.$port;
?>
