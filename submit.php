<!DOCTYPE html>
<html>
<head>
	<title>url shortener</title>
	<meta charset="utf-8">
</head>
<body>
<?php 
	/* Config */
	$siteURL = 'http://localhost/url/';
	$DBHost = 'localhost';
	$DBUser = 'root';
	$DBPass = '15253555';
	$DBName = 'url';
	$con = mysql_connect($DBHost, $DBUser, $DBPass) or die('Cannot connect to database');
	$db = mysql_select_db($DBName, $con) or die('5'.mysql_error());
	/* user-i tvac url-@ */
	$full = $_POST['fullurl'];
	/* Random string-i stanalu function */
	function getRandomString($length = 6) {
		$validCharacters = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$validCharNumber = strlen($validCharacters);
		$rand = ""; 

		for ($i = 0; $i < $length; $i++) {
			$index = mt_rand(0, $validCharNumber - 1);
			$rand.= $validCharacters[$index];
		}
		return $rand;
	}

	/* Random string-@ DB-um stugelu function */
	function check2(){
		$val = getRandomString();
		global $full, $siteURL;
		$sql = mysql_query("SELECT * FROM used WHERE short = '$val'") or die('1'.mysql_error());
		$count=mysql_num_rows( $sql);
		if($count==0) {
			mysql_query("INSERT INTO used (full, short) VALUES ('$full', '$val')") or die('3'.mysql_error());
			$fp = fopen('.htaccess', 'a');
			fwrite($fp, "redirect 301 /$val $full\n");
			fclose($fp);
			echo 'Your short url is: <input type="text" readonly value="'.$siteURL.$val.'">';
		} else {
			check2();
		}
	}
	/* user-i tvac url-@ DB-um stugelu function */
	function check(){
		global $full, $siteURL;
		$sql2 = mysql_query("SELECT * FROM used WHERE full = '$full'") or die('2'.mysql_error());
		$count2 = mysql_num_rows( $sql2);
		if($count2==1){
			$row = mysql_fetch_assoc($sql2);
			$aval = $row['short'];
			echo 'Your short url is: <input type="text" readonly value="'.$siteURL.$aval.'">';
		} else {
			check2();
		}
	}
	check();
 ?>
</body>
</html>