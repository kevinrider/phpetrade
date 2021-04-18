<?php
namespace phpetrade;

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
        $oauth = new \OAuth(APP_KEY,APP_SECRET,OAUTH_SIG_METHOD_HMACSHA1,OAUTH_AUTH_TYPE_AUTHORIZATION);
        $oauth->setToken(OAUTH_ACCESS_TOKEN,OAUTH_ACCESS_TOKEN_SECRET);
        $oauth->enableDebug();
        try 
        {
            if($this->method == "POST")
            {
                //POST requests are used for Preview/Place Order
                //$oauth->setAuthType(OAUTH_AUTH_TYPE_FORM);
                $oauth->fetch($this->url,$this->post_request,OAUTH_HTTP_METHOD_POST,array('Content-Type' => "application/$this->content_type"));
            }
            elseif($this->method == "DELETE")
            {
                //DELETE requests only delete alerts
                $oauth->fetch($this->url,'',OAUTH_HTTP_METHOD_DELETE);
            }
            elseif($this->method == "PUT")
            {
                //PUT requests are used to cancel or preview then update an order
                $oauth->fetch($this->url,$this->post_request,OAUTH_HTTP_METHOD_PUT,array('Content-Type' => "application/$this->content_type"));
            }
            else
            {
                //GET is used for everything else
                $oauth->fetch($this->url);
            }

            $oauth_response_info = $oauth->getLastResponseInfo();
            $oauth_response_content_type = $oauth_response_info['content_type'];
            if($oauth->getLastResponse() == "")
            {
                //Response is empty
                $response_as_object = false;
            }
            elseif(preg_match('/^application\/xml/i', $oauth_response_content_type))
            {
                //Response returned xml
                $response_as_object = new \SimpleXMLElement($oauth->getLastResponse());
            }
            else 
            {
                //Response returned something else, most likely a string (Renew/Revoke Token)
                $response_as_object = $oauth->getLastResponse();
            }
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
