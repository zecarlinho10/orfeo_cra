<style>
	h3 {
		margin-bottom: 0;
	}
 
	.event {
		margin-bottom: 3rem;
	}
 
	.dates {
		font-style: italic;
	}
 
	.location {
		font-size: .8rem;
	}
</style>
 
<?php
error_reporting(0); 
 
/*
 * https://developer.microsoft.com/en-us/graph/docs/api-reference/beta/api/user_list_calendars
 */
 
 
// Require our 3rd party libraries such as Oauth2
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . "/config.php";
// Include the Microsoft Graph classes
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
 
// This is taken from the apps.dev.microsoft.com portal an is the "Application ID"
#const CLIENT_ID     = 'this is your client ID';
 
// This is the secret ITS generates for you
#const CLIENT_SECRET = '';
 
// This should be one of the reply URLs set in your application
#const REDIRECT_URI           = 'https://app.sog.unc.edu';
 
// This is the object id of the user we want to use
#const USER = 'this is the user ID';
 
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
 
// Decode any exceptions Guzzle throws
} catch (GuzzleHttp\Exception\ClientException $e) {
    $response = $e->getResponse();
    $responseBodyAsString = $response->getBody()->getContents();
    echo $responseBodyAsString;
    exit();
}
?>
 
<h1>Mostrando Mensajes de correo@cra.gov.co</h1>
 
<?php foreach( $result->value as $event ): ?>
	<div class="event">	
		<h3><?php echo $event->subject; ?></h3>
 
		<?php if( $event->isAllDay ): ?>
			<div class="dates">
				<?php
					$start = new DateTime( $event->start->dateTime );
					$start->setTimezone( new DateTimeZone( 'America/Bogota' ) );
				?>
 
				<?php echo $start->format( 'l F jS' ); ?> - This event takes place all day.
			</div>
		<?php else: ?>	
			<div class="dates">
				<?php
					$start = new DateTime( $event->start->dateTime );
					$start->setTimezone( new DateTimeZone( 'America/Bogota' ) );
 
					$end = new DateTime( $event->end->dateTime );
					$end->setTimezone( new DateTimeZone( 'America/Bogota' ) );
 
					echo $start->format( 'l F jS g:ia' ) . ' - ' . $end->format( 'l F jS g:ia' );
				?>
			</div>
		<?php endif; ?>
 
		<div class="body">
			<?php echo $body; ?>
		</div>
 
		<?php if( strlen( $event->location->displayName ) > 0 ): ?>
			<div class="location">
				<label>Location: </label> <?php echo $event->location->displayName; ?>
			</div>
		<?php endif; ?>
	</div>
<?php endforeach; ?>


