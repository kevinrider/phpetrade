<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use phpetrade\Authorization;
use phpetrade\Config;

final class AuthorizationTest extends TestCase
{
    private $auth_obj;

    protected function setUp(): void
    {
        $config = new Config(true);
        $this->auth_obj = new Authorization($config);
    }

    public function testRenewAccessToken(): void
    {
        $this->assertEquals('Access Token has been renewed', $this->auth_obj->RenewAccessToken());
    }

    public function testRevokeAccessToken(): void
    {
        //There is a space after Token
        $this->assertEquals('Revoked Access Token ', $this->auth_obj->RevokeAccessToken());
    }

    public function test401OnRevoked(): void
    {
        $this->expectErrorMessageMatches('/401/');
        $this->auth_obj->RenewAccessToken();
    }
}
