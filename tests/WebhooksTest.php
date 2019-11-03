<?php

namespace Tests;

use PawnPay\Merchant\Models\Webhook;
use PawnPay\Merchant\Responses\Webhook as WebhookResponse;
use PawnPay\Merchant\Responses\Webhooks as WebhooksResponse;

class WebhooksTest extends TestCase
{
    /**
     * @test
     */
    public function creates_webhooks()
    {
        $response = static::$CLIENT->createWebhook('transaction.created', 'https://gateway.pawn-pay.com/webhooks');

        $this->assertTrue($response->success, $this->dumpClient());
        $this->assertInstanceOf(WebhookResponse::class, $response);
        $this->assertInstanceOf(Webhook::class, $response->webhook);

        return $response->webhook->id;
    }

    /**
     * @test
     * @depends creates_webhooks
     */
    public function gets_webhooks(string $hook_id)
    {
        $response = self::$CLIENT->getWebhook($hook_id);

        $this->assertTrue($response->success, $this->dumpClient());
        $this->assertInstanceOf(WebhookResponse::class, $response);
        $this->assertInstanceOf(Webhook::class, $response->webhook);
    }

    /**
     * @test
     * @depends creates_webhooks
     */
    public function updates_webhooks(string $hook_id)
    {
        $response = self::$CLIENT->updateWebhook(
            $hook_id,
            'transaction.created',
            'https://gateway.pawn-pay.com/webhooks2'
        );

        $this->assertTrue($response->success, $this->dumpClient());
        $this->assertInstanceOf(WebhookResponse::class, $response);
        $this->assertInstanceOf(Webhook::class, $response->webhook);
    }

    /**
     * @test
     */
    public function lists_webhooks()
    {
        $response = self::$CLIENT->listWebhooks('transaction.created');

        $this->assertTrue($response->success, $this->dumpClient());
        $this->assertInstanceOf(WebhooksResponse::class, $response);
        $this->assertIsArray($response->webhooks);
        $this->assertInstanceOf(Webhook::class, $response->webhooks[0]);
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
