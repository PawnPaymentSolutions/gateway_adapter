<?php

namespace Tests;

use PawnPay\Merchant\Models\Payer;
use PawnPay\Merchant\Responses\Payer as PayerResponse;

class PayersTest extends TestCase
{
    /**
     * @test
     */
    public function handles_failures_gracefully()
    {
        $response = self::$CLIENT->createPayer([]);

        $this->assertFalse($response->success, $this->dumpClient());
    }

    /**
     * @test
     */
    public function creates_payers()
    {
        $request = [
            'name'    => 'Johnny Test',
            'email'   => static::$FAKER->safeEmail,
            'phone'   => '+19544941234',
            'address' => [
                'street'  => '1234 Test St.',
                'city'    => 'Townsville',
                'state'   => 'GA',
                'postal'  => 30380,
                'country' => 'USA',
            ],
        ];

        $response = self::$CLIENT->createPayer($request);

        $this->assertTrue($response->success, $this->dumpClient());
        $this->assertInstanceOf(PayerResponse::class, $response);
        $this->assertInstanceOf(Payer::class, $response->payer);

        return $response->payer->id;
    }

    /**
     * @test
     * @depends creates_payers
     */
    public function updates_payers(string $payer_id)
    {
        $request = [
            'name'    => 'Test Update',
            'address' => [
                'street' => '4321 Test St.',
            ],
        ];

        $response = self::$CLIENT->updatePayer($payer_id, $request);

        $this->assertTrue($response->success, $this->dumpClient());
        $this->assertInstanceOf(PayerResponse::class, $response);
        $this->assertInstanceOf(Payer::class, $response->payer);
    }

    /**
     * @test
     * @depends creates_payers
     */
    public function deletes_payers(string $payer_id)
    {
        $success = self::$CLIENT->deletePayer($payer_id);

        $this->assertTrue($success, $this->dumpClient());
    }
}
