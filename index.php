<?php session_start(); ?>
<?php
$_SESSION['timeLast']=date("H:i:s");
		$difftime=(strtotime($_SESSION['timeLast'])-strtotime($_SESSION['timeBegin']));
		if( $difftime >= 7200  ){
			$url = 'https://ahu-be.azurewebsites.net/api/Auth/refreshtoken';

$res = array(
	'accessToken' => $_SESSION['logged_user']['userToken']['accessToken']['token'],
	'refreshToken' => $_SESSION['logged_user']['userToken']['refreshToken']
);
$payload = json_encode($res);
// Construct cURL resource
$data = curl_init($url);
curl_setopt($data, CURLOPT_RETURNTRANSFER, true);
curl_setopt($data, CURLINFO_HEADER_OUT, true);
curl_setopt($data, CURLOPT_POST, true);
curl_setopt($data, CURLOPT_POSTFIELDS, $payload);
// HTTP Headers
curl_setopt(
	$data,
	CURLOPT_HTTPHEADER,
	array(
		'Content-Type: application/json',
		'Content-Length: ' . strlen($payload)
	)
);

// Submit the POST request
$result = curl_exec($data);
$result = json_decode($result,true);

// echo "<pre>";
// print_r($result->accessToken->token);
// echo "</pre>";
// Close cURL session
curl_close($data);
$_SESSION['logged_user']['userToken']['accessToken']['token'] = $result['accessToken']['token'];
$_SESSION['logged_user']['userToken']['refreshToken']=$result['refreshToken'];
$_SESSION['timeBegin']=date("H:i:s");
    }
?>

<?php
$data=$_POST;
    $url='https://ahu-be.azurewebsites.net/api/Profile';
    $res = $data;
   

  $payload = json_encode($res);
  // Construct cURL resource
  $data = curl_init($url);

curl_setopt ($data, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($data, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer '. $_SESSION['logged_user']['userToken']['accessToken']['token']
            ));
            curl_setopt($data, CURLOPT_HTTPGET, 1);
            // json_encode
            
            $result = curl_exec($data);
            $result = json_decode($result, true);
            $response_code = curl_getinfo($data, CURLINFO_HTTP_CODE);
            $result = $result + array('statusCode'=>$response_code);
            
            // $response_code =json_decode($response_code, true);
            curl_close($data);


echo json_encode($result);
// echo json_encode($response_code);

?>
