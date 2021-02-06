
# phpetrade

A library of php classes and examples that connects to the [E*Trade v1 REST API](https://apisb.etrade.com/docs/api/account/api-account-v1.html).
Accounts, Alerts, Market, and Order end points are fully implemented.  The Authorization endpoint has all functions implemented except Renew/Revoke Access token methods.


## Disclosure

This software is in no way affiliated, endorsed, or approved by E*TRADE
or any of its affiliates or owners.  It comes with absolutely no warranty and
should not be used in actual trading unless you (the user) can read and
understand the source code.  The Order end point (in production mode) will submit 
trades that will be advertised on the open market and will immediately be filled
if a counter party is present regardless of whether you made a mistake or not.  It is your responsibility to understand, test (in the sandbox), and refine your trades and the code
where necessary before using in a live market environment.

Use the sandbox environment before moving to production, especially if you
are going to use the Order end point!  When starting in the production environment you should create
and test your trades and code.  This can include submitting orders when the regular
market session is closed and setting the marketSession to REGULAR.  You can
also submit trades with a limit price that is far below the market bid if going long or well above the ask
if you are going short.  Do not use priceType=MARKET trades unless you want an immediate fill on the order.  MARKET trades are definitely not recommended for options, especially those on low liquidity symbols or on strike prices that are deep OTM.

## Install

In order to use this code you need to have an E*Trade API account setup.  Visit this [link](https://developer.etrade.com/home) and click the Log On button to start the process.

Clone or download the repository to a directory of your choice.  You will need
a working PHP 7 cli and the excellent [pecl oauth library](https://pecl.php.net/package/oauth) installed 
in order to use phpetrade.  

Installing on Ubuntu will go something like this.  INSTALL_DIR is wherever you decided to clone phpetrade.  The code
can be installed anywhere and does not necessarily need to be installed in a web server accessible directory.  All example scripts run from the command line.
```
sudo apt-get install php7.2-cli
- or -
sudo apt-get install php-cli
- then -
pecl channel-update pecl.php.net
pecl install oauth

- then - 
cd to the INSTALL_DIR/phpetrade/examples.  

php test.php|grep -i oauth

- output -
OAuth
OAuth support => enabled
```
If php-cli and pecl oauth module install correctly but you don't see OAuth => enabled, you can manually
enable the module in php-cli php.ini file (which is different than the php web server module php.ini).  The
cli php.ini is usually available at something like: /etc/php/7.2/cli/php.ini (where 7.2 is the php version you are currently using).  Depending on the operating system you're using, the php.ini may be stored someplace else or is even the same as the web server module php.ini.

## Composer

This library uses Composer autoloading and will be available on Packagist soon.  To setup the autoloading:
```
cd INSTALL_DIR/phpetrade
curl -sS https://getcomposer.org/installer | php
php composer.phar update
```
Currently there are no requirements other than PHP 7.2 or greater.  However using composer will setup the autoloading of the API endpoint classes and a few config files.

## Authentication
Copy phpetrade/src/config.php.example to phpetrade/src/config.php and fill in your APP_KEY and APP_SECRET.  If you have sandbox keys only, then only
copy those into the sandbox keys in the code and vice versa for production keys.  Set the sandbox/production environment on line 11, the default is sandbox.

Logging into the E*Trade API is a two step process because the API uses the OAuth 1.0a specification.

First (from a terminal):
```
cd INSTALL_DIR/phpetrade
php auth.php

Your token authorize URL is : 

---------------------------------------------------------------
https://us.etrade.com/e/t/etws/authorize?key=SOMEKEY&token=SOMETOKEN

---------------------------------------------------------------
Please follow the above URL and get the verifier code (required to get the final access token).

Please enter the verifier code :

```

Second:
Follow the link (copy and paste into your browser), login to E*Trade and, follow the prompts and then
you should receive a 5 character Verifier code.  Copy the code into verifier code prompt then hit enter.

If all goes well you should see the following output.  
```
Here is your final authorized token and has been written to config.php
---------------------------------------------------------------

Token   : SOMEACCESSKEY
Secret  : SOMEACCESSSECRET
---------------------------------------------------------------

```
Note that SOMEKEY, SOMETOKEN, SOMEACCESSKEY, SOMEACCESSSECRET
are just examples.  The actual output for each will look something like an md5 hash.

The OAuth session is now setup and you can start issuing request with the API.  If there are no API requests for more
than 2 hours the Access keys are expired on the server side and you must authenticate again.

## Classes
In the classes directory are the libraries for each endpoint: accounts, market, alerts, and orders.
The oauthhttp class implements the communication stack (OAuth+HTTP) for each API request.
The orderticket class is for order types beyond simple equity orders, such as single and multi leg
option orders.

## Examples
The examples directory contains various test scripts that demonstrate how phpetrade is used.  I have added an "exit;" line to the beginning of each script which you
must delete or comment out before running the scripts.  This is to prevent accidents, because nearly all the scripts will issue an order of some type.
If you are in production mode these orders will go out on the live market and possibly be filled.  I've tried to select orders that would be unlikely to fill, but the market moves 
and these orders may fill at some point.

- sample.php : The Accounts, Alerts, Market, and Orders (for simple equity orders) end points.
- option.php : Single Leg Option Order example.
- spread.php : Double Leg Option Order example.
- iron_condor.php : Quad Leg Option Order example.
- orderticket.php : A more generalized way to create/issue option orders.

## Altering Requests
For most end points adding an additional request parameter is just a matter of adding to the parameter array.  For example in sample.php the following code is used to query the Accounts - Transaction Details end point.
```
$account_tran_id_para["Accept"] = "application/xml";
$account_tran_id_para["storeId"] = "0";
$ac_tran_id = $ac_obj->GetAccountTransactionDetails($account_id_key,$transaction_id,$account_tran_id_para);
```

If you wanted to only show transactions prior to 11/20/2020 and only return 25 transactions at a time, you would simply add those parameters to the array.
```
$account_tran_id_para["Accept"] = "application/xml";
$account_tran_id_para["storeId"] = "0";
$account_tran_id_para["count"] = "25";
$account_tran_id_para["endDate"] = "11202020";
$ac_tran_id = $ac_obj->GetAccountTransactionDetails($account_id_key,$transaction_id,$account_tran_id_para);
```

The sample scripts were not intended to show every possible combination of parameters, just the basics of using the various methods in each end point.
While the [E*Trade API documents](https://apisb.etrade.com/docs/api/account/api-transaction-v1.html) have a few issues mentioned below, in general they do a pretty good job of listing 
all of the possible parameters that a given method takes and the possible responses.

## Equity Orders
If you only trade stocks and ETFs (no options) the order.class.php should be sufficient for your needs.  The XML request structure is straight forward and all of the Order methods 
(Preview, Place, Cancel, Preview Changed Order, Place Changed Order) work in a straight forward way for equities.

## Option Orders
Option orders are where the API gets a bit more complex.  One problem is that options can have 1 to 4 legs
(4 is the single order limit) plus they can also be mixed with stocks (such as a buy write, 2 legs, or a collar, 3 legs).  Furthermore the API documents are incorrect when describing
the XML request format for option orders.

For example from the [documents](https://apisb.etrade.com/docs/api/order/api-order-v1.html#/definition/orderPlace) for an Option Spread Order:
```
<?xml version="1.0" encoding="UTF-8"?>
<root>
   <PlaceOrderRequest>
      <Order>
         <element>
            <Instrument>
               <element>
                  <Product>
                     <callPut>CALL</callPut>
                     <expiryDay>15</expiryDay>
                     <expiryMonth>02</expiryMonth>
                     <expiryYear>2019</expiryYear>
                     <securityType>OPTN</securityType>
                     <strikePrice>130</strikePrice>
                     <symbol>IBM</symbol>
                  </Product>
                  <orderAction>BUY_OPEN</orderAction>
                  <orderedQuantity>1</orderedQuantity>
                  <quantity>1</quantity>
               </element>
               <element>
                  <Product>
                     <callPut>CALL</callPut>
                     <expiryDay>15</expiryDay>
                     <expiryMonth>02</expiryMonth>
                     <expiryYear>2019</expiryYear>
                     <securityType>OPTN</securityType>
                     <strikePrice>131</strikePrice>
                     <symbol>IBM</symbol>
                  </Product>
                  <orderAction>SELL_OPEN</orderAction>
                  <orderedQuantity>1</orderedQuantity>
                  <quantity>1</quantity>
               </element>
            </Instrument>
            <allOrNone>false</allOrNone>
            <limitPrice>5</limitPrice>
            <marketSession>REGULAR</marketSession>
            <orderTerm>GOOD_FOR_DAY</orderTerm>
            <priceType>NET_DEBIT</priceType>
            <stopPrice>0</stopPrice>
         </element>
      </Order>
      <PreviewIds>
         <element>
            <previewId>3429218279</previewId>
         </element>
      </PreviewIds>
      <clientOrderId>3453f1</clientOrderId>
      <orderType>SPREADS</orderType>
   </PlaceOrderRequest>
</root>
```
This is wrong and should actually be as follows:
```
<?xml version="1.0" encoding="UTF-8"?>
   <PlaceOrderRequest>
      <Order>
            <Instrument>
                  <Product>
                     <callPut>CALL</callPut>
                     <expiryDay>15</expiryDay>
                     <expiryMonth>02</expiryMonth>
                     <expiryYear>2019</expiryYear>
                     <securityType>OPTN</securityType>
                     <strikePrice>130</strikePrice>
                     <symbol>IBM</symbol>
                  </Product>
                  <orderAction>BUY_OPEN</orderAction>
                  <orderedQuantity>1</orderedQuantity>
                  <quantity>1</quantity>
            </Instrument>
            <Instrument>
                  <Product>
                     <callPut>CALL</callPut>
                     <expiryDay>15</expiryDay>
                     <expiryMonth>02</expiryMonth>
                     <expiryYear>2019</expiryYear>
                     <securityType>OPTN</securityType>
                     <strikePrice>131</strikePrice>
                     <symbol>IBM</symbol>
                  </Product>
                  <orderAction>SELL_OPEN</orderAction>
                  <orderedQuantity>1</orderedQuantity>
                  <quantity>1</quantity>
            </Instrument>
            <allOrNone>false</allOrNone>
            <limitPrice>5</limitPrice>
            <marketSession>REGULAR</marketSession>
            <orderTerm>GOOD_FOR_DAY</orderTerm>
            <priceType>NET_DEBIT</priceType>
            <stopPrice>0</stopPrice>
      </Order>
      <PreviewIds>
            <previewId>3429218279</previewId>
      </PreviewIds>
      <clientOrderId>3453f1</clientOrderId>
      <orderType>SPREADS</orderType>
   </PlaceOrderRequest>
```
The `<root>` and `<element>` tags are wrong and each leg of the spread must be wrapped in its own `<Instrument>` tag.  In the examples folder there are the following files:
option.php, spread.php, and iron_condor.php which respectively show how to place 1 (long put), 2 (bull put credit spread), and 4 (iron condor) legged option orders.
I've tested these order types in a production environment and they are consistently and correctly accepted by the API servers.  Its worth noting that the Preview and Place 
orders must match identically (I suspect even the white space) other than the place order request has the root PlaceOrderRequest vs PreviewOrderRequest tag and the place order request has the required
previewId included.  If they don't match identically the PlaceOrder request is not accepted with some odd and inconsistent error codes, even after the API server clears the
exact same (in parameters) Preview Order request and issues a valid previewId.

Given these problems, I wrote up the orderticket class.  To use it you first create a xml template (i.e. a blank order "ticket") with the order parameters you will use in your option order.  This template is a skeleton of sorts
that holds the xml structure.  You then parse in the various option order parameter values (strike, symbol, priceType, expiration, etc) and then attach the completed XML to a HTTP/OAuth request.
I've written up five general XML tickets that should cover nearly all option orders that are in common use in Long or Short form.

- Single Leg: Calls or Puts
- Double Leg: Spreads, Strangles, Straddles, and Calenders
- Triple Leg: Call/Put Butterfly
- Quad Leg: Iron Condor, Iron Butterfly, Call/Put Condors
- Buy Writes: Stock + Single Option leg

Call/Put Condors were not tested but should work as a Quad Leg. Collars should also be possible, but would require creating a new ticket.  Collars would be two option `<Instrument>` tags and one equity `<Instrument>` tag.

I also did not implement the Change Order Preview and Place Changed Order for options, although these should be straight forward.  The only difference between a new order preview/place and a change preview/place
is that the URL path includes the `orderId` that is being changed.  So if you have Preview/Place order working correctly, adapting to an existing order is a matter of just pulling the orderId you
want to change and putting it in the URL path, examples are given in the sample.php file for an equity order.

# FAQ
- [My API request is giving XYZ error, what do I do?](#my-api-request-is-giving-xyz-error-what-do-i-do)
- [But I want to place market option orders!](#but-i-want-to-place-market-option-orders)
- [Do I really have to use the Order Preview Method?](#do-i-really-have-to-use-the-order-preview-method)
- [Can this code be used in a web app?](#can-this-code-be-used-in-a-web-app)
- [The code won't run on XYZ operating system!](#the-code-wont-run-on-xyz-operating-system)
- [Is there some way to automate the login process for unattended trading?](#is-there-some-way-to-automate-the-login-process-for-unattended-trading)
- [What about json requests and responses?](#what-about-json-requests-and-responses)

## My API request is giving ZYX error, what do I do?

The best place to start is to enable debugging in the config.php, just set the DEBUG constant to "1".  This will dump the full oauth/http communication log to terminal along with any additional info the
API server may have issued.  Its a lot of info, but usually by looking at the API documentation and the debug log its possible to figure out where the request went wrong.

## But I want to place market option orders!

You probably really don't want to even if you think you do, the potential to get burned on options with a wide bid-ask spread is high, especially on low liquidity and/or far OTM options.  The option order tickets
that I setup only use limit, however you can definitely setup your own tickets with the order parameters that you prefer to use.  Market orders should definitely be previewed and double checked before issuing
the PlaceOrderRequest though because they will fill quicker than you can cancel.

## Do I really have to use the Order Preview Method?

Yes, that's the way the API is setup.  Previewing the order does not necessarily mean that it has to be "bubbled up" to a person to then check again before submitting the final order.  The example code 
also shows how you can simply send the place order request without further review after the previewId is issued.  This process is much quicker than the multiple clicks that is required in Power Etrade to
issue an order or change an existing order.  Although by not closely reviewing Preview orders you are risking not catching an incorrect order that your code has produced and is about to submit.

## Can this code be used in a web app?

Yes, although the authorization script is command line only.  In other words, you can use the class libraries in whatever PHP based web app you can imagine, simply `require_once` in the classes you need; however
the authorization will need to occur on the command line.  You can configure a callback url with ETrade for your API account, once this is configured you would need to adapt the authorization process
to use the callback url, doing away with the command line authorization.  The callback url process for OAuth 1.0a is discussed in the API documents.

## The code won't run on XYZ operating system!

Make sure you are running at least PHP 7 and have the OAuth pecl module correctly installed.  Beyond these requirements there is no operating system specific code in the libraries.

## Is there some way to automate the login process for unattended trading?

Search GitHub and you will find a few examples, some or all of which probably no longer work.  Tony Trevisan, the author of an R based library for the E*Trade API, has put together an automated
login process [with Selenium](https://tonytrevisan.github.io/posts/2020-11-21-the-ultimate-dollar-cost-averaging-strategy/).  Keep in mind these automated logins may break without notice and you
are also storing your account login credentials and API credentials all in one place.

## What about json requests and responses?

The E*Trade API also supports json requests and responses, although this library uses the XML format exclusively.  In limited testing, I found that json worked just fine for all endpoints except when placing an order
request, although I did not test it very much.  It may turn out that json Order requests work just fine if you can figure out the correct format.  
