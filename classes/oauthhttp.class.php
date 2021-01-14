<?php

//Class handles all of the OAuth signing and header creation and HTTP communication.
//Assumes XML body response (default for ETrade API v1).

class OAuthHTTP
{
    function __construct($url,$method = "GET")
    {
        $this->url = $url;
        $this->method = $method;
        $this->post_request = "";
        $this->content_type = "xml";
    }

    public function GetResponse()
    {
        $oauth = new OAuth(APP_KEY,APP_SECRET,OAUTH_SIG_METHOD_HMACSHA1,OAUTH_AUTH_TYPE_AUTHORIZATION);
        $oauth->setToken(OAUTH_ACCESS_TOKEN,OAUTH_ACCESS_TOKEN_SECRET);
        $oauth->enableDebug();
        try 
        {
            if($this->method == "POST")
            {
                //POST REQUESTS are used for Preview/Place Order
                //$oauth->setAuthType(OAUTH_AUTH_TYPE_FORM);
                $oauth->fetch($this->url,$this->post_request,OAUTH_HTTP_METHOD_POST,array('Content-Type' => "application/$this->content_type"));
            }
            elseif($this->method == "DELETE")
            {
                //DELETE REQUESTS are used only delete alerts
                $oauth->fetch($this->url,'',OAUTH_HTTP_METHOD_DELETE);
            }
            elseif($this->method == "PUT")
            {
                //PUT REQUESTS are used to cancel or preview then update an order
                $oauth->fetch($this->url,$this->post_request,OAUTH_HTTP_METHOD_PUT,array('Content-Type' => "application/$this->content_type"));
            }
            else
            {
                //GET is used for everything else
                $oauth->fetch($this->url);
            }
            $response_as_object = new SimpleXMLElement($oauth->getLastResponse());
            return $response_as_object;             
        } 
        catch (Exception $E) 
        {
            echo "Exception caught!\n";
            echo "Response: ". $E->lastResponse . "\n";
            if(DEBUG_MODE)
            {
                print_r($E);
            }
        }
    }
}
?>
