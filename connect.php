<?php
//connect.php - server SISE\MySQL56\
$server = 'localhost';
$username   = 'root';
$password   = '';
//$password   = '';
$database   = 'mywebsitereg';

$_SESSION['Cn'] = mysqli_connect($server, $username,  $password, $database);

if(mysqli_connect_errno()!=0)
{
    exit ('Failed to connect to MySQL: ' . mysqli_connect_error());
}
//mysqli_select_db($con,$database);	//change the database to ...

//mysqli_close($con);

?>
