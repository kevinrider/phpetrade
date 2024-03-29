<?php
require_once __DIR__ . '/../vendor/autoload.php';
use phpetrade\Authorization;
use phpetrade\Config;

$config = new Config(true);
$auth_obj = new Authorization($config);

//Renew authenticated tokens
//Expect: "Access Token has been renewed"
$response = $auth_obj->RenewAccessToken();
echo $response;

//Revoke authenticated tokens
//Expect: Revoked Access Token 
$response = $auth_obj->RevokeAccessToken();
echo $response;

//Revoke authenticated tokens
//Expect: Invalid auth/bad request 401
$response = $auth_obj->RenewAccessToken();
echo $response;
