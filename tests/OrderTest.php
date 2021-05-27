<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use phpetrade\Order;
use phpetrade\Accounts;

final class OrderTest extends TestCase
{
    private $ord_obj;
    private $ac_obj;
    private $account_id_key;

    protected function setUp(): void
    {
        $this->ord_obj = new Order();
        $this->ac_obj = new Accounts();
        $response = $this->ac_obj->GetAccountList();
        $this->account_id_key = (string) $response->Accounts->Account->accountIdKey;
    }

    public function testListOrders(): void
    {
        $order_list_para["count"] = "2";
        $order_list_para["marketSession"] = "REGULAR";
        $order_list_para["status"] = "EXECUTED";
        $ord_list = $this->ord_obj->ListOrders($this->account_id_key,$order_list_para);
        $this->assertEquals("2",count($ord_list->Order));
        $this->assertTrue(isset($ord_list->Order[0]->orderId));
        $this->assertTrue(isset($ord_list->Order[0]->OrderDetail));
    }

    public function testPreviewOrder(): array
    {
        $client_order_id = 'test' . $this->ord_obj->RandomString();
        $order_preview_para["PreviewOrderRequest"]["orderType"] = "EQ";
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
        $ord_preview = $this->ord_obj->PreviewOrder($this->account_id_key,$order_preview_para);
        $this->assertIsObject($ord_preview);
        $this->assertEquals("EQ",$ord_preview->orderType);
        $this->assertEquals("LIMIT",$ord_preview->Order->priceType);
        $this->assertEquals("4.25",$ord_preview->Order->limitPrice);
        $this->assertEquals("AMZN",$ord_preview->Order->Instrument->Product->symbol);
        $this->assertEquals("1",$ord_preview->Order->Instrument->quantity);
        $preview_id = $ord_preview->PreviewIds->previewId;
        $ids['previewid'] = $preview_id;
        $ids['clientorderid'] = $client_order_id;

        return $ids;
    }

    /**
    * @depends testPreviewOrder
    */
    public function testPlaceOrder($ids): int
    {
        $order_place_para["PlaceOrderRequest"]["orderType"] = "EQ"; 
        $order_place_para["PlaceOrderRequest"]["clientOrderId"] = $ids['clientorderid'];
        $order_place_para["PlaceOrderRequest"]["PreviewIds"]["previewId"] = $ids['previewid'];
        $order_place_para["PlaceOrderRequest"]["Order"]["allOrNone"] = "true";
        $order_place_para["PlaceOrderRequest"]["Order"]["priceType"] = "LIMIT";
        $order_place_para["PlaceOrderRequest"]["Order"]["orderTerm"] = "GOOD_FOR_DAY";
        $order_place_para["PlaceOrderRequest"]["Order"]["marketSession"] = "REGULAR";
        $order_place_para["PlaceOrderRequest"]["Order"]["limitPrice"] = "4.25";
        $order_place_para["PlaceOrderRequest"]["Order"]["Instrument"]["Product"]["securityType"] = "EQ";
        $order_place_para["PlaceOrderRequest"]["Order"]["Instrument"]["Product"]["symbol"] = "AMZN";
        $order_place_para["PlaceOrderRequest"]["Order"]["Instrument"]["orderAction"] = "BUY";
        $order_place_para["PlaceOrderRequest"]["Order"]["Instrument"]["quantityType"] = "QUANTITY";
        $order_place_para["PlaceOrderRequest"]["Order"]["Instrument"]["quantity"] = "1";
        $ord_place = $this->ord_obj->PlaceOrder($this->account_id_key,$order_place_para);
        $this->assertIsObject($ord_place);
        $this->assertEquals("EQ",$ord_place->orderType);
        $this->assertEquals("LIMIT",$ord_place->Order->priceType);
        $this->assertEquals("4.25",$ord_place->Order->limitPrice);
        $this->assertEquals("AMZN",$ord_place->Order->Instrument->Product->symbol);
        $this->assertEquals("1",$ord_place->Order->Instrument->quantity);
        $this->assertTrue(isset($ord_place->OrderIds->orderId));
        $order_id = (int) $ord_place->OrderIds->orderId;

        return $order_id;
    }

    /**
    * @depends testPlaceOrder
    */
    public function testChangePreviewOrder($order_id): array
    {
        $client_order_id = 'test' . $this->ord_obj->RandomString();
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
        $ord_change_preview = $this->ord_obj->ChangePreviewOrder($this->account_id_key,$order_id,$order_change_preview_para);
        $this->assertIsObject($ord_change_preview);
        $this->assertEquals("EQ",$ord_change_preview->orderType);
        $this->assertEquals("LIMIT",$ord_change_preview->Order->priceType);
        $this->assertEquals("3.25",$ord_change_preview->Order->limitPrice);
        $this->assertEquals("AMZN",$ord_change_preview->Order->Instrument->Product->symbol);
        $this->assertEquals("1",$ord_change_preview->Order->Instrument->quantity);
        $preview_id = $ord_change_preview->PreviewIds->previewId;
        $ids['previewid'] = $preview_id;
        $ids['clientorderid'] = $client_order_id;
        $ids['orderid'] = $order_id;

        return $ids;
    }

    /**
    * @depends testChangePreviewOrder
    */
    public function testPlaceChangeOrder($ids): int
    {
        $order_place_change_para["PlaceOrderRequest"]["orderType"] = "EQ"; 
        $order_place_change_para["PlaceOrderRequest"]["clientOrderId"] = $ids['clientorderid'];
        $order_place_change_para["PlaceOrderRequest"]["PreviewIds"]["previewId"] = $ids['previewid'];
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
        $ord_place_change = $this->ord_obj->PlaceChangeOrder($this->account_id_key,$ids['orderid'],$order_place_change_para);
        $this->assertIsObject($ord_place_change);
        $this->assertEquals("EQ",$ord_place_change->orderType);
        $this->assertEquals("LIMIT",$ord_place_change->Order->priceType);
        $this->assertEquals("3.25",$ord_place_change->Order->limitPrice);
        $this->assertEquals("AMZN",$ord_place_change->Order->Instrument->Product->symbol);
        $this->assertEquals("1",$ord_place_change->Order->Instrument->quantity);
        $this->assertTrue(isset($ord_place_change->OrderIds->orderId));
        $order_id = (int) $ord_place_change->OrderIds->orderId;

        return $order_id;
    }

    /**
    * @depends testPlaceChangeOrder
    */
    public function testCancelOrder($order_id): void
    {
        $order_cancel_para["CancelOrderRequest"]["orderId"] = $order_id;
        $ord_cancel = $this->ord_obj->CancelOrder($this->account_id_key,$order_cancel_para);
        $this->assertIsObject($ord_cancel);
        $this->assertEquals($order_id,(int) $ord_cancel->orderId);
        $this->assertEquals("5011",$ord_cancel->Messages->Message->code);
        $this->assertEquals("200|Your request to cancel your order is being processed.",$ord_cancel->Messages->Message->description);
    }

    /**
    * @dataProvider UrlProvider
    */
    public function testbuildFullUrl($a, $b, $expected): void
    {
        $this->assertSame($expected, $this->ord_obj->buildFullUrl($a,$b));
    }

    public function urlProvider(): array
    {
        return [
            'single simple'  => ["http://localhost", array("id" => "12423"), "http://localhost?id=12423"],
            'double simple' => ["http://localhost", array("id" => "12423", "mode" => "true"), "http://localhost?id=12423&mode=true"],
            'triple w/ encode' => ["http://localhost", array("id" => "12423", "mode" => "true", "test" => "<>hello"), "http://localhost?id=12423&mode=true&test=%3C%3Ehello"],
            'double -'  => ["http://localhost", array("id" => "12423", "mode" => "-true-"), "http://localhost?id=12423&mode=-true-"]
        ];
    }
}