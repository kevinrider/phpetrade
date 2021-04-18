<?php
namespace phpetrade;

//Revoke and Renew Access Tokens.
//Get Request Token, Authorize App, and Get Access Token endpoints are handled in auth.php

class Authorization
{
    use EndPointTrait;

    public function RenewAccessToken()
    {
        //No input parameters
        return $this->getResponse(RENEW_TOKEN_URL);
    }

    public function RevokeAccessToken()
    {
        //No input parameters
        return $this->getResponse(REVOKE_TOKEN_URL);
    }

}