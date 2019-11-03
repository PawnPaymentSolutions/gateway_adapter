<?php

namespace Tests;

class PayersTest extends TestCase
{
    /**
     * @test
     */
    public function creates_payers()
    {
        $request = [
            'name'    => 'Johnny Test',
            'email'   => 'j.test@example.com',
            'phone'   => '+19544941234',
            'address' => [
                'street'  => '1234 Test St.',
                'city'    => 'Townsville',
                'state'   => 'GA',
                'postal'  => 30380,
                'country' => 'USA',
            ],
        ];

        $payer = self::$CLIENT->createPayer($request);

        $last_response = self::$CLIENT->getLastResponse();

        $this->assertEquals(201, $last_response['status'], $this->dumpClient());

        return $payer->id;
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

        $payer = self::$CLIENT->updatePayer($payer_id, $request);

        $last_response = self::$CLIENT->getLastResponse();

        $this->assertEquals(200, $last_response['status'], $this->dumpClient());
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
