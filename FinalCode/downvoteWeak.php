<?php
	session_start();
	
	$id = $_GET["id"];
	
	$conn = new PDO("mysql:host=edward2.solent.ac.uk;dbname=mtomlin;","mtomlin","iechohva");
	
	//Stops SQL injection 
	$statement = $conn->prepare("SELECT * FROM Weak_vs WHERE ID=?");
	$statement->bindParam (1, $id);
	$statement->execute();
	
	//$results = $conn->query("SELECT username,password FROM ht_users WHERE username='$un' AND password='$pw'");
	while ($row = $statement->fetch())
		{
			$newrate = $row[rating] - 100;
			
			$statement2 = $conn->prepare("UPDATE Weak_vs SET rating=? WHERE ID=?");
			$statement2->bindParam (1, $newrate);
			$statement2->bindParam (2, $id);
			$statement2->execute();
		}  
?>
