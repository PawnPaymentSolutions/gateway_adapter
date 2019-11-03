<?php

namespace Tests;

use PawnPay\Merchant\MerchantClient;

class ClientTest extends TestCase
{
    /**
     * @test
     */
    public function returns_true_on_valid_credentials()
    {
        $client = new MerchantClient(
            getenv('MERCHANT_ID'),
            getenv('MERCHANT_KEY'),
            getenv('MERCHANT_SECRET'),
            getenv('API_URL')
        );

        $success = $client->testAuth();

        $this->assertTrue($success, $this->dumpClient($client));
    }

    /**
     * @test
     */
    public function returns_false_on_invalid_credentials()
    {
        $client = new MerchantClient(
            'OOGA-BOOGA',
            'HOTGARBAGE',
            getenv('MERCHANT_SECRET'),
            getenv('API_URL')
        );

        $this->assertFalse($client->testAuth(), $this->dumpClient($client));
    }
}
