<?php
exit;
require_once(dirname(__FILE__) . "/../config.php");
require_once(dirname(__FILE__) . "/../classes/oauthhttp.class.php");
require_once(dirname(__FILE__) . "/../classes/accounts.class.php");
$ac_obj = new Accounts();

$ac = $ac_obj->GetAccountList();
$account_id_key = (string) $ac->Accounts->Account->accountIdKey;

$client_order_id = "gbAaEdWPlVWI6201"; //Some unique random order id
$url = str_replace("accountkeyid",$account_id_key,ORDER_PREVIEW_URL);
$OAuthHTTPObj = new OAuthHTTP($url,"POST");
$OAuthHTTPObj->post_request = preview_request($client_order_id);

//exit;
$ord_preview = $OAuthHTTPObj->GetResponse();
print_r($ord_preview);
//exit;
$preview_id = $ord_preview->PreviewIds->previewId;

$url = str_replace("accountkeyid",$account_id_key,ORDER_PLACE_URL);
print "$url\n";
$OAuthHTTPObj = new OAuthHTTP($url,"POST");
$OAuthHTTPObj->post_request = place_request($client_order_id,$preview_id);
print $OAuthHTTPObj->post_request;
//exit;
$ord_place = $OAuthHTTPObj->GetResponse();
print_r($ord_place);

function preview_request($client_order_id)
{
    $post_request_temp = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
    <PreviewOrderRequest>
      <Order>
            <Instrument>
                  <Product>
                     <callPut>PUT</callPut>
                     <expiryDay>19</expiryDay>
                     <expiryMonth>02</expiryMonth>
                     <expiryYear>2021</expiryYear>
                     <securityType>OPTN</securityType>
                     <strikePrice>340</strikePrice>
                     <symbol>SPY</symbol>
                  </Product>
                  <orderAction>SELL_OPEN</orderAction>
                  <orderedQuantity>1</orderedQuantity>
                  <quantity>1</quantity>
            </Instrument>
            <Instrument>
                  <Product>
                     <callPut>PUT</callPut>
                     <expiryDay>19</expiryDay>
                     <expiryMonth>02</expiryMonth>
                     <expiryYear>2021</expiryYear>
                     <securityType>OPTN</securityType>
                     <strikePrice>330</strikePrice>
                     <symbol>SPY</symbol>
                  </Product>
                  <orderAction>BUY_OPEN</orderAction>
                  <orderedQuantity>1</orderedQuantity>
                  <quantity>1</quantity>
            </Instrument>
            <Instrument>
                  <Product>
                     <callPut>CALL</callPut>
                     <expiryDay>19</expiryDay>
                     <expiryMonth>02</expiryMonth>
                     <expiryYear>2021</expiryYear>
                     <securityType>OPTN</securityType>
                     <strikePrice>403</strikePrice>
                     <symbol>SPY</symbol>
                  </Product>
                  <orderAction>SELL_OPEN</orderAction>
                  <orderedQuantity>1</orderedQuantity>
                  <quantity>1</quantity>
            </Instrument>
            <Instrument>
                  <Product>
                     <callPut>CALL</callPut>
                     <expiryDay>19</expiryDay>
                     <expiryMonth>02</expiryMonth>
                     <expiryYear>2021</expiryYear>
                     <securityType>OPTN</securityType>
                     <strikePrice>413</strikePrice>
                     <symbol>SPY</symbol>
                  </Product>
                  <orderAction>BUY_OPEN</orderAction>
                  <orderedQuantity>1</orderedQuantity>
                  <quantity>1</quantity>
            </Instrument>
            <allOrNone>false</allOrNone>
            <limitPrice>5</limitPrice>
            <marketSession>REGULAR</marketSession>
            <orderTerm>GOOD_FOR_DAY</orderTerm>
            <priceType>NET_CREDIT</priceType>
            <stopPrice>0</stopPrice>
      </Order>
      <clientOrderId>$client_order_id</clientOrderId>
      <orderType>IRON_CONDOR</orderType>
    </PreviewOrderRequest>";
return $post_request_temp;
}

function place_request($client_order_id,$preview_id)
{
    $post_request_temp = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
    <PlaceOrderRequest>
      <Order>
            <Instrument>
                  <Product>
                     <callPut>PUT</callPut>
                     <expiryDay>19</expiryDay>
                     <expiryMonth>02</expiryMonth>
                     <expiryYear>2021</expiryYear>
                     <securityType>OPTN</securityType>
                     <strikePrice>340</strikePrice>
                     <symbol>SPY</symbol>
                  </Product>
                  <orderAction>SELL_OPEN</orderAction>
                  <orderedQuantity>1</orderedQuantity>
                  <quantity>1</quantity>
            </Instrument>
            <Instrument>
                  <Product>
                     <callPut>PUT</callPut>
                     <expiryDay>19</expiryDay>
                     <expiryMonth>02</expiryMonth>
                     <expiryYear>2021</expiryYear>
                     <securityType>OPTN</securityType>
                     <strikePrice>330</strikePrice>
                     <symbol>SPY</symbol>
                  </Product>
                  <orderAction>BUY_OPEN</orderAction>
                  <orderedQuantity>1</orderedQuantity>
                  <quantity>1</quantity>
            </Instrument>
            <Instrument>
                  <Product>
                     <callPut>CALL</callPut>
                     <expiryDay>19</expiryDay>
                     <expiryMonth>02</expiryMonth>
                     <expiryYear>2021</expiryYear>
                     <securityType>OPTN</securityType>
                     <strikePrice>403</strikePrice>
                     <symbol>SPY</symbol>
                  </Product>
                  <orderAction>SELL_OPEN</orderAction>
                  <orderedQuantity>1</orderedQuantity>
                  <quantity>1</quantity>
            </Instrument>
            <Instrument>
                  <Product>
                     <callPut>CALL</callPut>
                     <expiryDay>19</expiryDay>
                     <expiryMonth>02</expiryMonth>
                     <expiryYear>2021</expiryYear>
                     <securityType>OPTN</securityType>
                     <strikePrice>413</strikePrice>
                     <symbol>SPY</symbol>
                  </Product>
                  <orderAction>BUY_OPEN</orderAction>
                  <orderedQuantity>1</orderedQuantity>
                  <quantity>1</quantity>
            </Instrument>
            <allOrNone>false</allOrNone>
            <limitPrice>5</limitPrice>
            <marketSession>REGULAR</marketSession>
            <orderTerm>GOOD_FOR_DAY</orderTerm>
            <priceType>NET_CREDIT</priceType>
            <stopPrice>0</stopPrice>
      </Order>
      <PreviewIds>
            <previewId>$preview_id</previewId>
      </PreviewIds>
      <clientOrderId>$client_order_id</clientOrderId>
      <orderType>IRON_CONDOR</orderType>
    </PlaceOrderRequest>";
return $post_request_temp;
}
?>
