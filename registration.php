<?php
//var_dump($_POST);

$name=$_POST["name"];
$pass=$_POST["pass"];
$hashed_password = password_hash($pass, PASSWORD_DEFAULT);

$servername="localhost";
$username="root";
$password="";

$conn = new mysqli($servername, $username, $password);

$issues = [];

if(!isNameCorrectFirstChar($name)){
	array_push($issues,"A név első karakterének betűnek kell lennie!");
}
if(!isNameCorrectLength($name)){
	array_push($issues,"A név hosszának minimum 8 karakternek kell lennie!");
}
if(!isPasswordCorrectLowerCase($pass)){
	array_push($issues,"A jelszónak tartalmaznia kell kis betűt!");
}
if(!isPasswordCorrectUpperCase($pass)){
	array_push($issues,"A jelszónak tartalmaznia kell nagy betűt!");
}
if(!isPasswordCorrectNumber($pass)){
	array_push($issues,"A jelszónak tartalmaznia kell számot!");
}
if(!isPasswordCorrectSpecialChar($pass)){
	array_push($issues,"A jelszónak tartalmaznia kell speciális karaktert!");
}
if(!isPasswordCorrectSpace($pass)){
	array_push($issues,"A jelszó nem tartalmazhat szóközt!");
}
if(!isPasswordCorrectLength($pass)){
	array_push($issues,"A jelszónak legalább 10 karakter hosszúnak kell lennie!");
}

if(count($issues) == 0){
	$q = "INSERT INTO db1.users (username, PASSWORD, lastlogin, reg_ts) VALUES ('$name', '$hashed_password', null, now());";
	$conn->query($q);
	$q = "SELECT id FROM db1.users WHERE username='$name' AND PASSWORD='$hashed_password';";
	$r = $conn->query($q);
	$x = $r->fetch_assoc();
	$id = $x["id"];
	$q = "INSERT INTO db1.logs (userid, ts, muvelet, error_message) VALUES ($id, now(), 1, null);";
	//echo $q;
	$conn->query($q);
	echo "<h1>Sikeres regisztráció!</h1><br>";
}
else{
	echo "<h1>A regisztráció sikertelen!</h1><br>";
	echo "<h3>A jelszó nem felel meg az alábbi követelményeknek:</h3><br>";
	echo "<ul>";
	foreach($issues as $issue){
		echo "<li>$issue</li><br>";
	}
	echo "</ul>";
}

$conn->close();

function isNameCorrectFirstChar($input){
	return ctype_alpha($input);
}
function isNameCorrectLength($input){
	return strlen($input) > 7;
}
function isPasswordCorrectLowerCase($input){
	return preg_match("/[a-z]/i", $input);
}
function isPasswordCorrectUpperCase($input){
	return preg_match("/[A-Z]/i", $input);
}
function isPasswordCorrectNumber($input){
	return preg_match("/[0-9]/i", $input);
}
function isPasswordCorrectSpecialChar($input){
	return preg_match('/[\'!^£$%&*()}{@#~?><>,|=_+¬-]/', $input);
}
function isPasswordCorrectSpace($input){
	return !str_contains($input, ' ');
}
function isPasswordCorrectLength($input){
	return strlen($input) > 9;
}

?>