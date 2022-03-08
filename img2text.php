<?php
$target_dir = "uploads/";
$raw_data = file_get_contents("php://input");
$raw_image = substr($raw_data,22);
$filename = rand(10000000000,99999999999) . ".jpg";
$target_file = $target_dir . $filename;

file_put_contents("$target_file",base64_decode($raw_image));

exec("tesseract $target_file result",$result,$error);
if($error){
	$data = [
		'message' => "Cannot Convert Image"
	];
}else{
	$result = file_get_contents('result.txt');
	$data = [
		'message' => $result
	];
	
}

echo json_encode($data);
?>