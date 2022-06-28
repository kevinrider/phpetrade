<?php

declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use phpetrade\Alerts;
use phpetrade\Config;

final class AlertsTest extends TestCase
{
    private Alerts $al_obj;

    protected function setUp(): void
    {
        $config = new Config(true);
        $this->al_obj = new Alerts($config);
    }

    public function testGetAlertsList(): int
    {
        $alerts_list_para["count"] = "5";
        $al_list = $this->al_obj->AlertsList($alerts_list_para);
        $this->assertIsObject($al_list);
        $this->assertTrue(isset($al_list->totalAlerts));
        $this->assertGreaterThan(0, count($al_list->Alert));
        $alert_id = (int) $al_list->Alert[0]->id;
        return $alert_id;
    }

    /**
    * @depends testGetAlertsList
    */
    public function testAlertDetails($alert_id): void
    {
        $alerts_details_para["id"] = "$alert_id";
        $al_details = $this->al_obj->AlertsListDetails($alerts_details_para);
        $this->assertIsObject($al_details);
        $this->assertSame($alert_id, (int) $al_details->id);
        $this->assertTrue(isset($al_details->subject));
    }

    /**
    * @depends testGetAlertsList
    */
    public function testAlertsDelete($alert_id): void
    {
        $alerts_delete_para["id"][] = "$alert_id";
        $al_delete = $this->al_obj->AlertsDelete($alerts_delete_para);
        $this->assertEquals("SUCCESS", $al_delete->result);
    }

    /**
    * @dataProvider UrlProvider
    */
    public function testbuildFullUrl($a, $b, $expected): void
    {
        $this->assertSame($expected, $this->al_obj->buildFullUrl($a, $b));
    }

    public function urlProvider(): array
    {
        return [
            'single id'  => ["http://localhost", array("id" => 1), "http://localhost/1"],
            'array id' => ["http://localhost", array("id" => array(1,2,3)), "http://localhost/1,2,3"],
            'array id w/ arg' => ["http://localhost", array("id" => array(1,2,3), "mode" => "true"), "http://localhost/1,2,3?mode=true"],
            'array id w/ arg w/ encode'  => ["http://localhost", array("id" => array(1,2,3), "mode" => "true", "test" => "<>hello"), "http://localhost/1,2,3?mode=true&test=%3C%3Ehello"]
        ];
    }
}
