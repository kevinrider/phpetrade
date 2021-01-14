<?php
exit;
require_once(dirname(__FILE__) . "/../config.php");
require_once(dirname(__FILE__) . "/../classes/oauthhttp.class.php");
require_once(dirname(__FILE__) . "/../classes/accounts.class.php");
require_once(dirname(__FILE__) . "/../classes/orderticket.class.php");
$ac_obj = new Accounts();

$ac = $ac_obj->GetAccountList();
$account_id_key = (string) $ac->Accounts->Account->accountIdKey;


//Build Up Single Leg Option Order Ticket
$order_ticket = new OrderTicket(dirname(__FILE__) . "/../tickets");
$order_ticket->LoadOptionOrderTicket("single");

$client_order_id = "gbAaEdWPlVWI45636";  //Some unique random order id
//Setup the Long 2/19/2021 SPY 380 PUT 
$order_ticket->Parse("CALLPUT", "PUT");
$order_ticket->Parse("EXPDAY", "19");
$order_ticket->Parse("EXPMONTH", "02");
$order_ticket->Parse("EXPYEAR", "2021");
$order_ticket->Parse("STRIKE", "380");
$order_ticket->Parse("SYMBOL", "SPY");
$order_ticket->Parse("QUANTITY", "1");
$order_ticket->Parse("ALLORNONE", "false");
$order_ticket->Parse("LIMITPRICE", "0.5");
$order_ticket->Parse("ORDERACTION", "BUY_OPEN");
$order_ticket->Parse("CLIENTORDERID", "$client_order_id");

//Clone the order before parsing final data.
$order_ticket->Clone();
$order_ticket->Parse("PLACEPREVIEW", "Preview");
$order_ticket->Parse("PREVIEWID", "");

//echo $order_ticket->ticket_data;
//echo $order_ticket->ticket_clone;
//exit;

$url = str_replace("accountkeyid",$account_id_key,ORDER_PREVIEW_URL);
$OAuthHTTPObj = new OAuthHTTP($url,"POST");
$OAuthHTTPObj->post_request = $order_ticket->ticket_data;

$ord_preview = $OAuthHTTPObj->GetResponse();
print_r($ord_preview);
//exit;
$preview_id = "";
$preview_id = $ord_preview->PreviewIds->previewId;

if($preview_id != "")
{
    //Preview was accepted
    $order_ticket->ticket_data = $order_ticket->ticket_clone;
    $order_ticket->Parse("PLACEPREVIEW", "Place");
    $order_ticket->Parse("PREVIEWID", "\n<PreviewIds>\n<previewId>$preview_id</previewId>\n</PreviewIds>");
//    echo $order_ticket->ticket_data;
//    exit;
    $url = str_replace("accountkeyid",$account_id_key,ORDER_PLACE_URL);
    $OAuthHTTPObj->url = $url;
    $OAuthHTTPObj->post_request = $order_ticket->ticket_data;
    $ord_place = $OAuthHTTPObj->GetResponse();
    print_r($ord_place);
}

//Build Up Two Leg Option Order Ticket
$order_ticket = new OrderTicket(dirname(__FILE__) . "/../tickets");
$order_ticket->LoadOptionOrderTicket("double");

$client_order_id = "gbAaEdWPlVWIi";  //Some unique random order id
//Setup the Short 340/330 Put Credit Spread 2/19/2021 SPY 
$order_ticket->Parse("ONECALLPUT", "PUT");
$order_ticket->Parse("ONEEXPDAY", "19");
$order_ticket->Parse("ONEEXPMONTH", "02");
$order_ticket->Parse("ONEEXPYEAR", "2021");
$order_ticket->Parse("ONESTRIKE", "340");
$order_ticket->Parse("SYMBOL", "SPY");
$order_ticket->Parse("ONEORDERACTION", "SELL_OPEN");
$order_ticket->Parse("ONEQUANTITY", "1");

$order_ticket->Parse("TWOCALLPUT", "PUT");
$order_ticket->Parse("TWOEXPDAY", "19");
$order_ticket->Parse("TWOEXPMONTH", "02");
$order_ticket->Parse("TWOEXPYEAR", "2021");
$order_ticket->Parse("TWOSTRIKE", "330");
$order_ticket->Parse("SYMBOL", "SPY");
$order_ticket->Parse("TWOORDERACTION", "BUY_OPEN");
$order_ticket->Parse("TWOQUANTITY", "1");

$order_ticket->Parse("ALLORNONE", "false");
$order_ticket->Parse("LIMITPRICE", "5");
$order_ticket->Parse("CLIENTORDERID", "$client_order_id");
$order_ticket->Parse("PRICETYPE", "NET_CREDIT");

//Clone the order before parsing final data.
$order_ticket->Clone();
$order_ticket->Parse("PLACEPREVIEW", "Preview");
$order_ticket->Parse("PREVIEWID", "");

echo $order_ticket->ticket_data;
echo $order_ticket->ticket_clone;

$url = str_replace("accountkeyid",$account_id_key,ORDER_PREVIEW_URL);
$OAuthHTTPObj = new OAuthHTTP($url,"POST");
$OAuthHTTPObj->post_request = $order_ticket->ticket_data;

$ord_preview = $OAuthHTTPObj->GetResponse();
print_r($ord_preview);
//exit;

$preview_id = "";
$preview_id = $ord_preview->PreviewIds->previewId;

if($preview_id != "")
{
    //Preview was accepted
    $order_ticket->ticket_data = $order_ticket->ticket_clone;
    $order_ticket->Parse("PLACEPREVIEW", "Place");
    $order_ticket->Parse("PREVIEWID", "\n<PreviewIds>\n<previewId>$preview_id</previewId>\n</PreviewIds>");
//    echo $order_ticket->ticket_data;
//    exit;
    $url = str_replace("accountkeyid",$account_id_key,ORDER_PLACE_URL);
    $OAuthHTTPObj->url = $url;
    $OAuthHTTPObj->post_request = $order_ticket->ticket_data;
    $ord_place = $OAuthHTTPObj->GetResponse();
    print_r($ord_place);
}

?>
