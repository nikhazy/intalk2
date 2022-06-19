<?php
//NikhazyMarci
//Larixkft25!
$name=$_POST["name"];
$pass=$_POST["pass"];

$servername="localhost";
$username="root";
$password="";

$conn = new mysqli($servername, $username, $password);


$userData = GetUserData($conn, $name);
if(is_array($userData)){
	$id = $userData["id"];
	if(password_verify($pass, $userData["password"])){
		$q = "INSERT INTO db1.logs (userid, ts, muvelet, error_message) VALUES ($id, now(), 2, null);";
		$conn->query($q);
		$q = "UPDATE db1.users set lastlogin=now() WHERE username='$name';";
		$conn->query($q);
		echo "<h1>Bejelentkezés sikeres</h1>";
	}
	else{
		$q = "INSERT INTO db1.logs (userid, ts, muvelet, error_message) VALUES ($id, now(), 3, 'A megadott jelszó nem megfelelő!');";
		$conn->query($q);
		echo "<h1>Bejelentkezés sikertelen!</h1>";
	}
}
else{
	$q = "INSERT INTO db1.logs (userid, ts, muvelet, error_message) VALUES (null, now(), 3, 'A megadott felhasználónév nem létezik: $name');";
	$conn->query($q);
	echo "<h1>Bejelentkezés sikertelen!</h1>";
}



function GetUserData($input_conn, $input_name){
	$q = "SELECT * FROM db1.users WHERE username='$input_name';";
	$r = $input_conn->query($q);
	$x = $r->fetch_assoc();
	return $x;
}
$conn->close();

?>