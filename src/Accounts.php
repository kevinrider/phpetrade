<?php
namespace phpetrade;

//Preps the url and query string for the ETrade API "Accounts" end points
//before passing the final url to oauthhttp class

class Accounts
{
    use EndPointTrait;
    public $config;

    function __construct()
    {
        $this->config = new Config(true);
    }
    
    public function GetAccountList()
    {
        //No input parameters
        return $this->getResponse($this->config->url_accountlist);
    }

    public function GetAccountBalance($account_id_key,$queryStringArray)
    {
        $this_url = str_replace("accountkeyid",$account_id_key,$this->config->url_accountbalance);
        if(isset($queryStringArray) && $queryStringArray != "")
        {
            $this_url = $this->buildFullURL($this_url,$queryStringArray);
        }
        return $this->getResponse($this_url);
    }

    public function GetAccountTransactions($account_id_key,$queryStringArray)
    {
        $this_url = str_replace("accountkeyid",$account_id_key,$this->config->url_accounttransactions);
        if(isset($queryStringArray) && $queryStringArray != "")
        {
            $this_url = $this->buildFullURL($this_url,$queryStringArray);
        }
        return $this->getResponse($this_url);
    }

    public function GetAccountTransactionDetails($account_id_key,$tran_id,$queryStringArray)
    {
        $this_url = str_replace("accountkeyid",$account_id_key,$this->config->url_accounttransactionsdetails);
        $this_url = str_replace("transid",$tran_id,$this_url);
        if(isset($queryStringArray) && $queryStringArray != "")
        {
            $this_url = $this->buildFullURL($this_url,$queryStringArray);
        }
        return $this->getResponse($this_url);
    }

    public function GetAccountPortfolio($account_id_key,$queryStringArray)
    {
        $this_url = str_replace("accountkeyid",$account_id_key,$this->config->url_accountportfolio);
        if(isset($queryStringArray) && $queryStringArray != "")
        {
            $this_url = $this->buildFullURL($this_url,$queryStringArray);
        }
        return $this->getResponse($this_url);

    } 

    public function buildFullURL($url,$queryParamsArray)
    {
        $string = "";
        foreach($queryParamsArray as $k=>$v)
        {
            if(!empty($k))
            {
                    $string .= $k.'='. urlencode($v) .'&';
            }
        }
        $string = rtrim($string,"&");
        $full_url = $url . "?" . $string;
        return $full_url;
    }
}

?>