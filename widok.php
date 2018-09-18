<?php
$tab = $_REQUEST['tab'];
?><!doctype html>
<html>
<head>
	<meta charset='UTF-8' />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


	<script src="fancywebsocket.js"></script>
	<script>
		var Server;

		function log( text ) {
			$log = $('#log');
			$log.append(($log.val()?"\n":'')+text);
			$log[0].scrollTop = $log[0].scrollHeight - $log[0].clientHeight;
		}

		function send( text ) {
			log( "Wykonuje polecenie: "+text );
			Server.send('message',text );
		}

		$(document).ready(function() {
			log('Connecting...');
			Server = new FancyWebSocket('ws://127.0.0.1:9300');
        
            /*
            $('#btn-tblcontent').click(function() {
                send('table_content:'+'<?php echo $tab?>');
            });
            */
            
			Server.bind('open', function() {
				log( "Connected." );
                send('table_content:'+'<?php echo $tab?>');
			});

			Server.bind('close', function( data ) {
				log( "Disconnected." );
			});

			Server.bind('message', function( payload ) {

                //console.log(payload);
                var obj = jQuery.parseJSON(payload);
                if(obj[0]=='<?php echo $tab ?>') {
                    $('#content').html(obj[1]);
                    log( payload );                 
                }
			});

			Server.connect();
            

		});
	</script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12"><a href="/" class="btn btn-success" style="margin-top:20px">&larr; Powrót do strony głównej</a>
                <h1>Zawartość tabeli: <u><?php echo $tab?></u></h1>
                <div id="content"></div>
                <br/><!--<button class="btn btn-default" id="btn-tblcontent">Pobierz zawartość tabeli</button>-->
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="content"></div>
                    <br/><textarea class="form-control" id="log" name="log" readonly="readonly"></textarea>
            </div>
        </div>
</body>
</html>