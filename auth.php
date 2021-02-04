<?php
require_once __DIR__ . '/vendor/autoload.php';

//Setup the OAuth 1.0a exchange.
//Must have pecl oauth installed and enabled in php.ini.
try 
{
    $oauth = new OAuth(APP_KEY,APP_SECRET);
    //The "oob" call back is required by ETrade API when authenticating.
    $request_token_info = $oauth->getRequestToken(REQUEST_TOKEN_URL,"oob");
    if(empty($request_token_info)) 
    {
        print "Failed fetching request token, response was: " . $oauth->getLastResponse();
    }
} 
catch(OAuthException $E) 
{
    echo "Response: ". $E->lastResponse . "\n";
}

$auth_url =  AUTHORIZE_URL . "?key=" . APP_KEY . "&token=" . urlencode($request_token_info['oauth_token']);

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
    $access_token_info = $oauth->getAccessToken(ACCESS_TOKEN_URL,'',$v,'GET');
}
catch (OAuthException $E) 
{
    echo "Response: ". $E->lastResponse . "\n";
}


if(isset($access_token_info['oauth_token']) and isset($access_token_info['oauth_token_secret']))
{
    echo "Here is your final authorized token and has been written to tokens.php";
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

//Write the new config.php file with the access token's swapped in.

$file_name = dirname(__FILE__) . "/src/tokens.tpl";
$out_name = dirname(__FILE__) . "/src/tokens.php";

$fd = fopen($file_name,"r");
$file_data = fread($fd, filesize($file_name));
fclose($fd);

$file_data = Parse("ACCESS_TOKEN",$access_token_info['oauth_token'],$file_data);
$file_data = Parse("TOKEN_SECRET",$access_token_info['oauth_token_secret'],$file_data);

$fp = fopen("$out_name", 'w');
fwrite($fp, $file_data);
fclose($fp);

function Parse($key, $value, $file_data)
{
        $key = '/{' . "$key" . '}/';
        $file_data = preg_replace("$key","$value",$file_data);
        return $file_data;
}

?>
