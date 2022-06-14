<?php

namespace phpetrade;

/*
 * Revoke and Renew Access Tokens.
 * Get Request Token, Authorize App, and Get Access Token endpoints are handled in auth.php
 */
use SimpleXMLElement;

class Authorization
{
    use EndPointTrait;

    public function __construct(protected Config $config)
    {
    }

    /**
     * @return SimpleXMLElement|string|bool|null
     */
    public function RenewAccessToken(): SimpleXMLElement|string|bool|null
    {
        //No input parameters
        return $this->getResponse($this->config, $this->config->renew_token_url);
    }

    /**
     * @return SimpleXMLElement|string|bool|null
     */
    public function RevokeAccessToken(): SimpleXMLElement|string|bool|null
    {
        //No input parameters
        return $this->getResponse($this->config, $this->config->revoke_token_url);
    }
}
