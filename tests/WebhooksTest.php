<?php

namespace Tests;

class WebhooksTest extends TestCase
{
    /**
     * @test
     */
    public function creates_webhooks()
    {
        $hook = static::$CLIENT->createWebhook('transaction.created', 'https://gateway.pawn-pay.com/webhooks');

        $last_response = self::$CLIENT->getLastResponse();

        $this->assertEquals(201, $last_response['status'], $this->dumpClient());

        return $hook->id;
    }

    /**
     * @test
     * @depends creates_webhooks
     */
    public function gets_webhooks(string $hook_id)
    {
        $hook = self::$CLIENT->getWebhook($hook_id);

        $last_response = self::$CLIENT->getLastResponse();

        $this->assertEquals(200, $last_response['status'], $this->dumpClient());
    }

    /**
     * @test
     * @depends creates_webhooks
     */
    public function updates_webhooks(string $hook_id)
    {
        $hook = self::$CLIENT->updateWebhook(
            $hook_id,
            'transaction.created',
            'https://gateway.pawn-pay.com/webhooks2'
        );

        $last_response = self::$CLIENT->getLastResponse();

        $this->assertEquals(200, $last_response['status'], $this->dumpClient());
    }

    /**
     * @test
     */
    public function lists_webhooks()
    {
        $hooks = self::$CLIENT->listWebhooks('transaction.created');

        $last_response = self::$CLIENT->getLastResponse();

        $this->assertEquals(200, $last_response['status'], $this->dumpClient());
    }

    /**
     * @test
     * @depends creates_webhooks
     */
    public function deletes_webhooks(string $hook_id)
    {
        $success = self::$CLIENT->deleteWebhook($hook_id);

        $this->assertTrue($success, $this->dumpClient());
    }

    /**
     * @test
     */
    public function validates_webhooks()
    {
        $timestamp = (string) time();
        $token     = '12345';
        $signature = hash_hmac(
            'sha256',
            $token.$timestamp,
            getenv('MERCHANT_SECRET')
        );

        $valid = self::$CLIENT->validateWebhook($timestamp, $token, $signature);

        $this->assertTrue($valid);
    }
}
