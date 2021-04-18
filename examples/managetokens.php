<?php
require_once __DIR__ . '/../vendor/autoload.php';
use phpetrade\Authorization;

$auth_obj = new Authorization();

//Renew authenticated tokens
//Expect: "Access Token has been renewed"
$response = $auth_obj->RenewAccessToken();
echo $response;

//Revoke authenticated tokens
//Expect: Revoked Access Token 
$auth_obj->RevokeAccessToken();
echo $response;

//Revoke authenticated tokens
//Expect: Invalid auth/bad request 401
$auth_obj->RenewAccessToken();
echo $response;
