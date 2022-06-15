<?php

namespace phpetrade;

use OAuthException;
use SimpleXMLElement;

trait EndPointTrait
{

    /**
     * @param Config $config
     * @param string $url
     * @param string $method
     * @param array $orderRequestArray
     * @return SimpleXMLElement|string|bool|null
     * @throws OAuthException
     */
    private function getResponse(Config $config, string $url, string $method = 'GET', array $orderRequestArray = []): SimpleXMLElement|string|bool|null
    {
        $OAuthHTTPObj = new OAuthHTTP($config, $url, $method);
        if (!empty($orderRequestArray)) {
            $OAuthHTTPObj->post_request = self::encodeXML($orderRequestArray, '', 0);
        }
        return $OAuthHTTPObj->GetResponse();
    }

    /**
     * @param $data
     * @param $node
     * @param $depth
     * @return string
     */
    public function encodeXML($data, $node, $depth): string
    {
        $xml = str_repeat("\t", $depth);
        if ($depth == 0) {
            $xml .= "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        } else {
            $xml .= "<$node>\n";
        }

        foreach ($data as $key=>$val) {
            if (is_array($val)) {
                $xml .= self::encodeXml($val, $key, ($depth + 1));
            } else {
                $xml .= str_repeat("\t", ($depth + 1));
                $xml .= "<$key>" . htmlspecialchars($val) . "</$key>\n";
            }
        }
        $xml .= str_repeat("\t", $depth);
        if ($depth != "0") {
            $xml .= "</$node>\n";
        }
        return $xml;
    }

    /**
     * @param $limit
     * @return string
     */
    public function RandomString($limit = 16): string
    {
        //clientOrderId may be up to 20 alphanumeric character.
        $input = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $input_length = strlen($input);
        $random_string = '';
        for ($i = 0; $i < $limit; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
        return $random_string;
    }
}
