<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use phpetrade\Alerts;

final class AlertsTest extends TestCase
{
    private $al_obj;

    protected function setUp(): void
    {
        $this->al_obj = new Alerts();
    }
}