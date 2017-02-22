<?php
	session_start();
		
	$un = $_POST["username"];
	$pw = $_POST["password"];
	$sn = $_POST["sname"];
	$sv = $_POST["server"];
	$em = $_POST["email"];
	$id = "2";
	
	$conn = new PDO("mysql:host=edward2.solent.ac.uk;dbname=mtomlin;","mtomlin","iechohva");
	
	$results=$conn->prepare("INSERT INTO Users (`ID`, `s_name`, `email`, `username`, `password`, `server`) VALUES (?,?,?,?,?,?);");
	$results->bindParam (1, $id);
	$results->bindParam (2, $sn);
	$results->bindParam (3, $em);
	$results->bindParam (4, $un);
	$results->bindParam (5, $pw);
	$results->bindParam (6, $sv);
	$results->execute();
	
	echo "Account: ". $un ." has been added. <a href='index.php'>Return to Homepage</a>"; 
?>
