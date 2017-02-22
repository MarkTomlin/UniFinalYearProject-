
<?php
	session_start();
		
	$un = $_POST["username"];
	$pw = $_POST["password"];
	
	$conn = new PDO("mysql:host=edward2.solent.ac.uk;dbname=mtomlin;","mtomlin","iechohva");
	
	//Stops SQL injection 
	$statement = $conn->prepare("SELECT username,password FROM Users WHERE username=? AND password=?");
	$statement->bindParam (1, $un);
	$statement->bindParam (2, $pw);
	$statement->execute();
	
	//$results = $conn->query("SELECT username,password FROM ht_users WHERE username='$un' AND password='$pw'");
	
	If ($statement->fetch() == false) 
	{
		echo "<p>ERROR: Invalid Login!";
		echo " <a href='login.html'>Back</a></p>";
	}
	else 
	{
		//Sets up the authentication session variable and stores the username in it
		$_SESSION["gatekeeper"] = $un;
		//Redirects to index page
		header ("Location: myStats.php");
	}
?>
