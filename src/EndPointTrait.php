<?php
namespace phpetrade;

trait EndPointTrait
{
 
    private function getResponse($url,$method = 'GET',$orderRequestArray = '')
    {
        print "$url\n";
        $OAuthHTTPObj = new OAuthHTTP($url,$method);
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
    
}