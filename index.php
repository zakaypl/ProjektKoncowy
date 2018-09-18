<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

require_once 'config.php';

$dsn = "mysql:host=$host;port=$port;dbname=$database";
$conn = new \PDO($dsn, $username, $password, array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

$sql = "SHOW TABLES";
$tbList = $conn->query($sql)->fetchAll();        
$ret = '<select class="form-control" id="list">';
foreach($tbList as $t) {
    $ret.= '<option value="'.$t['Tables_in_ps2'].'">'.$t['Tables_in_ps2'].'</option>';
}
$ret.='</select><button class="btn btn-primary" id="rdr">Pokaż zawartość tabeli</button>';
?>
<!doctype html>
<html>
<head>
	<meta charset='UTF-8' />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Lista tabel w bazie danych:</h1>
                <div id="content"><?php echo $ret;?></div>
            </div>
        </div>
        <script>
		$(document).ready(function() {
            
        	$( "body" ).delegate("#rdr", "click", function() {
                var tb = $( "#list option:selected" ).text();
                window.location.href='widok.php?tab='+tb;
        		return false;	
        	});

		});
	</script>
</head>        
</body>
</html>