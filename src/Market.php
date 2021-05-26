<?php
namespace phpetrade;

//Preps the url and query string for the ETrade API "Market" end points
//before passing the final url to oauthhttp class

class Market
{
    use EndPointTrait;
    public $config;

    function __construct()
    {
        $this->config = new Config(true);
    }

    public function MarketGetQuotes($queryStringArray)
    {
        //No input parameters
        if(!isset($queryStringArray) || $queryStringArray == "")
        {
            print "Must submit a query string (at least symbols) to Market Quotes!\n";
            exit;
        }
        else
        {
            $this_url = $this->buildFullURL($this->config->url_getquote,$queryStringArray);
        }
        return $this->getResponse($this_url);
    }

    public function MarketLookUp($queryStringArray)
    {
        //No input parameters
        if(!isset($queryStringArray) || $queryStringArray == "")
        {
            print "Must submit a query string (a search text) to Market Look Up!\n";
            exit;
        }
        else
        {
            $this_url = $this->buildFullURL($this->config->url_marketlookup,$queryStringArray);
        }
        return $this->getResponse($this_url);
    }

    public function MarketGetOptionChain($queryStringArray)
    {
        //No input parameters
        if(!isset($queryStringArray) || $queryStringArray == "")
        {
            print "Must submit a query string (a symbol) to Market Get Option Chain!\n";
            exit;
        }
        else
        {
            $this_url = $this->buildFullURL($this->config->url_optionchains,$queryStringArray);
        }
        return $this->getResponse($this_url);
    }

    public function MarketGetOptionExp($queryStringArray)
    {
        //No input parameters
        if(!isset($queryStringArray) || $queryStringArray == "")
        {
            print "Must submit a query string (a symbol) to Market Get Option Chain Expiration Dates!\n";
            exit;
        }
        else
        {
            $this_url = $this->buildFullURL($this->config->url_expirydates,$queryStringArray);
        }
        return $this->getResponse($this_url);
    }

    

    public function buildFullURL($url,$queryParamsArray)
    {
        $query_string = "";
        foreach($queryParamsArray as $k=>$v)
        {
            if(is_array($v) && $k == "symbols")
            {
                //ETrade API does not like symbol to be urlencoded.
                //$symbol_string = "/" . implode(',',array_filter(array_map('urlencode', $v)));
                $symbols_string = "/" . implode(',', array_filter($v));
            }
            elseif($k == "symbols")
            {
                $symbols_string =  "/" . urlencode($v);
            }
            elseif($k == "search")
            {
                $search_string = "/" . urlencode($v);
            }
            elseif(!empty($k))
            {
                $query_string .= $k.'='. urlencode($v) .'&';
            }
            else
            {
            }
        }

        $query_string = rtrim($query_string,"&");
        if(isset($symbols_string) && $query_string != "")
        {
            $full_url = $url . $symbols_string . "?" . $query_string;
        }
        elseif(isset($symbols_string))
        {
            $full_url = $url . $symbols_string;
        }
        elseif(isset($search_string))
        {
            //search has no query string in the ETrade API definition as of time of writing... but it may in the future.
            if($query_string != "")
            {
                $full_url = $url . $search_string . "?" . $query_string;
            }
            else
            {
                $full_url = $url . $search_string;
            }
        }
        else
        {
            $full_url = $url . "?" . $query_string;
        }
        
        return $full_url;
    }
}