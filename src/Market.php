<?php

namespace phpetrade;

/*
 * Preps the url and query string for the ETrade API "Market" end points
 * before passing the final url to oauthhttp class
 */
use SimpleXMLElement;

class Market
{
    use EndPointTrait;

    public function __construct(protected Config $config)
    {
    }

    /**
     * @param $queryStringArray
     * @return SimpleXMLElement|string|bool|null
     * @throws \OAuthException
     */
    public function MarketGetQuotes($queryStringArray): SimpleXMLElement|string|bool|null
    {
        //No input parameters
        if (!isset($queryStringArray) || $queryStringArray == "") {
            print "Must submit a query string (at least symbols) to Market Quotes!\n";
            exit;
        } else {
            $this_url = $this->buildFullURL($this->config->url_getquote, $queryStringArray);
        }
        return $this->getResponse($this->config, $this_url);
    }

    /**
     * @param $queryStringArray
     * @return SimpleXMLElement|string|bool|null
     * @throws \OAuthException
     */
    public function MarketLookUp($queryStringArray): SimpleXMLElement|string|bool|null
    {
        //No input parameters
        if (!isset($queryStringArray) || $queryStringArray == "") {
            print "Must submit a query string (a search text) to Market Look Up!\n";
            exit;
        } else {
            $this_url = $this->buildFullURL($this->config->url_marketlookup, $queryStringArray);
        }
        return $this->getResponse($this->config, $this_url);
    }

    /**
     * @param $queryStringArray
     * @return SimpleXMLElement|string|bool|null
     * @throws \OAuthException
     */
    public function MarketGetOptionChain($queryStringArray): SimpleXMLElement|string|bool|null
    {
        //No input parameters
        if (!isset($queryStringArray) || $queryStringArray == "") {
            print "Must submit a query string (a symbol) to Market Get Option Chain!\n";
            exit;
        } else {
            $this_url = $this->buildFullURL($this->config->url_optionchains, $queryStringArray);
        }
        return $this->getResponse($this->config, $this_url);
    }

    /**
     * @param $queryStringArray
     * @return SimpleXMLElement|string|bool|null
     * @throws \OAuthException
     */
    public function MarketGetOptionExp($queryStringArray): SimpleXMLElement|string|bool|null
    {
        //No input parameters
        if (!isset($queryStringArray) || $queryStringArray == "") {
            print "Must submit a query string (a symbol) to Market Get Option Chain Expiration Dates!\n";
            exit;
        } else {
            $this_url = $this->buildFullURL($this->config->url_expirydates, $queryStringArray);
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
        $query_string = "";
        foreach ($queryParamsArray as $k=>$v) {
            if (is_array($v) && $k == "symbols") {
                //ETrade API does not like symbol to be urlencoded.
                //$symbol_string = "/" . implode(',',array_filter(array_map('urlencode', $v)));
                $symbols_string = "/" . implode(',', array_filter($v));
            } elseif ($k == "symbols") {
                $symbols_string =  "/" . urlencode($v);
            } elseif ($k == "search") {
                $search_string = "/" . urlencode($v);
            } elseif (!empty($k)) {
                $query_string .= $k . '=' . urlencode($v) . '&';
            } else {
            }
        }

        $query_string = rtrim($query_string, "&");
        if (isset($symbols_string) && $query_string != "") {
            $full_url = $url . $symbols_string . "?" . $query_string;
        } elseif (isset($symbols_string)) {
            $full_url = $url . $symbols_string;
        } elseif (isset($search_string)) {
            //search has no query string in the ETrade API definition as of time of writing... but it may in the future.
            if ($query_string != "") {
                $full_url = $url . $search_string . "?" . $query_string;
            } else {
                $full_url = $url . $search_string;
            }
        } else {
            $full_url = $url . "?" . $query_string;
        }

        return $full_url;
    }
}
