<?php

namespace phpetrade;

/*
 * Preps the url and query string for the ETrade API "Accounts" end points
 * before passing the final url to oauthhttp class
 */

use SimpleXMLElement;

class Accounts
{
    use EndPointTrait;

    public function __construct(protected Config $config)
    {
    }

    /**
     * @return SimpleXMLElement|null
     */
    public function GetAccountList(): SimpleXMLElement|string|bool
    {
        //No input parameters
        return $this->getResponse($this->config, $this->config->url_accountlist);
    }

    /**
     * @param $account_id_key
     * @param $queryStringArray
     * @return SimpleXMLElement|null
     */
    public function GetAccountBalance($account_id_key, $queryStringArray): SimpleXMLElement|string|bool
    {
        $this_url = str_replace("accountkeyid", $account_id_key, $this->config->url_accountbalance);
        if (isset($queryStringArray) && $queryStringArray != "") {
            $this_url = $this->buildFullURL($this_url, $queryStringArray);
        }
        return $this->getResponse($this->config, $this_url);
    }

    /**
     * @param $account_id_key
     * @param $queryStringArray
     * @return SimpleXMLElement|null
     */
    public function GetAccountTransactions($account_id_key, $queryStringArray): SimpleXMLElement|string|bool
    {
        $this_url = str_replace("accountkeyid", $account_id_key, $this->config->url_accounttransactions);
        if (isset($queryStringArray) && $queryStringArray != "") {
            $this_url = $this->buildFullURL($this_url, $queryStringArray);
        }
        return $this->getResponse($this->config, $this_url);
    }

    /**
     * @param $account_id_key
     * @param $tran_id
     * @param $queryStringArray
     * @return SimpleXMLElement|null
     */
    public function GetAccountTransactionDetails($account_id_key, $tran_id, $queryStringArray): SimpleXMLElement|string|bool
    {
        $this_url = str_replace("accountkeyid", $account_id_key, $this->config->url_accounttransactionsdetails);
        $this_url = str_replace("transid", $tran_id, $this_url);
        if (isset($queryStringArray) && $queryStringArray != "") {
            $this_url = $this->buildFullURL($this_url, $queryStringArray);
        }
        return $this->getResponse($this->config, $this_url);
    }

    /**
     * @param $account_id_key
     * @param $queryStringArray
     * @return SimpleXMLElement|null
     */
    public function GetAccountPortfolio($account_id_key, $queryStringArray): SimpleXMLElement|string|bool
    {
        $this_url = str_replace("accountkeyid", $account_id_key, $this->config->url_accountportfolio);
        if (isset($queryStringArray) && $queryStringArray != "") {
            $this_url = $this->buildFullURL($this_url, $queryStringArray);
        }
        return $this->getResponse($this->config, $this_url);
    }

    /**
     * @param $url
     * @param $queryParamsArray
     * @return string
     */
    public function buildFullURL($url, $queryParamsArray): string
    {
        $string = "";
        foreach ($queryParamsArray as $k=>$v) {
            if (!empty($k)) {
                $string .= $k . '=' . urlencode($v) . '&';
            }
        }
        $string = rtrim($string, "&");
        return $url . "?" . $string;
    }
}
