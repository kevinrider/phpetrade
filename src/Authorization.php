<?php
namespace phpetrade;

//Revoke and Renew Access Tokens.
//Get Request Token, Authorize App, and Get Access Token endpoints are handled in auth.php

class Authorization
{
    use EndPointTrait;
    public $config;

    function __construct()
    {
        $this->config = new Config(true);
    }

    public function RenewAccessToken()
    {
        //No input parameters
        return $this->getResponse($this->config->renew_token_url);
    }

    public function RevokeAccessToken()
    {
        //No input parameters
        return $this->getResponse($this->config->revoke_token_url);
    }

}