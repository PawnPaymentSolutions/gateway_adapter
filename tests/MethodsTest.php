<?php

namespace Tests;

class MethodsTest extends TestCase
{
    /**
     * @var string
     */
    protected static $PAYER_ID;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $payer = static::$CLIENT->createPayer([
            'name'    => 'Test Payer',
            'email'   => 'test.payer@example.com',
        ]);

        static::$PAYER_ID = $payer->id;
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

        $method = self::$CLIENT->createMethod(static::$PAYER_ID, $request);

        $last_response = self::$CLIENT->getLastResponse();

        $this->assertEquals(201, $last_response['status'], $this->dumpClient());

        return $method->id;
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

        $method = self::$CLIENT->createMethod(static::$PAYER_ID, $request);

        $last_response = self::$CLIENT->getLastResponse();

        $this->assertEquals(201, $last_response['status'], $this->dumpClient());

        return $method->id;
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

        $method = static::$CLIENT->updateMethod($method_id, $request);

        $last_response = self::$CLIENT->getLastResponse();

        $this->assertEquals(200, $last_response['status'], $this->dumpClient());
    }

    /**
     * @test
     */
    public function gets_payment_methods()
    {
        $methods = static::$CLIENT->getMethods(static::$PAYER_ID);

        $this->assertIsArray($methods);

        $last_response = self::$CLIENT->getLastResponse();

        $this->assertEquals(200, $last_response['status'], $this->dumpClient());
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
