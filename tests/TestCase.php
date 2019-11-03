<?php

namespace Tests;

use Dotenv\Dotenv;
use PawnPay\Merchant\MerchantClient;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /** @var \PawnPay\Merchant\MerchantClient */
    protected static $CLIENT;

    /** @var \Faker\Generator */
    protected static $FAKER;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        // Load .env
        $env = Dotenv::create(__DIR__.'/..');
        $env->load();

        static::$CLIENT = new MerchantClient(
            getenv('MERCHANT_ID'),
            getenv('MERCHANT_KEY'),
            getenv('MERCHANT_SECRET'),
            getenv('API_URL')
        );

        self::$FAKER = \Faker\Factory::create();
    }

    /**
     * @param \PawnPay\Merchant\MerchantClient|null $client
     *
     * @return string
     */
    protected function dumpClient(MerchantClient $client = null)
    {
        if (!$client) {
            $client = static::$CLIENT;
        }

        return \json_encode([
            'request'  => $client->getLastRequest(),
            'response' => $client->getLastResponse(),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
