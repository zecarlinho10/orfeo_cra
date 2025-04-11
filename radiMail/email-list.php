<?php
session_start();

$ruta = '../bodega';
$ruta_raiz = "../";
include ("$ruta_raiz/config.php");
include ("funcionesIMAP.php");
if (! $_SESSION['dependencia'])
    die("<center>Sesion terminada, vuelve a iniciar sesion <a href='../cerrar_session.php'>aqui.<a></center>");
if (isset($_GET['force'])) {
    unset($_SESSION['emails']);
}

foreach ($_GET as $key => $val) {
    ${$key} = $val;
}
foreach ($_POST as $key => $val) {
    ${$key} = $val;
}

//$inbox = $_SESSION['inbox'];

require_once __DIR__ . '/graph/vendor/autoload.php';
require_once __DIR__ . '/graph/config.php';
// Include the Microsoft Graph classes
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

 
// Set up a new Oauth2 client that will be talking to Azure
$provider = new TheNetworg\OAuth2\Client\Provider\Azure([
    'clientId'                => CLIENT_ID,
    'clientSecret'            => CLIENT_SECRET,
    'redirectUri'             => REDIRECT_URI,
]);
 
// Change the URLs of our Oauth2 request to the correct endpoint and tenant
$provider->urlAPI = "https://graph.microsoft.com/v1.0/";
$provider->resource = "https://graph.microsoft.com/";
$provider->tenant = 'cra.gov.co';

$options = array();

 
// Try to get an access token using the client credentials grant.
$accessToken = $provider->getAccessToken( 'client_credentials', $options );



// Start a new Guzzle client
$client = new \GuzzleHttp\Client();
 
// Set up headers
$headers = [
    'Authorization' => 'Bearer ' . $accessToken->getToken(),
];

//echo $accessToken->getToken();
// Wrap our HTTP request in a try/catch block so we can decode problems
try {
 
	// Set up our request to the API
	$response = $client->request(
		'GET',
		'https://graph.microsoft.com/v1.0/users/' . USER . '/messages',
		array( 'headers' => $headers )
	);
 
	// Store the result as an object
	$result = json_decode( $response->getBody() );
 	//print_r($result);

// Decode any exceptions Guzzle throws
} catch (GuzzleHttp\Exception\ClientException $e) {
    $response = $e->getResponse();
    $responseBodyAsString = $response->getBody()->getContents();
    echo $responseBodyAsString;
    exit();
}

?>
<table id="inbox-table" class="table table-striped table-hover">
	<tbody>
<?php
/*
if (empty($fullUser) || $fullUser == false) {
    $usua_email = current(explode("@", $usua_email));
}
if (empty($inbox) || ! is_resource($inbox)) {
    $hostname = '{' . "$servidor_mail:$puerto_mail/$protocolo_mail/ssl/novalidate-cert" . '}';
    $inbox = imap_open($hostname, $usua_email, $passwd_mail) or die('Error al conectar a e-mail');
}
$emailCount = imap_num_msg($inbox);
*/
/* if emails are returned, cycle through each... */

    //$overview = imap_fetch_overview($inbox, "$inicio:$top");
    $i=0;
    //for ($i = count($overview) - 1; $i > count($overview) - 50 && $i >= 0; $i --) {
    foreach( $result->value as $event ){
    	/*
    	if( $event->isAllDay ){
			$start = new DateTime( $event->start->dateTime );
			$start->setTimezone( new DateTimeZone( 'America/Bogota' ) );
		//	echo $start->format( 'l F jS' );
		}
		else{
			$start = new DateTime( $event->start->dateTime );
			$start->setTimezone( new DateTimeZone( 'America/Bogota' ) );
 			$end = new DateTime( $event->end->dateTime );
			$end->setTimezone( new DateTimeZone( 'America/Bogota' ) );
		//	echo $start->format( 'l F jS g:ia' ) . ' - ' . $end->format( 'l F jS g:ia' );
		}
		*/
		/*
        $output .= $i . '<span class="subject">subject:' . $event->subject . '</span> ';
        $output .= '</div><br>';
        */
        //$asunto = mb_decode_mimeheader($event->subject);
        $asunto = $event->subject;
        //$mailFrom = imap_utf8(trim($event->from));
        //$mailFrom = $event->from;
        $mailFrom = $event->from->emailAddress->address;
        //mb_internal_encoding('UTF-8');
        $emails[$i]['uid'] = $event->id;
        //$emails[$i]['mailAsunto'] = str_to_utf8($asunto); 
        $emails[$i]['mailAsunto'] = $asunto; 
        //$emails[$i]['mailFecha'] = substr($overview[$i]->date, 0, 12);
        $emails[$i]['mailFecha'] = $event->receivedDateTime;
        //$emails[$i]['mailFrom'] = str_replace("_", " ", mb_decode_mimeheader($mailFrom));
        $emails[$i]['mailFrom'] = $mailFrom;
        //$emails[$i]['mailToF'] = imap_utf8(trim($overview[$i]->to));
        //$emails[$i]['mailToF'] = $event->toRecipients[0]->emailAddress->address;
        $emails[$i]['mailAttach'] = "";
        $uid = $emails[$i]['uid'];
        /*
        echo "<br>DATOS VETOR emails:";
        echo "<br>emails[i][uid]" . $emails[$i]['uid'];
        echo "<br>emails[i][mailAsunto]" . $emails[$i]['mailAsunto'];
        echo "<br>emails[i][mailFecha]" . $emails[$i]['mailFecha'];
        echo "<br>emails[i][mailFrom]" . $emails[$i]['mailFrom'];
        //echo "<br>emails[i][mailToF]" . $emails[$i]['mailToF'];
        echo "<br>emails[i][mailToF]: " . $event->toRecipients[0]->emailAddress->address;
        //echo "<br>emails[i][mailToF]: ";
        //print_r($event->toRecipients);
        echo "<br>emails[i][mailAttach]" . $emails[$i]['mailAttach'];
        echo "<br>END...........";
        */
        ?>
        <tr id="<?=$uid?>" class="unread">
			<td id="<?=$uid?>" class="inbox-table-icon">
				<div class="">
					<span><span class="label bg-color-orange"><?=//$numMail
																$i?></span><span>
				
				</div>
			</td>
			<td id="<?=$uid?>" class="inbox-data-from hidden-xs hidden-sm">
				<div><?=$emails[$i]['mailFrom']?>
				</div>
			</td>
			<td id="<?=$uid?>" class="inbox-data-message">
				<div><?=substr($emails[$i]['mailAsunto'],0,100)?>
				</div>
			</td>
			<td id="<?=$uid?>" class="inbox-data-attachment hidden-xs">
				<div>
					<a href="javascript:void(0);" rel="tooltip" data-placement="left"
						data-original-title="FILES: rocketlaunch.jpg, timelogs.xsl"
						class="txt-color-darken"><i class="fa fa-paperclip fa-lg"></i></a>
				</div>
			</td>
			<td id="<?=$uid?>" class="inbox-data-date hidden-xs">
				<div>
					<?=$emails[$i]['mailFecha']?>
				</div>
			</td>
		</tr>
		<?
//        $numMail ++;
        $i++;
    }
?>
	</tbody>
</table>

<script>
	
	//Gets tooltips activated
	$("#inbox-table [rel=tooltip]").tooltip();

	$("#inbox-table input[type='checkbox']").change(function() {
		$(this).closest('tr').toggleClass("highlight", this.checked);
	});

	$("#inbox-table .inbox-data-message").click(function() {
		$this = $(this);
		getMail($this);
	})
	$("#inbox-table .inbox-data-from").click(function() {
		$this = $(this);
		getMail($this);
	})
	function getMail($this) {
		var uid=($this.attr("id"));
		loadURL("email-opened.php?uid="+uid, $('#inbox-content > .table-wrap'));
	}


	$('.inbox-table-icon input:checkbox').click(function() {
		enableDeleteButton();
	})

	$(".deletebutton").click(function() {
		$('#inbox-table td input:checkbox:checked').parents("tr").rowslide();
		//$(".inbox-checkbox-triggered").removeClass('visible');
		//$("#compose-mail").show();
	});

	function enableDeleteButton() {
		var isChecked = $('.inbox-table-icon input:checkbox').is(':checked');

		if (isChecked) {
			$(".inbox-checkbox-triggered").addClass('visible');
			//$("#compose-mail").hide();
		} else {
			$(".inbox-checkbox-triggered").removeClass('visible');
			//$("#compose-mail").show();
		}
	}
	window.onload = function() {
		$(".inbox-load").html("Inbox (<?=$emailCount?>)");
	}
	
</script>
