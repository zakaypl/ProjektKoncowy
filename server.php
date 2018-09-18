<?php
set_time_limit(0);
require 'class.PHPWebSocket.php';

require_once 'config.php';

$dsn = "mysql:host=$host;port=$port;dbname=$database";
$conn = new \PDO($dsn, $username, $password, array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

function wsOnMessage($clientID, $message, $messageLength, $binary) {
	global $Server;
    global $conn;
    
	if ($messageLength == 0) {
		$Server->wsClose($clientID);
		return;
	}
    
    $arg = explode(':',$message);
    switch($arg[0]) {
        case 'table_list': {
            $sql = "SHOW TABLES";
            $tbList = $conn->query($sql)->fetchAll();        
            $ret = '<select class="form-control" id="list">';
            foreach($tbList as $t) {
                $ret.= '<option value="'.$t['Tables_in_ps2'].'">'.$t['Tables_in_ps2'].'</option>';
            }
            $ret.='</select><button class="btn btn-primary" id="rdr">Pokaż zawartość tabeli</button>';
            
        }
        break;
        
        case 'table_content': {
            $sql = "SHOW COLUMNS FROM $arg[1]";
            $tbHead = $conn->query($sql)->fetchAll();        
            $ret = '<a href="dodaj.php?tab='.$arg['1'].'" class="btn btn-primary" style="margin:20px 0">Dodaj</a><br/><table class="table table-bordered"><tr>';
            foreach($tbHead as $t) {
                $ret.= '<th>'.$t['Field'].'</th>';
            }
            $ret.='</th><th>Opcje</th></tr>';
            
            $sql = "SELECT * FROM `$arg[1]`";
            $tbList = $conn->query($sql)->fetchAll();        

            
            
            foreach($tbList as $t) {
                $ret.= '<tr>';
                foreach($tbHead as $k => $x) {
                    $ret.= '<td>'.$t[$k].'</td>';    
                }
                $ret.='<td><a class="btn btn-primary" href="edytuj.php?tab='.$arg[1].'&id='.$t[0].'">Edytuj</a> <a class="btn btn-danger" href="usun.php?tab='.$arg[1].'&id='.$t[0].'">Usuń</a></td></tr>';
            }
            $ret.='</table>';
        }
        break;
    }        
        $r[] = $arg[1];
        $r[] = $ret;
        
    	foreach ( $Server->wsClients as $id => $client ) {
            $Server->wsSend($id,json_encode($r));
        }
}

function wsOnOpen($clientID)
{
	global $Server;
	$ip = long2ip( $Server->wsClients[$clientID][6] );

	$Server->log( "$ip ($clientID) has connected." );

//	foreach ( $Server->wsClients as $id => $client )
//		if ( $id != $clientID )
//			$Server->wsSend($id, "Visitor $clientID ($ip) has joined the room.");
}

function wsOnClose($clientID, $status) {
	global $Server;
	$ip = long2ip( $Server->wsClients[$clientID][6] );

	$Server->log( "$ip ($clientID) has disconnected." );

	//Send a user left notice to everyone in the room
//	foreach ( $Server->wsClients as $id => $client )
//		$Server->wsSend($id, "Visitor $clientID ($ip) has left the room.");
}

// start the server
$Server = new PHPWebSocket();
$Server->bind('message', 'wsOnMessage');
$Server->bind('open', 'wsOnOpen');
$Server->bind('close', 'wsOnClose');

$Server->wsStartServer('127.0.0.1', 9300);

?>