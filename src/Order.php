<?php

namespace phpetrade;

/*
 * Preps the url and query string for the ETrade API "Orders" end points
 * before passing the final url to oauthhttp class
 * Works best with equity orders.  For option or more complex orders
 * use the orderticket class.
 */

use SimpleXMLElement;

class Order
{
    use EndPointTrait;

    public function __construct(protected Config $config)
    {
    }

    /**
     * @param $account_id_key
     * @param $queryStringArray
     * @return SimpleXMLElement|string|bool|null
     * @throws \OAuthException
     */
    public function ListOrders($account_id_key, $queryStringArray): SimpleXMLElement|string|bool|null
    {
        $this_url = str_replace("accountkeyid", $account_id_key, $this->config->order_list_url);
        if (isset($queryStringArray) && $queryStringArray != "") {
            $this_url = $this->buildFullURL($this_url, $queryStringArray);
        }
        return $this->getResponse($this->config, $this_url);
    }

    /**
     * @param $account_id_key
     * @param $orderRequestArray
     * @return SimpleXMLElement|string|bool|null
     * @throws \OAuthException
     */
    public function PreviewOrder($account_id_key, $orderRequestArray): SimpleXMLElement|string|bool|null
    {
        $this_url = str_replace("accountkeyid", $account_id_key, $this->config->order_preview_url);
        if (!isset($orderRequestArray) || $orderRequestArray == "") {
            print "Must submit order to Preview Order!\n";
            exit;
        }
        return $this->getResponse($this->config, $this_url, "POST", $orderRequestArray);
    }

    /**
     * @param $account_id_key
     * @param $orderRequestArray
     * @return SimpleXMLElement|string|bool|null
     * @throws \OAuthException
     */
    public function PlaceOrder($account_id_key, $orderRequestArray): SimpleXMLElement|string|bool|null
    {
        $this_url = str_replace("accountkeyid", $account_id_key, $this->config->order_place_url);
        if (!isset($orderRequestArray) || $orderRequestArray == "") {
            print "Must submit order to Place Order!\n";
            exit;
        }
        return $this->getResponse($this->config, $this_url, "POST", $orderRequestArray);
    }

    /**
     * @param $account_id_key
     * @param $order_id
     * @param $orderRequestArray
     * @return SimpleXMLElement|string|bool|null
     * @throws \OAuthException
     */
    public function ChangePreviewOrder($account_id_key, $order_id, $orderRequestArray): SimpleXMLElement|string|bool|null
    {
        $this_url = str_replace("accountkeyid", $account_id_key, $this->config->order_change_preview_url);
        $this_url = str_replace("orderid", $order_id, $this_url);
        if (!isset($orderRequestArray) || $orderRequestArray == "") {
            print "Must submit order to Change Preview Order!\n";
            exit;
        }
        return $this->getResponse($this->config, $this_url, "PUT", $orderRequestArray);
    }

    /**
     * @param $account_id_key
     * @param $order_id
     * @param $orderRequestArray
     * @return SimpleXMLElement|string|bool|null
     * @throws \OAuthException
     */
    public function PlaceChangeOrder($account_id_key, $order_id, $orderRequestArray): SimpleXMLElement|string|bool|null
    {
        $this_url = str_replace("accountkeyid", $account_id_key, $this->config->order_change_place_url);
        $this_url = str_replace("orderid", $order_id, $this_url);
        if (!isset($orderRequestArray) || $orderRequestArray == "") {
            print "Must submit order to Place Change Order!\n";
            exit;
        }
        return $this->getResponse($this->config, $this_url, "PUT", $orderRequestArray);
    }

    /**
     * @param $account_id_key
     * @param $orderRequestArray
     * @return SimpleXMLElement|string|bool|null
     * @throws \OAuthException
     */
    public function CancelOrder($account_id_key, $orderRequestArray): SimpleXMLElement|string|bool|null
    {
        $this_url = str_replace("accountkeyid", $account_id_key, $this->config->order_cancel_url);
        if (!isset($orderRequestArray) || $orderRequestArray == "") {
            print "Must submit orderId to Cancel Order!\n";
            exit;
        }
        return $this->getResponse($this->config, $this_url, "PUT", $orderRequestArray);
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
        $full_url = $url . "?" . $string;
        return $full_url;
    }
}
