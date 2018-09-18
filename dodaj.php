<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

require_once 'config.php';

$dsn = "mysql:host=$host;port=$port;dbname=$database";
$conn = new \PDO($dsn, $username, $password, array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

$tab = $_REQUEST['tab'];
?><!doctype html>
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
            <div class="col-md-12"><a href="/socket" class="btn btn-success" style="margin-top:20px">&larr; Powrót do strony głównej</a><br/>
                <a href="/socket/widok.php?tab=<?php echo $tab;?>" class="btn btn-primary" style="margin-top:20px">&larr; Powrót do widoku tabeli: <?php echo $tab?></a>
                <h1>Dodanie zawartości do tabeli: <u><?php echo $tab?></u></h1>
                <div id="content">
                    <?php
                    $sql = "SHOW COLUMNS FROM $tab";
                    $tbHead = $conn->query($sql)->fetchAll();        

                    $ret = '<form action="insertact.php" method="post"><table class="table table-bordered">';
                    foreach($tbHead as $k => $t) {
                        if($t['Field']!='id') { 
                            $ret.= '<tr><th>'.$t['Field'].'</th><td><input type="text" class="form-control" name="tb_'.$t['Field'].'"></td></tr>';
                        }
                    }
                    $ret.='</table><input type="hidden" name="tab" value="'.$tab.'"><input type="submit" value="Dodaj" class="btn btn-danger btn-block"></form>';
                    echo $ret;
                    ?>
                </div>
            </div>
        </div>
</body>
</html>