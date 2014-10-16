<?php

$dsn      = 'mysql:dbname=my_oauth2_db;host=localhost';
$username = 'root';
$password = 'root';

// error reporting (this is a demo, after all!)
ini_set('display_errors',1);error_reporting(E_ALL);

// Autoloading (composer is preferred, but for this example let's just do this)
require_once('oauth2-server-php/src/OAuth2/Autoloader.php');
OAuth2\Autoloader::register();

// $dsn is the Data Source Name for your database, for example"mysql:dbname=my_oauth2_db;host=localhost"
$storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $username, 'password' => $password));

// set default expiration date
$expirationDefault = 60;

// set maximum expiration date based on mysql timestamp max range
$expirationMax = strtotime('2038-01-01 00:00:00') - strtotime("now");

// set the expiration / access lifetime based on grant type request
$accessLifetime = $expirationMax;
if(isset($_REQUEST['grant_type'])){
	switch ($_REQUEST['grant_type']) {
		case 'password':
			$accessLifetime = $expirationMax;
			break;
		case 'refresh_token':
			$accessLifetime = $expirationDefault;
			break;
	}
}

// Pass a storage object or array of storage objects to the OAuth2 server class
$server = new OAuth2\Server($storage, array(
	'access_lifetime' => $accessLifetime,
	'refresh_token_lifetime' => $expirationMax
));

// Add the "Client Credentials" grant type
$server->addGrantType(new OAuth2\GrantType\UserCredentials($storage));

// Add the "Refresh Token" grant type
$server->addGrantType(new OAuth2\GrantType\RefreshToken($storage, array(
    'always_issue_new_refresh_token' => false
)));