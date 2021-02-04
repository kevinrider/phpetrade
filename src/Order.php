<?php
namespace phpetrade;

//Preps the url and query string for the ETrade API "Orders" end points
//before passing the final url to oauthhttp class
//Works best with equity orders.  For option or more complex orders
//use the optionticket class.

class Order
{
    use EndPointTrait;
    
    public function ListOrders($account_id_key,$queryStringArray)
    {
        $this_url = str_replace("accountkeyid",$account_id_key,ORDER_LIST_URL);
        if(isset($queryStringArray) && $queryStringArray != "")
        {
            $this_url = $this->buildFullURL($this_url,$queryStringArray);
        }
        return $this->getResponse($this_url);
    }
    
    public function PreviewOrder($account_id_key,$orderRequestArray)
    {
        $this_url = str_replace("accountkeyid",$account_id_key,ORDER_PREVIEW_URL);
        if(!isset($orderRequestArray) || $orderRequestArray == "")
        {
            print "Must submit order to Preview Order!\n";
            exit;
        }
        return $this->getResponse($this_url,"POST",$orderRequestArray);
    }
    
    public function PlaceOrder($account_id_key,$orderRequestArray)
    {
        $this_url = str_replace("accountkeyid",$account_id_key,ORDER_PLACE_URL);
        if(!isset($orderRequestArray) || $orderRequestArray == "")
        {
            print "Must submit order to Place Order!\n";
            exit;
        }
        return $this->getResponse($this_url,"POST",$orderRequestArray);
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
        return $this->getResponse($this_url,"PUT",$orderRequestArray);
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
        return $this->getResponse($this_url,"PUT",$orderRequestArray);
    }
    
    public function CancelOrder($account_id_key,$orderRequestArray)
    {
        $this_url = str_replace("accountkeyid",$account_id_key,ORDER_CANCEL_URL);
        if(!isset($orderRequestArray) || $orderRequestArray == "")
        {
            print "Must submit orderId to Cancel Order!\n";
            exit;
        }
        return $this->getResponse($this_url,"PUT",$orderRequestArray);
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
