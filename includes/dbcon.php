<?php 
$dbhost = 'localhost';
$username = 'root';
$password = '';
$dbname = 'casino';
$con = mysqli_connect($dbhost, $username, $password, $dbname);

//check connection
if(mysqli_connect_errno()){
	die ('database connection failed'.mysqli_connect_error());
}/*else{
	echo 'connection successfull';
}*/

?>


