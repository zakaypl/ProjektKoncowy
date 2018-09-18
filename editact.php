<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

require_once 'config.php';

$dsn = "mysql:host=$host;port=$port;dbname=$database";
$conn = new \PDO($dsn, $username, $password, array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

$tab = $_POST['tab'];
$id = $_POST['id'];

foreach($_POST as $key => $value) {
	if (strstr($key,'tb_')) { 
        if($key!='tb_id') {
		    $set.="`".substr($key,3)."`='$value',";
        }
	}
}

$sql = "UPDATE `$tab` SET ".substr($set,0,-1)." WHERE `id`='$id';";
$q = $conn->prepare($sql);
$response = $q->execute();

header("location:widok.php?tab=".$tab);
?>