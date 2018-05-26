<?php
header("Content-type: text/html; charset=utf-8");
if (isset($_POST['domian']) && isset($_POST['keyword']) && isset($_POST['keywords'])) {
	if (!is_dir('all/'.$_POST['domian'])) {
		echo '1';
		mkdir('all/'.$_POST['domian'].'/');
	}
	$fp1 = fopen('all/'.$_POST['domian'].'/ci','a');
	$fp2 = fopen('all/'.$_POST['domian'].'/liang','a');
	$fp3 = fopen('conf.ini','a');
	fwrite($fp1,$_POST['keywords']);
	for($i = 0; $i < 20 ;$i++){
		fwrite($fp2,generate_password(rand(5,8))."\r\n");
	}
	fwrite($fp3,$_POST['domian'].'----'.$_POST['keyword']."\r\n");


	fclose($fp1); 
	fclose($fp2); 
	fclose($fp3); 
}

function generate_password( $length = 8 ) { 
	$chars = 'abcdefghijklmnopqrstuvwxyz'; 
	$password = ''; 
	for ( $i = 0; $i < $length; $i++ ) { 
		$password .= $chars[ mt_rand(0, strlen($chars) - 1) ]; 
	} 
	return $password; 
} 

?>
<!DOCTYPE html>
<html>
<head>
	<title>上传</title>
</head>
<body>
<form action="" method="post">
	域名:<br />
	<input type="text" name="domian"><br />
	关键词<br />
	<input type="text" name="keyword"><br />
	关键词组<br />
	<textarea name="keywords" style="width:400px;height: 200px;"></textarea><br />
	<button type="submit">提交</button>
</form>
</body>
</html>