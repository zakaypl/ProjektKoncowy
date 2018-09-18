<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

require_once 'config.php';

$dsn = "mysql:host=$host;port=$port;dbname=$database";
$conn = new \PDO($dsn, $username, $password, array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

$tab = $_POST['tab'];

$k = '`id`,';
$v = 'NULL,';

foreach($_POST as $key => $value) {
	if (strstr($key,'tb_')) { 
	    $k.= "`".substr($key,3).'`,';   
        $v.="'$value'".',';
	}
}
$k = substr($k,0,-1);
$v = substr($v,0,-1);


$sql = "INSERT INTO `$tab` ($k) VALUES ($v);";

$q = $conn->prepare($sql);
$response = $q->execute();

header("location:widok.php?tab=".$tab);
?>