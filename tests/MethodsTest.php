<?php

namespace Tests;

use PawnPay\Merchant\Models\PaymentMethod;
use PawnPay\Merchant\Responses\Method as MethodResponse;
use PawnPay\Merchant\Responses\Methods as MethodsResponse;

class MethodsTest extends TestCase
{
    /**
     * @var string
     */
    protected static $PAYER_ID;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $response = static::$CLIENT->createPayer([
            'name'    => 'Test Payer',
            'email'   => static::$FAKER->safeEmail,
        ]);

        static::$PAYER_ID = $response->payer->id;
    }

    public static function tearDownAfterClass(): void
    {
        static::$CLIENT->deletePayer(static::$PAYER_ID);

        parent::tearDownAfterClass();
    }

    /**
     * @test
     */
    public function creates_credit_methods()
    {
        $request = [
            'name'         => 'Test Visa',
            'type'         => 'credit',
            'sub_type'     => 'visa',
            'account_name' => 'Test Cardholder',
            'account'      => '4242424242424242',
            'exp'          => '0124',
            'cvv'          => '123',
            'address'      => [
                'street'  => '1234 Test St.',
                'city'    => 'Townsville',
                'state'   => 'GA',
                'postal'  => 30380,
                'country' => 'USA',
            ],
        ];

        $response = self::$CLIENT->createMethod(static::$PAYER_ID, $request);

        $this->assertTrue($response->success, $this->dumpClient());
        $this->assertInstanceOf(MethodResponse::class, $response);
        $this->assertInstanceOf(PaymentMethod::class, $response->method);

        return $response->method->id;
    }

    /**
     * @test
     */
    public function creates_ach_methods()
    {
        $request = [
            'name'         => 'Test Checking',
            'type'         => 'ach',
            'sub_type'     => 'checking',
            'account_name' => 'Test Account',
            'routing'      => '061113415',
            'account'      => '12341234',
            'address'      => [
                'street'  => '1234 Test St.',
                'city'    => 'Townsville',
                'state'   => 'GA',
                'postal'  => 30380,
                'country' => 'USA',
            ],
        ];

        $response = self::$CLIENT->createMethod(static::$PAYER_ID, $request);

        $this->assertTrue($response->success, $this->dumpClient());
        $this->assertInstanceOf(MethodResponse::class, $response);
        $this->assertInstanceOf(PaymentMethod::class, $response->method);

        return $response->method->id;
    }

    /**
     * @test
     * @depends creates_credit_methods
     */
    public function updates_payment_methods(string $method_id)
    {
        $request = [
            'name'    => 'Test Update',
            'address' => [
                'street'  => '43211 Test St.',
            ],
        ];

        $response = static::$CLIENT->updateMethod($method_id, $request);

        $this->assertTrue($response->success, $this->dumpClient());
        $this->assertInstanceOf(MethodResponse::class, $response);
        $this->assertInstanceOf(PaymentMethod::class, $response->method);
    }

    /**
     * @test
     */
    public function gets_payment_methods()
    {
        $response = static::$CLIENT->getMethods(static::$PAYER_ID);

        $this->assertTrue($response->success, $this->dumpClient());
        $this->assertInstanceOf(MethodsResponse::class, $response);
        $this->assertIsArray($response->methods);
        $this->assertInstanceOf(PaymentMethod::class, $response->methods[0]);
    }

    /**
     * @test
     * @depends creates_ach_methods
     */
    public function deletes_payment_methods(string $method_id)
    {
        $success = static::$CLIENT->deleteMethod($method_id);

        $this->assertTrue($success, $this->dumpClient());
    }
}
