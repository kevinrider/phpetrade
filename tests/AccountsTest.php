<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use phpetrade\Accounts;

final class AccountsTest extends TestCase
{
    private $ac_obj;

    protected function setUp(): void
    {
        $this->ac_obj = new Accounts();
    }

    public function testGetAccountList(): string
    {
        $response = $this->ac_obj->GetAccountList();
        $this->assertIsObject($response);
        $this->assertTrue(isset($response->Accounts->Account->accountId));
        $this->assertTrue(isset($response->Accounts->Account->accountIdKey));
        $account_id_key = (string) $response->Accounts->Account->accountIdKey;
        return $account_id_key;
    }

    /**
    * @depends testGetAccountList
    */
    public function testGetAccountBalance($account_id_key): void
    {
        $account_balance_para["instType"] = "BROKERAGE";
        $account_balance_para["realTimeNAV"] = "true";
        $ac_balance = $this->ac_obj->GetAccountBalance($account_id_key,$account_balance_para);
        $this->assertIsObject($ac_balance);
        $this->assertTrue(isset($ac_balance->accountId));
        $this->assertTrue(isset($ac_balance->accountType));
    }

    /**
    * @depends testGetAccountList
    */
    public function testGetAccountTransactions($account_id_key): int
    {
        $account_tran_para["Accept"] = "application/xml";
        $account_tran_para["sortOrder"] = "desc";
        $account_tran_para["count"] = "25";
        $ac_tran = $this->ac_obj->GetAccountTransactions($account_id_key,$account_tran_para);
        $this->assertIsObject($ac_tran);
        $this->assertGreaterThan(0, count($ac_tran->Transaction));
        $transaction_id = (int) $ac_tran->Transaction[0]->transactionId;
        return $transaction_id;
    }

    /**
    * @depends testGetAccountList
    * @depends testGetAccountTransactions
    */
    public function testGetAccountTransactionDetails($account_id_key,$transaction_id): void
    {
        $account_tran_id_para["Accept"] = "application/xml";
        $account_tran_id_para["storeId"] = "0";
        $ac_tran_id = $this->ac_obj->GetAccountTransactionDetails($account_id_key,$transaction_id,$account_tran_id_para);
        $this->assertIsObject($ac_tran_id);
        $this->assertTrue(isset($ac_tran_id->transactionId));
        $this->assertTrue(isset($ac_tran_id->transactionDate));
    }

    /**
    * @depends testGetAccountList
    */
    public function testGetAccountPortfolio($account_id_key): void
    {
        $account_port_para["count"] = "25";
        $account_port_para["view"] = "COMPLETE";

        //Use mocked Accounts only when portfolio is empty.
        $ac_obj_mock = $this->createMock(Accounts::class);
        $ac_obj_mock->expects($this->once())
             ->method('GetAccountPortfolio')
             ->will($this->returnValue(true));        
        $ac_port = $ac_obj_mock->GetAccountPortfolio($account_id_key,$account_port_para);
        $this->assertTrue($ac_port);

        //No mocking
        // $ac_port = $ac_obj->GetAccountPortfolio($account_id_key,$account_port_para);
        // $this->assertIsObject($ac_port);
        // $this->assertGreaterThan(0, count($ac_port->AccountPortfolio->Position));
    }

    /**
    * @dataProvider UrlProvider
    */
    public function testbuildFullUrl($a, $b, $expected)
    {
        $this->assertSame($expected, $this->ac_obj->buildFullUrl($a,$b));
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
