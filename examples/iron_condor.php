<?php
//WARNING: THIS EXAMPLE SENDS A LIVE ORDER TO YOUR E*TRADE ACCOUNT THROUGH THE API!
//WARNING: DO NOT EXECUTE THIS EXAMPLE AGAINST A PRODUCTION ACCOUNT DURING MARKET HOURS!
exit;
require_once __DIR__ . '/../vendor/autoload.php';
use phpetrade\Accounts;
use phpetrade\OAuthHTTP;

$ac_obj = new Accounts();

$ac = $ac_obj->GetAccountList();
$account_id_key = (string) $ac->Accounts->Account->accountIdKey;

$client_order_id = 'test' . rand_order_id(); //Some unique random order id
$url = str_replace("accountkeyid",$account_id_key,$ac_obj->config->order_preview_url);
$OAuthHTTPObj = new OAuthHTTP($url,"POST");
$OAuthHTTPObj->post_request = preview_request($client_order_id);

//exit;
$ord_preview = $OAuthHTTPObj->GetResponse();
print_r($ord_preview);
//exit;
$preview_id = $ord_preview->PreviewIds->previewId;

$url = str_replace("accountkeyid",$account_id_key,$ac_obj->config->order_place_url);
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
                     <expiryDay>17</expiryDay>
                     <expiryMonth>12</expiryMonth>
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
                     <expiryDay>17</expiryDay>
                     <expiryMonth>12</expiryMonth>
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
                     <expiryDay>17</expiryDay>
                     <expiryMonth>12</expiryMonth>
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
                     <expiryDay>17</expiryDay>
                     <expiryMonth>12</expiryMonth>
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
            <limitPrice>9</limitPrice>
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
                     <expiryDay>17</expiryDay>
                     <expiryMonth>12</expiryMonth>
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
                     <expiryDay>17</expiryDay>
                     <expiryMonth>12</expiryMonth>
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
                     <expiryDay>17</expiryDay>
                     <expiryMonth>12</expiryMonth>
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
                     <expiryDay>17</expiryDay>
                     <expiryMonth>12</expiryMonth>
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
            <limitPrice>9</limitPrice>
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

function rand_order_id($limit = 16) 
{
    $input = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $input_length = strlen($input);
    $random_string = '';
    for($i = 0; $i < $limit; $i++) {
        $random_character = $input[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }
    return $random_string;
}
?>
