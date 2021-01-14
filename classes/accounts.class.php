<?php

//Preps the url and query string for the ETrade API "Accounts" end points
//before passing the final url to oauthhttp class

class Accounts
{
    
    public function GetAccountList()
    {
        //No input parameters
        return $this->getAccountsResponse(URL_ACCOUNTLIST);
    }

    public function GetAccountBalance($account_id_key,$queryStringArray)
    {
        $this_url = str_replace("accountkeyid",$account_id_key,URL_ACCOUNTBALANCE);
        if(isset($queryStringArray) && $queryStringArray != "")
        {
            $this_url = $this->buildFullURL($this_url,$queryStringArray);
        }
        return $this->getAccountsResponse($this_url);
    }

    public function GetAccountTransactions($account_id_key,$queryStringArray)
    {
        $this_url = str_replace("accountkeyid",$account_id_key,URL_ACCOUNTTRANSACTIONS);
        if(isset($queryStringArray) && $queryStringArray != "")
        {
            $this_url = $this->buildFullURL($this_url,$queryStringArray);
        }
        return $this->getAccountsResponse($this_url);
    }

    public function GetAccountTransactionDetails($account_id_key,$tran_id,$queryStringArray)
    {
        $this_url = str_replace("accountkeyid",$account_id_key,URL_ACCOUNTTRANSACTIONSDETAILS);
        $this_url = str_replace("transid",$tran_id,$this_url);
        if(isset($queryStringArray) && $queryStringArray != "")
        {
            $this_url = $this->buildFullURL($this_url,$queryStringArray);
        }
        return $this->getAccountsResponse($this_url);
    }

    public function GetAccountPortfolio($account_id_key,$queryStringArray)
    {
        $this_url = str_replace("accountkeyid",$account_id_key,URL_ACCOUNTPORTFOLIO);
        if(isset($queryStringArray) && $queryStringArray != "")
        {
            $this_url = $this->buildFullURL($this_url,$queryStringArray);
        }
        return $this->getAccountsResponse($this_url);

    }

    private function getAccountsResponse($url,$method = 'GET')
    {
        print "$url\n";
        $OAuthHTTPObj = new OAuthHTTP($url);
        return $OAuthHTTPObj->GetResponse();
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