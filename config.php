<?php
/**
 * ETrade API v1 config.php
 *
 */

//Set Tokens
require_once("tokens.php");

//Query production or sandbox environments?
$str = 'sandbox' ;// options : prod, sandbox
switch($str)
{
    case 'prod' :
        //Fill in APP_KEY and APP_SECRET with the issued production keys.
        defineConst('APP_KEY',          '');
        defineConst('APP_SECRET',       '');   
        defineConst('ETRADE_SERVER',    'https://api.etrade.com');
        break;
    case 'sandbox' : 	
        //Fill in the APP_KEY and APP_SECRET with the issued sandbox keys.
        defineConst('APP_KEY',          '');
        defineConst('APP_SECRET',       '');
        defineConst('ETRADE_SERVER',    'https://apisb.etrade.com');
        break;
    default : 
        echo "Please set this as a Production or Sandbox environment in config.php file and try again.\n";
        exit;
}


//App wide Constats
defineConst('ETRADE_OAUTH_SERVER', 'https://api.etrade.com');
defineConst('AUTHORIZE_URL', 'https://us.etrade.com/e/t/etws/authorize');

defineConst('DEBUG_MODE',0);  //1: Dumps OAUTH/HTTP communication log on error.  0: No extended error reporting

//Authorization Endpoints
defineConst('REQUEST_TOKEN_URL', ETRADE_OAUTH_SERVER . '/oauth/request_token');
defineConst('ACCESS_TOKEN_URL', ETRADE_OAUTH_SERVER . '/oauth/access_token');
defineConst('RENEW_TOKEN_URL', ETRADE_OAUTH_SERVER . '/oauth/renew_access_token');
defineConst('REVOKE_TOKEN_URL', ETRADE_OAUTH_SERVER . '/oauth/revoke_access_token');

//Account Endpoints
defineConst('URL_ACCOUNTLIST', ETRADE_SERVER . '/v1/accounts/list');
defineConst('URL_ACCOUNTBALANCE', ETRADE_SERVER . '/v1/accounts/accountkeyid/balance');
defineConst('URL_ACCOUNTTRANSACTIONS', ETRADE_SERVER . '/v1/accounts/accountkeyid/transactions');
defineConst('URL_ACCOUNTTRANSACTIONSDETAILS', ETRADE_SERVER . '/v1/accounts/accountkeyid/transactions/transid');
defineConst('URL_ACCOUNTPORTFOLIO', ETRADE_SERVER . '/v1/accounts/accountkeyid/portfolio');

//Market Endpoints
defineConst('URL_GETQUOTE', ETRADE_SERVER . '/v1/market/quote');
defineConst('URL_MARKETLOOKUP', ETRADE_SERVER . '/v1/market/lookup');
defineConst('URL_OPTIONCHAINS',	ETRADE_SERVER . '/v1/market/optionchains');
defineConst('URL_EXPIRYDATES', ETRADE_SERVER . '/v1/market/optionexpiredate');

//Alerts Endpoints
defineConst('LIST_ALERTS_URL', ETRADE_SERVER . '/v1/user/alerts');
defineConst('ALERT_DETAILS_URL', ETRADE_SERVER . '/v1/user/alerts');
defineConst('DELETE_ALERT_URL', ETRADE_SERVER . '/v1/user/alerts');

//Order Endpoints
defineConst('ORDER_LIST_URL', ETRADE_SERVER . '/v1/accounts/accountkeyid/orders');
defineConst('ORDER_PREVIEW_URL', ETRADE_SERVER . '/v1/accounts/accountkeyid/orders/preview');
defineConst('ORDER_PLACE_URL', ETRADE_SERVER . '/v1/accounts/accountkeyid/orders/place');
defineConst('ORDER_CANCEL_URL', ETRADE_SERVER . '/v1/accounts/accountkeyid/orders/cancel');
defineConst('ORDER_CHANGE_PREVIEW_URL', ETRADE_SERVER . '/v1/accounts/accountkeyid/orders/orderid/change/preview');
defineConst('ORDER_CHANGE_PLACE_URL', ETRADE_SERVER . '/v1/accounts/accountkeyid/orders/orderid/change/place');

function defineConst($name,$value)
{
    if (!defined($name))	
    { 
        define($name, $value);
    }
}


?>
