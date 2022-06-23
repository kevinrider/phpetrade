<?php

namespace phpetrade;

/*
 * Preps the url and query string for the ETrade API "Alerts" end points
 * before passing the final url to oauthhttp class
 */

use OAuthException;
use SimpleXMLElement;

class Alerts
{
    use EndPointTrait;

    public function __construct(protected Config $config)
    {
    }

    /**
     * @param $queryStringArray
     * @return SimpleXMLElement|string|bool
     * @throws OAuthException
     */
    public function AlertsList($queryStringArray): SimpleXMLElement|string|bool
    {
        $this_url = $this->buildFullURL($this->config->list_alerts_url, $queryStringArray);
        return $this->getResponse($this->config, $this_url);
    }

    /**
     * @param $queryStringArray
     * @return SimpleXMLElement|string|bool
     * @throws OAuthException
     */
    public function AlertsListDetails($queryStringArray): SimpleXMLElement|string|bool
    {
        //No input parameters
        if (!isset($queryStringArray) || $queryStringArray == "") {
            print "Must submit a query string (an alert id) to Alert Details!\n";
            exit;
        } else {
            $this_url = $this->buildFullURL($this->config->alert_details_url, $queryStringArray);
        }
        return $this->getResponse($this->config, $this_url);
    }

    /**
     * @param $queryStringArray
     * @return SimpleXMLElement|string|bool
     * @throws OAuthException
     */
    public function AlertsDelete($queryStringArray): SimpleXMLElement|string|bool
    {
        //No input parameters
        if (!isset($queryStringArray) || $queryStringArray == "") {
            print "Must submit a query string (an alert id) to Delete Alert!\n";
            exit;
        } else {
            $this_url = $this->buildFullURL($this->config->delete_alert_url, $queryStringArray);
        }
        $method = "DELETE";
        return $this->getResponse($this->config, $this_url, $method);
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
            if (is_array($v) && $k == "id") {
                $id_string = "/" . implode(',', array_filter($v));
            } elseif ($k == "id") {
                $id_string =  "/" . urlencode($v);
            } elseif (!empty($k)) {
                $query_string .= $k . '=' . urlencode($v) . '&';
            }
        }

        $query_string = rtrim($query_string, "&");
        if (isset($id_string)) {
            if ($query_string != "") {
                $full_url = $url . $id_string . "?" . $query_string;
            } else {
                $full_url = $url . $id_string;
            }
        } elseif ($query_string != "") {
            $full_url = $url . "?" . $query_string;
        } else {
            $full_url = $url;
        }

        return $full_url;
    }
}
