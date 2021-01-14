<?php

//Preps the url and query string for the ETrade API "Orders" end points
//before passing the final url to oauthhttp class
//Works best with equity orders.  For option or more more complex orders
//use the optionticket class.

class Order
{
    
    public function ListOrders($account_id_key,$queryStringArray)
    {
        $this_url = str_replace("accountkeyid",$account_id_key,ORDER_LIST_URL);
        if(isset($queryStringArray) && $queryStringArray != "")
        {
            $this_url = $this->buildFullURL($this_url,$queryStringArray);
        }
        return $this->getOrderResponse($this_url);
    }
    
    public function PreviewOrder($account_id_key,$orderRequestArray)
    {
        $this_url = str_replace("accountkeyid",$account_id_key,ORDER_PREVIEW_URL);
        if(!isset($orderRequestArray) || $orderRequestArray == "")
        {
            print "Must submit order to Preview Order!\n";
            exit;
        }
        return $this->getOrderResponse($this_url,"POST",$orderRequestArray);
    }
    
    public function PlaceOrder($account_id_key,$orderRequestArray)
    {
        $this_url = str_replace("accountkeyid",$account_id_key,ORDER_PLACE_URL);
        if(!isset($orderRequestArray) || $orderRequestArray == "")
        {
            print "Must submit order to Place Order!\n";
            exit;
        }
        return $this->getOrderResponse($this_url,"POST",$orderRequestArray);
    }
    
    public function ChangePreviewOrder($account_id_key,$order_id,$orderRequestArray)
    {
        $this_url = str_replace("accountkeyid",$account_id_key,ORDER_CHANGE_PREVIEW_URL);
        $this_url = str_replace("orderid",$order_id,$this_url);
        if(!isset($orderRequestArray) || $orderRequestArray == "")
        {
            print "Must submit order to Change Preview Order!\n";
            exit;
        }
        return $this->getOrderResponse($this_url,"PUT",$orderRequestArray);
    }
    
    public function PlaceChangeOrder($account_id_key,$order_id,$orderRequestArray)
    {
        $this_url = str_replace("accountkeyid",$account_id_key,ORDER_CHANGE_PLACE_URL);
        $this_url = str_replace("orderid",$order_id,$this_url);
        if(!isset($orderRequestArray) || $orderRequestArray == "")
        {
            print "Must submit order to Place Change Order!\n";
            exit;
        }
        return $this->getOrderResponse($this_url,"PUT",$orderRequestArray);
    }
    
    public function CancelOrder($account_id_key,$orderRequestArray)
    {
        $this_url = str_replace("accountkeyid",$account_id_key,ORDER_CANCEL_URL);
        if(!isset($orderRequestArray) || $orderRequestArray == "")
        {
            print "Must submit orderId to Cancel Order!\n";
            exit;
        }
        return $this->getOrderResponse($this_url,"PUT",$orderRequestArray);
    }

    private function getOrderResponse($url,$method = 'GET',$orderRequestArray = '')
    {
        print "$url\n";
        $OAuthHTTPObj = new OAuthHTTP($url,$method);
        if($orderRequestArray != "")
        {
            $OAuthHTTPObj->post_request = self::encodeXML($orderRequestArray,'',0);
        }
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
    
    public function encodeXML($data, $node, $depth) 
    {
        $xml = str_repeat("\t", $depth);
        if($depth == 0)
        {
            $xml .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        }
        else 
        {
            $xml .= "<$node>\n";
        }

        foreach($data as $key=>$val)
        {
            if(is_array($val)) 
            {
                $xml .= self::encodeXml($val, $key, ($depth + 1));
            } 
            else 
            {

                $xml .= str_repeat("\t", ($depth + 1));
                $xml .= "<$key>" . htmlspecialchars($val) . "</$key>\n";
            }
        }
        $xml .= str_repeat("\t", $depth);
        if($depth != "0")
        {
            $xml .= "</$node>\n";
        }
        return $xml;
    }
}

?>
