<?php
namespace phpetrade;

//Preps the url and query string for the ETrade API "Alerts" end points
//before passing the final url to oauthhttp class

class Alerts
{
    use EndPointTrait;
    
    public function AlertsList($queryStringArray)
    {
        $this_url = $this->buildFullURL(LIST_ALERTS_URL,$queryStringArray);
        return $this->getResponse($this_url);
    }

    public function AlertsListDetails($queryStringArray)
    {
        //No input parameters
        if(!isset($queryStringArray) || $queryStringArray == "")
        {
            print "Must submit a query string (an alert id) to Alert Details!\n";
            exit;
        }
        else
        {
            $this_url = $this->buildFullURL(ALERT_DETAILS_URL,$queryStringArray);
        }
        return $this->getResponse($this_url);
    }

    public function AlertsDelete($queryStringArray)
    {
        //No input parameters
        if(!isset($queryStringArray) || $queryStringArray == "")
        {
            print "Must submit a query string (an alert id) to Delete Alert!\n";
            exit;
        }
        else
        {
            $this_url = $this->buildFullURL(DELETE_ALERT_URL,$queryStringArray);
        }
        $method = "DELETE";
        return $this->getResponse($this_url,$method);
    }

    
    
    public function buildFullURL($url,$queryParamsArray)
    {
        $query_string = "";
        print_r($queryParamsArray);
        foreach($queryParamsArray as $k=>$v)
        {
            //print "$k - $v\n";
            //print_r($v);
            if(is_array($v) && $k == "id")
            {
                $id_string = "/" . implode(',', array_filter($v));
            }
            elseif($k == "id")
            {
                $id_string =  "/" . urlencode($v);
            }
            elseif(!empty($k))
            {
                $query_string .= $k.'='. urlencode($v) .'&';
            }
            else
            {
            }
        }
        //exit;
        $query_string = rtrim($query_string,"&");
        if(isset($id_string))
        {
            if($query_string != "")
            {
                $full_url = $url . $id_string . "?" . $query_string;
            }
            else
            {
                $full_url = $url . $id_string;
            }
        }
        elseif($query_string != "")
        {
            $full_url = $url . "?" . $query_string;
        }
        else
        {
            $full_url = $url;
        }
        //$full_url = $url . $symbol_string;
        return $full_url;
    }
}

?>