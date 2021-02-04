<?php
exit;
require_once __DIR__ . '/../vendor/autoload.php';
use phpetrade\Accounts;
use phpetrade\OAuthHTTP;
use phpetrade\OrderTicket;

$ac_obj = new Accounts();

$ac = $ac_obj->GetAccountList();
$account_id_key = (string) $ac->Accounts->Account->accountIdKey;


//Build Up Single Leg Option Order Ticket
$order_ticket = new OrderTicket(dirname(__FILE__) . "/../src/tickets");
$order_ticket->LoadOptionOrderTicket("single");

$client_order_id = "gbAaEdWPlVWI301";  //Some unique random order id
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
    //echo $order_ticket->ticket_data;
    //exit;
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
$order_ticket = new OrderTicket(dirname(__FILE__) . "/../src/tickets");
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

//Build Up Three Leg Option Order Ticket
$order_ticket = new OrderTicket(dirname(__FILE__) . "/../src/tickets");
$order_ticket->LoadOptionOrderTicket("triple");

$client_order_id = "gbAaEdWPl191";  //Some unique random order id
//Long Butterfly 366/376/386 Calls 2/19/2021 SPY
//Note: orderType=BUTTERFLY or orderType=SPREADS works.
$order_ticket->Parse("ONECALLPUT", "CALL");
$order_ticket->Parse("ONEEXPDAY", "19");
$order_ticket->Parse("ONEEXPMONTH", "02");
$order_ticket->Parse("ONEEXPYEAR", "2021");
$order_ticket->Parse("ONESTRIKE", "366");
$order_ticket->Parse("SYMBOL", "SPY");
$order_ticket->Parse("ONEORDERACTION", "BUY_OPEN");
$order_ticket->Parse("ONEQUANTITY", "1");

$order_ticket->Parse("TWOCALLPUT", "CALL");
$order_ticket->Parse("TWOEXPDAY", "19");
$order_ticket->Parse("TWOEXPMONTH", "02");
$order_ticket->Parse("TWOEXPYEAR", "2021");
$order_ticket->Parse("TWOSTRIKE", "376");
$order_ticket->Parse("SYMBOL", "SPY");
$order_ticket->Parse("TWOORDERACTION", "SELL_OPEN");
$order_ticket->Parse("TWOQUANTITY", "2");

$order_ticket->Parse("THREECALLPUT", "CALL");
$order_ticket->Parse("THREEEXPDAY", "19");
$order_ticket->Parse("THREEEXPMONTH", "02");
$order_ticket->Parse("THREEEXPYEAR", "2021");
$order_ticket->Parse("THREESTRIKE", "386");
$order_ticket->Parse("SYMBOL", "SPY");
$order_ticket->Parse("THREEORDERACTION", "BUY_OPEN");
$order_ticket->Parse("THREEQUANTITY", "1");

$order_ticket->Parse("ALLORNONE", "false");
$order_ticket->Parse("LIMITPRICE", "0.50");
$order_ticket->Parse("CLIENTORDERID", "$client_order_id");
$order_ticket->Parse("PRICETYPE", "NET_DEBIT");
$order_ticket->Parse("ORDERTYPE", "BUTTERFLY");

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

//Build Up Four Leg Option Order Ticket
$order_ticket = new OrderTicket(dirname(__FILE__) . "/../src/tickets");
$order_ticket->LoadOptionOrderTicket("quad");

$client_order_id = "gbAaEdWPl3";  //Some unique random order id
//IRON CONDOR 330/340 403/413 2/19/2021 SPY
$order_ticket->Parse("ONECALLPUT", "PUT");
$order_ticket->Parse("ONEEXPDAY", "19");
$order_ticket->Parse("ONEEXPMONTH", "02");
$order_ticket->Parse("ONEEXPYEAR", "2021");
$order_ticket->Parse("ONESTRIKE", "330");
$order_ticket->Parse("SYMBOL", "SPY");
$order_ticket->Parse("ONEORDERACTION", "BUY_OPEN");
$order_ticket->Parse("ONEQUANTITY", "1");

$order_ticket->Parse("TWOCALLPUT", "PUT");
$order_ticket->Parse("TWOEXPDAY", "19");
$order_ticket->Parse("TWOEXPMONTH", "02");
$order_ticket->Parse("TWOEXPYEAR", "2021");
$order_ticket->Parse("TWOSTRIKE", "340");
$order_ticket->Parse("SYMBOL", "SPY");
$order_ticket->Parse("TWOORDERACTION", "SELL_OPEN");
$order_ticket->Parse("TWOQUANTITY", "1");

$order_ticket->Parse("THREECALLPUT", "CALL");
$order_ticket->Parse("THREEEXPDAY", "19");
$order_ticket->Parse("THREEEXPMONTH", "02");
$order_ticket->Parse("THREEEXPYEAR", "2021");
$order_ticket->Parse("THREESTRIKE", "403");
$order_ticket->Parse("SYMBOL", "SPY");
$order_ticket->Parse("THREEORDERACTION", "SELL_OPEN");
$order_ticket->Parse("THREEQUANTITY", "1");

$order_ticket->Parse("FOURCALLPUT", "CALL");
$order_ticket->Parse("FOUREXPDAY", "19");
$order_ticket->Parse("FOUREXPMONTH", "02");
$order_ticket->Parse("FOUREXPYEAR", "2021");
$order_ticket->Parse("FOURSTRIKE", "413");
$order_ticket->Parse("SYMBOL", "SPY");
$order_ticket->Parse("FOURORDERACTION", "BUY_OPEN");
$order_ticket->Parse("FOURQUANTITY", "1");

$order_ticket->Parse("ALLORNONE", "false");
$order_ticket->Parse("LIMITPRICE", "5");
$order_ticket->Parse("CLIENTORDERID", "$client_order_id");
$order_ticket->Parse("PRICETYPE", "NET_CREDIT");
$order_ticket->Parse("ORDERTYPE", "IRON_CONDOR");

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

//Build Up Another Four Leg Option Order Ticket
$order_ticket = new OrderTicket(dirname(__FILE__) . "/../src/tickets");
$order_ticket->LoadOptionOrderTicket("quad");

$client_order_id = "gbAaEdWPl5";  //Some unique random order id
//IRON BUTTERFLY 330/370 370/410 2/19/2021 SPY
//NOTE: Order is accepted whether orderType=IRON_BUTTERFLY or orderType=IRON_CONDOR
$order_ticket->Parse("ONECALLPUT", "PUT");
$order_ticket->Parse("ONEEXPDAY", "19");
$order_ticket->Parse("ONEEXPMONTH", "02");
$order_ticket->Parse("ONEEXPYEAR", "2021");
$order_ticket->Parse("ONESTRIKE", "330");
$order_ticket->Parse("SYMBOL", "SPY");
$order_ticket->Parse("ONEORDERACTION", "BUY_OPEN");
$order_ticket->Parse("ONEQUANTITY", "1");

$order_ticket->Parse("TWOCALLPUT", "PUT");
$order_ticket->Parse("TWOEXPDAY", "19");
$order_ticket->Parse("TWOEXPMONTH", "02");
$order_ticket->Parse("TWOEXPYEAR", "2021");
$order_ticket->Parse("TWOSTRIKE", "370");
$order_ticket->Parse("SYMBOL", "SPY");
$order_ticket->Parse("TWOORDERACTION", "SELL_OPEN");
$order_ticket->Parse("TWOQUANTITY", "1");

$order_ticket->Parse("THREECALLPUT", "CALL");
$order_ticket->Parse("THREEEXPDAY", "19");
$order_ticket->Parse("THREEEXPMONTH", "02");
$order_ticket->Parse("THREEEXPYEAR", "2021");
$order_ticket->Parse("THREESTRIKE", "370");
$order_ticket->Parse("SYMBOL", "SPY");
$order_ticket->Parse("THREEORDERACTION", "SELL_OPEN");
$order_ticket->Parse("THREEQUANTITY", "1");

$order_ticket->Parse("FOURCALLPUT", "CALL");
$order_ticket->Parse("FOUREXPDAY", "19");
$order_ticket->Parse("FOUREXPMONTH", "02");
$order_ticket->Parse("FOUREXPYEAR", "2021");
$order_ticket->Parse("FOURSTRIKE", "410");
$order_ticket->Parse("SYMBOL", "SPY");
$order_ticket->Parse("FOURORDERACTION", "BUY_OPEN");
$order_ticket->Parse("FOURQUANTITY", "1");

$order_ticket->Parse("ALLORNONE", "false");
$order_ticket->Parse("LIMITPRICE", "25");
$order_ticket->Parse("CLIENTORDERID", "$client_order_id");
$order_ticket->Parse("PRICETYPE", "NET_CREDIT");
$order_ticket->Parse("ORDERTYPE", "IRON_BUTTERFLY");

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

//Build Up Buy Write (Stock + Single Leg Option) Order Ticket
$order_ticket = new OrderTicket(dirname(__FILE__) . "/../src/tickets");
$order_ticket->LoadOptionOrderTicket("buywrite");

$client_order_id = "gbAaEdWPlVWI7";  //Some unique random order id
//Setup the GE Covered Call + Buying GE Stock
$order_ticket->Parse("CALLPUT", "CALL");
$order_ticket->Parse("EXPDAY", "19");
$order_ticket->Parse("EXPMONTH", "02");
$order_ticket->Parse("EXPYEAR", "2021");
$order_ticket->Parse("STRIKE", "11");
$order_ticket->Parse("SYMBOL", "GE");
$order_ticket->Parse("QUANTITY", "1");
$order_ticket->Parse("ORDERACTION", "SELL_OPEN");

$order_ticket->Parse("EQORDERACTION", "BUY");
$order_ticket->Parse("EQQUANTITY", "100");

$order_ticket->Parse("ALLORNONE", "false");
$order_ticket->Parse("LIMITPRICE", "5");
$order_ticket->Parse("ORDERTYPE", "BUY_WRITES");
$order_ticket->Parse("PRICETYPE", "NET_DEBIT");
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

?>
