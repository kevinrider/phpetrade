<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use phpetrade\OrderTicket;
use phpetrade\Accounts;
use phpetrade\OAuthHTTP;
use phpetrade\Order;

final class OrderTicketTest extends TestCase
{
    private $ord_obj;
    private $ac_obj;
    private $account_id_key;

    protected function setUp(): void
    {
        $this->ord_obj = new OrderTicket(dirname(__FILE__) . "/../src/tickets");
        $this->ac_obj = new Accounts();
        $response = $this->ac_obj->GetAccountList();
        $this->account_id_key = (string) $response->Accounts->Account->accountIdKey;
    }

    public function testOrderTicket(): void
    {
        //IRON CONDOR 340/350 490/500 12/17/2021 SPY
        $this->ord_obj->LoadOptionOrderTicket("quad");

        $client_order_id = 'test' . $this->ord_obj->RandomString();
        $this->ord_obj->Parse("ONECALLPUT", "PUT");
        $this->ord_obj->Parse("ONEEXPDAY", "17");
        $this->ord_obj->Parse("ONEEXPMONTH", "12");
        $this->ord_obj->Parse("ONEEXPYEAR", "2021");
        $this->ord_obj->Parse("ONESTRIKE", "340");
        $this->ord_obj->Parse("SYMBOL", "SPY");
        $this->ord_obj->Parse("ONEORDERACTION", "BUY_OPEN");
        $this->ord_obj->Parse("ONEQUANTITY", "1");

        $this->ord_obj->Parse("TWOCALLPUT", "PUT");
        $this->ord_obj->Parse("TWOEXPDAY", "17");
        $this->ord_obj->Parse("TWOEXPMONTH", "12");
        $this->ord_obj->Parse("TWOEXPYEAR", "2021");
        $this->ord_obj->Parse("TWOSTRIKE", "350");
        $this->ord_obj->Parse("SYMBOL", "SPY");
        $this->ord_obj->Parse("TWOORDERACTION", "SELL_OPEN");
        $this->ord_obj->Parse("TWOQUANTITY", "1");

        $this->ord_obj->Parse("THREECALLPUT", "CALL");
        $this->ord_obj->Parse("THREEEXPDAY", "17");
        $this->ord_obj->Parse("THREEEXPMONTH", "12");
        $this->ord_obj->Parse("THREEEXPYEAR", "2021");
        $this->ord_obj->Parse("THREESTRIKE", "490");
        $this->ord_obj->Parse("SYMBOL", "SPY");
        $this->ord_obj->Parse("THREEORDERACTION", "SELL_OPEN");
        $this->ord_obj->Parse("THREEQUANTITY", "1");

        $this->ord_obj->Parse("FOURCALLPUT", "CALL");
        $this->ord_obj->Parse("FOUREXPDAY", "17");
        $this->ord_obj->Parse("FOUREXPMONTH", "12");
        $this->ord_obj->Parse("FOUREXPYEAR", "2021");
        $this->ord_obj->Parse("FOURSTRIKE", "500");
        $this->ord_obj->Parse("SYMBOL", "SPY");
        $this->ord_obj->Parse("FOURORDERACTION", "BUY_OPEN");
        $this->ord_obj->Parse("FOURQUANTITY", "1");

        $this->ord_obj->Parse("ALLORNONE", "false");
        $this->ord_obj->Parse("LIMITPRICE", "5");
        $this->ord_obj->Parse("CLIENTORDERID", "$client_order_id");
        $this->ord_obj->Parse("PRICETYPE", "NET_CREDIT");
        $this->ord_obj->Parse("ORDERTYPE", "IRON_CONDOR");

        $this->ord_obj->Clone();
        $this->ord_obj->Parse("PLACEPREVIEW", "Preview");
        $this->ord_obj->Parse("PREVIEWID", "");
        $url = str_replace("accountkeyid",$this->account_id_key,$this->ord_obj->config->order_preview_url);
        $OAuthHTTPObj = new OAuthHTTP($url,"POST");
        $OAuthHTTPObj->post_request = $this->ord_obj->ticket_data;
        $ord_preview = $OAuthHTTPObj->GetResponse();
        $preview_id = "";
        $preview_id = $ord_preview->PreviewIds->previewId;

        if($preview_id != "")
        {
            $this->ord_obj->ticket_data = $this->ord_obj->ticket_clone;
            $this->ord_obj->Parse("PLACEPREVIEW", "Place");
            $this->ord_obj->Parse("PREVIEWID", "\n<PreviewIds>\n<previewId>$preview_id</previewId>\n</PreviewIds>");
            $url = str_replace("accountkeyid",$this->account_id_key,$this->ord_obj->config->order_place_url);
            $OAuthHTTPObj->url = $url;
            $OAuthHTTPObj->post_request = $this->ord_obj->ticket_data;
            $ord_place = $OAuthHTTPObj->GetResponse();
        }

        $this->assertIsObject($ord_place);
        $this->assertEquals("IRON_CONDOR",$ord_place->orderType);
        $this->assertEquals("NET_CREDIT",$ord_place->Order->priceType);
        $this->assertEquals("5",(int) $ord_place->Order->limitPrice);
        $this->assertEquals("SPY",$ord_place->Order->Instrument[0]->Product->symbol);
        $this->assertEquals("1",$ord_place->Order->Instrument[0]->quantity);
        $this->assertEquals("1026",$ord_place->Order->messages->Message->code);
        $this->assertMatchesRegularExpression('/successfully entered/', (string) $ord_place->Order->messages->Message->description);

        //Cancel Iron Condor
        $cord_obj = new Order();
        $order_cancel_para["CancelOrderRequest"]["orderId"] = (int) $ord_place->OrderIds->orderId;
        $ord_cancel = $cord_obj->CancelOrder($this->account_id_key,$order_cancel_para);
        $this->assertIsObject($ord_cancel);
        $this->assertEquals("5011",$ord_cancel->Messages->Message->code);
        $this->assertMatchesRegularExpression('/200/',(string) $ord_cancel->Messages->Message->description);
    }
}