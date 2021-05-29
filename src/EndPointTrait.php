<?php
namespace phpetrade;

trait EndPointTrait
{
 
    private function getResponse(Config $config,$url,$method = 'GET',$orderRequestArray = '')
    {
        $OAuthHTTPObj = new OAuthHTTP($config,$url,$method);
        if($orderRequestArray != "")
        {
            $OAuthHTTPObj->post_request = self::encodeXML($orderRequestArray,'',0);
        }
        return $OAuthHTTPObj->GetResponse();
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

    public function RandomString($limit = 16) 
    {
        //clientOrderId may be up to 20 alphanumeric character.
        $input = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $input_length = strlen($input);
        $random_string = '';
        for($i = 0; $i < $limit; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
        return $random_string;
    }
}