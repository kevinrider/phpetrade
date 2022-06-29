<?php

declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use phpetrade\Market;
use phpetrade\Config;

final class MarketTest extends TestCase
{
    private Market $mk_obj;

    protected function setUp(): void
    {
        $config = new Config(true);
        $this->mk_obj = new Market($config);
    }

    public function testMarketGetQuotes(): void
    {
        $market_quotes_para["symbols"][] = "MRK";
        $market_quotes_para["symbols"][] = "PFE";
        $market_quotes_para["symbols"][] = "JNJ";
        $market_quotes_para["symbols"][] = "AZN";
        $market_quotes_para["detailFlag"] = "ALL";
        $market_quotes_para["overrideSymbolCount"] = "true";
        $mk_quotes = $this->mk_obj->MarketGetQuotes($market_quotes_para);
        $this->assertEquals("4", count($mk_quotes->QuoteData));
        $this->assertEquals("MRK", $mk_quotes->QuoteData[0]->Product->symbol);
        $this->assertEquals("PFE", $mk_quotes->QuoteData[1]->Product->symbol);
        $this->assertEquals("JNJ", $mk_quotes->QuoteData[2]->Product->symbol);
        $this->assertEquals("AZN", $mk_quotes->QuoteData[3]->Product->symbol);
    }

    public function testMarketLookup(): void
    {
        $market_lookup_para["search"] = "Apple";
        $mk_lookup = $this->mk_obj->MarketLookUp($market_lookup_para);
        $this->assertGreaterThan(0, count($mk_lookup->Data));
        $this->assertEquals("AAPL", $mk_lookup->Data[0]->symbol);
    }

    public function testMarketGetOptionChain(): void
    {
        $market_optionchain_para["symbol"] = "MTN";
        $market_optionchain_para["chainType"] = "CALLPUT";
        $market_optionchain_para["strikePriceNear"] = "270";
        $mk_optionchain = $this->mk_obj->MarketGetOptionChain($market_optionchain_para);
        $this->assertGreaterThan(0, count($mk_optionchain->OptionPair));
        $this->assertTrue(isset($mk_optionchain->OptionPair[0]->Call));
        $this->assertTrue(isset($mk_optionchain->OptionPair[0]->Put));
    }

    public function testMarketGetOptionExp(): void
    {
        $market_optionchainexp_para["symbol"] = "MTN";
        $mk_optionchainexp = $this->mk_obj->MarketGetOptionExp($market_optionchainexp_para);
        $this->assertGreaterThan(0, count($mk_optionchainexp->ExpirationDate));
        $this->assertTrue(isset($mk_optionchainexp->ExpirationDate[0]->year));
        $this->assertTrue(isset($mk_optionchainexp->ExpirationDate[0]->month));
    }

    /**
    * @dataProvider UrlProvider
    */
    public function testbuildFullUrl($a, $b, $expected): void
    {
        $this->assertSame($expected, $this->mk_obj->buildFullUrl($a, $b));
    }

    public function urlProvider(): array
    {
        return [
            'single'  => ["http://localhost", array("id" => "12423"), "http://localhost?id=12423"],
            'array symbols' => ["http://localhost", array("symbols" => array("NFLX","AMZN","FB")), "http://localhost/NFLX,AMZN,FB"],
            'search w/ encode' => ["http://localhost", array("search" => "<>hello"), "http://localhost/%3C%3Ehello"],
            'array symbols w/ arg'  => ["http://localhost",array("symbols" => array("NFLX","AMZN","FB"),"mode" => "true"), "http://localhost/NFLX,AMZN,FB?mode=true"]
        ];
    }
}
