<?php
$tab = $_GET['tab'];
$id = $_GET['id'];

require_once 'config.php';

$dsn = "mysql:host=$host;port=$port;dbname=$database";
$conn = new \PDO($dsn, $username, $password, array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    
$sql = "DELETE FROM ".$tab." WHERE id = ?";        
$q = $conn->prepare($sql);
$response = $q->execute(array($id));

header("location:widok.php?tab=".$tab);
?>