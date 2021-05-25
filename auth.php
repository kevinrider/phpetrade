<?php
namespace phpetrade;
require_once __DIR__ . '/vendor/autoload.php';

$config = new Config();
//Setup the OAuth 1.0a exchange.
//Must have pecl oauth installed and enabled in php.ini.
try 
{
    $oauth = new \OAuth($config->app_key,$config->app_secret);
    //The "oob" call back is required by ETrade API when authenticating.
    $request_token_info = $oauth->getRequestToken($config->request_token_url,"oob");
    if(empty($request_token_info)) 
    {
        print "Failed fetching request token, response was: " . $oauth->getLastResponse();
    }
} 
catch(OAuthException $E) 
{
    echo "Response: ". $E->lastResponse . "\n";
}

$auth_url =  $config->authorize_url . "?key=" . $config->app_key . "&token=" . urlencode($request_token_info['oauth_token']);

echo "Your token authorize URL is : \n";
echo "\n---------------------------------------------------------------\n";
echo $auth_url . "\n";
echo "\n---------------------------------------------------------------\n";
echo "Please follow the above URL and get the verifier code (required to get the final access token).\n\n";

echo "Please enter the verifier code :";
$h = fopen ("php://stdin","r");
$v = fgets($h);
$v = trim($v);
fclose($h);

$oauth->setToken($request_token_info['oauth_token'],$request_token_info['oauth_token_secret']);
try
{
    $access_token_info = $oauth->getAccessToken($config->access_token_url,'',$v,'GET');
}
catch (OAuthException $E) 
{
    echo "Response: ". $E->lastResponse . "\n";
}


if(isset($access_token_info['oauth_token']) && isset($access_token_info['oauth_token_secret']))
{
    echo "Final access tokens have been written to tokens.inc";
    echo "\n---------------------------------------------------------------\n";
    echo "\nToken   : ". $access_token_info['oauth_token'];
    echo "\nSecret  : ". $access_token_info['oauth_token_secret'];
    echo "\n---------------------------------------------------------------\n";
}
else
{
    echo "Could not get request token...\n";
    exit;
}

//Store tokens
$token_array[0] = $access_token_info['oauth_token'];
$token_array[1] = $access_token_info['oauth_token_secret'];
$s = serialize($token_array);
file_put_contents($config->token_file, $s);
