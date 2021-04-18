<?php
//WARNING: THIS EXAMPLE SENDS LIVES ORDERS TO YOUR E*TRADE ACCOUNT THROUGH THE API!
//WARNING: DO NOT EXECUTE THIS EXAMPLE AGAINST A PRODUCTION ACCOUNT DURING MARKET HOURS!
exit;
require_once __DIR__ . '/../vendor/autoload.php';
use phpetrade\Accounts;
use phpetrade\OAuthHTTP;
use phpetrade\Market;
use phpetrade\Alerts;
use phpetrade\Order;

$ac_obj = new Accounts();

//Account List End Point
//Must get the random accountIdKey before calling any of the other Account end points.
//accountIdKey is not any of the other oauth related tokens or secrets.
$ac     = $ac_obj->GetAccountList();
//print_r($ac);

$account_id_key = (string) $ac->Accounts->Account->accountIdKey;

//Account Balance End Point
$account_balance_para["instType"] = "BROKERAGE";
$account_balance_para["realTimeNAV"] = "true";
$ac_balance = $ac_obj->GetAccountBalance($account_id_key,$account_balance_para);
//print_r($ac_balance);

//Account Transactions End Point
$account_tran_para["Accept"] = "application/xml";
$account_tran_para["sortOrder"] = "desc";
$account_tran_para["count"] = "25";
$ac_tran = $ac_obj->GetAccountTransactions($account_id_key,$account_tran_para);
//print_r($ac_tran);

//Account Transaction Details End Point
//Assumes that the account has a last transaction available.
//PHP can't access an object that has an integer as a key.  Simple work around...
$ii = 0;
$transaction_id = (int) $ac_tran->Transaction->$ii->transactionId;
$account_tran_id_para["Accept"] = "application/xml";
$account_tran_id_para["storeId"] = "0";
$ac_tran_id = $ac_obj->GetAccountTransactionDetails($account_id_key,$transaction_id,$account_tran_id_para);
//print_r($ac_tran_id);

//Account Portfolio End Point
$account_port_para["count"] = "25";
$account_port_para["view"] = "COMPLETE";
$ac_port = $ac_obj->GetAccountPortfolio($account_id_key,$account_port_para);
//print_r($ac_port);

//Market End Point
$mk_obj = new Market();

//$market_quotes["symbols"] = "MRK"; //Format Single Ticker Quote
//Format Multi Ticker Quote
$market_quotes_para["symbols"][] = "MRK";
$market_quotes_para["symbols"][] = "PFE";
$market_quotes_para["symbols"][] = "JNJ";
$market_quotes_para["symbols"][] = "AZN";
$market_quotes_para["detailFlag"] = "ALL";
$market_quotes_para["overrideSymbolCount"] = "true";
$mk_quotes = $mk_obj->MarketGetQuotes($market_quotes_para);
//print_r($mk_quotes);

//Format Multi Option Quote
//Option Quotes have the form TICKER:YEAR:MONTH:DAY:OPTIONTYPE:STRIKE
$market_quoteso_para["symbols"][] = "NFLX:2023:1:20:Put:515";
$market_quoteso_para["symbols"][] = "SLB:2023:1:20:Call:22.5";
$market_quoteso_para["symbols"][] = "KSU:2023:1:20:Put:220";
$market_quoteso_para["symbols"][] = "TEAM:2023:1:20:Put:240";
$market_quoteso_para["detailFlag"] = "ALL";
$market_quoteso_para["overrideSymbolCount"] = "true";
$mk_quoteso = $mk_obj->MarketGetQuotes($market_quoteso_para);
//print_r($mk_quoteso);

//Market Look Up
$market_lookup_para["search"] = "Apple";
$mk_lookup = $mk_obj->MarketLookUp($market_lookup_para);
//print_r($mk_lookup);

//Market Get Option Chain
$market_optionchain_para["symbol"] = "MTN";
$market_optionchain_para["chainType"] = "CALLPUT";
$market_optionchain_para["strikePriceNear"] = "270";
$mk_optionchain = $mk_obj->MarketGetOptionChain($market_optionchain_para);
//print_r($mk_optionchain);

//Market Get Option Chain Expiration Dates
//Gets a list of future option expiration dates and wether they are Monthly or Weekly expirations.
$market_optionchainexp_para["symbol"] = "MTN";
$mk_optionchainexp = $mk_obj->MarketGetOptionExp($market_optionchainexp_para);
//print_r($mk_optionchainexp);

//Alerts End Point
$al_obj = new Alerts();

//List Alerts
$alerts_list_para["count"] = "5";
$al_list = $al_obj->AlertsList($alerts_list_para);
print_r($al_list);

//List Alert Details
//Assumes there is at least one alert available
$iii = 0;
$alert_id = (int) $al_list->Alert->$iii->id;
$alerts_details_para["id"] = "$alert_id";
//$alerts_details_para["htmlTags"] = "true";
$al_details = $al_obj->AlertsListDetails($alerts_details_para);

//Delete Alert
//Delete the two most recent alerts... assuming they are available
//Uncomment in order to delete the alerts.
/*
$alerts_delete_para["id"][] = "$alert_id";
$iii = 1;
$alert_id = (int) $al_list->Alert->$iii->id;
$alerts_delete_para["id"][] = "$alert_id";
$al_details = $al_obj->AlertsDelete($alerts_delete_para);
print_r($al_details);
 */

//Order End point
$ord_obj = new Order();

//List Orders
$order_list_para["count"] = "2";
$order_list_para["marketSession"] = "REGULAR";
$order_list_para["status"] = "OPEN";
$ord_list = $ord_obj->ListOrders($account_id_key,$order_list_para);
print_r($ord_list);

//Preview Order
//EQ
$client_order_id = 'test' . rand_order_id(); //Some unique random order id
$order_preview_para["PreviewOrderRequest"]["orderType"] = "EQ"; //EQ, OPTN, SPREADS, BUT_WRITES,BUTTERFLY,IRON_BUTTERFLY,CONDOR,IRON_CONDOR,MF,MMF
$order_preview_para["PreviewOrderRequest"]["clientOrderId"] = "$client_order_id";
$order_preview_para["PreviewOrderRequest"]["Order"]["allOrNone"] = "true";
$order_preview_para["PreviewOrderRequest"]["Order"]["priceType"] = "LIMIT";
$order_preview_para["PreviewOrderRequest"]["Order"]["orderTerm"] = "GOOD_FOR_DAY";
$order_preview_para["PreviewOrderRequest"]["Order"]["marketSession"] = "REGULAR";
$order_preview_para["PreviewOrderRequest"]["Order"]["limitPrice"] = "4.25";
$order_preview_para["PreviewOrderRequest"]["Order"]["Instrument"]["Product"]["securityType"] = "EQ";
$order_preview_para["PreviewOrderRequest"]["Order"]["Instrument"]["Product"]["symbol"] = "AMZN";
$order_preview_para["PreviewOrderRequest"]["Order"]["Instrument"]["orderAction"] = "BUY";
$order_preview_para["PreviewOrderRequest"]["Order"]["Instrument"]["quantityType"] = "QUANTITY";
$order_preview_para["PreviewOrderRequest"]["Order"]["Instrument"]["quantity"] = "1";

//print_r($order_preview_para);
//echo json_encode($order_preview_para);
//exit;

$ord_preview = $ord_obj->PreviewOrder($account_id_key,$order_preview_para);
//print_r($ord_preview);
//exit;


//Get the previewId for this order
$preview_id = $ord_preview->PreviewIds->previewId;

//Place Order
//EQ
$order_place_para["PlaceOrderRequest"]["orderType"] = "EQ"; 
$order_place_para["PlaceOrderRequest"]["clientOrderId"] = "$client_order_id";
$order_place_para["PlaceOrderRequest"]["PreviewIds"]["previewId"] = "$preview_id";
$order_place_para["PlaceOrderRequest"]["Order"]["allOrNone"] = "true";
$order_place_para["PlaceOrderRequest"]["Order"]["priceType"] = "LIMIT";
$order_place_para["PlaceOrderRequest"]["Order"]["orderTerm"] = "GOOD_FOR_DAY";
$order_place_para["PlaceOrderRequest"]["Order"]["marketSession"] = "REGULAR";
$order_place_para["PlaceOrderRequest"]["Order"]["limitPrice"] = "4.00";
$order_place_para["PlaceOrderRequest"]["Order"]["Instrument"]["Product"]["securityType"] = "EQ";
$order_place_para["PlaceOrderRequest"]["Order"]["Instrument"]["Product"]["symbol"] = "AMZN";
$order_place_para["PlaceOrderRequest"]["Order"]["Instrument"]["orderAction"] = "BUY";
$order_place_para["PlaceOrderRequest"]["Order"]["Instrument"]["quantityType"] = "QUANTITY";
$order_place_para["PlaceOrderRequest"]["Order"]["Instrument"]["quantity"] = "1";

//echo $ord_obj->encodeXML($order_place_para,'',0);
$ord_place = $ord_obj->PlaceOrder($account_id_key,$order_place_para);
print_r($ord_place);
//exit;


//Cancel Order
$iiii = 0;
//Get the most recent open order from the order list (requested above);
$ord_list = $ord_obj->ListOrders($account_id_key,$order_list_para);
$cancel_order_id = $ord_list->Order->$iiii->orderId;
/*
$order_cancel_para["CancelOrderRequest"]["orderId"] = "$cancel_order_id";
$ord_cancel = $ord_obj->CancelOrder($account_id_key,$order_cancel_para);
print_r($ord_cancel);
exit;
 * 
 */

//Change Preview Order
//EQ
$client_order_id = 'test' . rand_order_id(); //Some unique random order id
$order_id = $ord_list->Order->$iiii->orderId;
$order_change_preview_para["PreviewOrderRequest"]["orderType"] = "EQ"; 
$order_change_preview_para["PreviewOrderRequest"]["clientOrderId"] = "$client_order_id";
$order_change_preview_para["PreviewOrderRequest"]["Order"]["allOrNone"] = "true";
$order_change_preview_para["PreviewOrderRequest"]["Order"]["priceType"] = "LIMIT";
$order_change_preview_para["PreviewOrderRequest"]["Order"]["orderTerm"] = "GOOD_FOR_DAY";
$order_change_preview_para["PreviewOrderRequest"]["Order"]["marketSession"] = "REGULAR";
$order_change_preview_para["PreviewOrderRequest"]["Order"]["limitPrice"] = "3.25";
$order_change_preview_para["PreviewOrderRequest"]["Order"]["Instrument"]["Product"]["securityType"] = "EQ";
$order_change_preview_para["PreviewOrderRequest"]["Order"]["Instrument"]["Product"]["symbol"] = "AMZN";
$order_change_preview_para["PreviewOrderRequest"]["Order"]["Instrument"]["orderAction"] = "BUY";
$order_change_preview_para["PreviewOrderRequest"]["Order"]["Instrument"]["quantityType"] = "QUANTITY";
$order_change_preview_para["PreviewOrderRequest"]["Order"]["Instrument"]["quantity"] = "1";
//echo $ord_obj->encodeXML($order_change_preview_para,'',0);
$ord_change_preview = $ord_obj->ChangePreviewOrder($account_id_key,$order_id,$order_change_preview_para);
print_r($ord_change_preview);


//Place Changed Preview Order
//EQ
$order_id = $ord_list->Order->$iiii->orderId;
$preview_id = $ord_change_preview->PreviewIds->previewId;
$order_place_change_para["PlaceOrderRequest"]["orderType"] = "EQ"; 
$order_place_change_para["PlaceOrderRequest"]["clientOrderId"] = "$client_order_id";
$order_place_change_para["PlaceOrderRequest"]["PreviewIds"]["previewId"] = "$preview_id";
$order_place_change_para["PlaceOrderRequest"]["Order"]["allOrNone"] = "true";
$order_place_change_para["PlaceOrderRequest"]["Order"]["priceType"] = "LIMIT";
$order_place_change_para["PlaceOrderRequest"]["Order"]["orderTerm"] = "GOOD_FOR_DAY";
$order_place_change_para["PlaceOrderRequest"]["Order"]["marketSession"] = "REGULAR";
$order_place_change_para["PlaceOrderRequest"]["Order"]["limitPrice"] = "3.25";
$order_place_change_para["PlaceOrderRequest"]["Order"]["Instrument"]["Product"]["securityType"] = "EQ";
$order_place_change_para["PlaceOrderRequest"]["Order"]["Instrument"]["Product"]["symbol"] = "AMZN";
$order_place_change_para["PlaceOrderRequest"]["Order"]["Instrument"]["orderAction"] = "BUY";
$order_place_change_para["PlaceOrderRequest"]["Order"]["Instrument"]["quantityType"] = "QUANTITY";
$order_place_change_para["PlaceOrderRequest"]["Order"]["Instrument"]["quantity"] = "1";
echo $ord_obj->encodeXML($order_place_change_para,'',0);
$ord_place_preview = $ord_obj->PlaceChangeOrder($account_id_key,$order_id,$order_place_change_para);
print_r($ord_place_preview);

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
