<?php
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'shop_db';
$db_port =3307;


 $conn =mysqli_connect($db_host,$db_username,$db_password,$db_name,$db_port) or die('connection failed');
?>