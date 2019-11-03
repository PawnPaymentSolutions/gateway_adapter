<?php

namespace Tests;

class TransactionsTest extends TestCase
{
    /**
     * @test
     */
    public function authorizes_transactions()
    {
        $transaction = static::$CLIENT->authorize([
            'amount'   => 1134,
            'currency' => 'USD',
            'payer'    => [
                'name'    => static::$FAKER->name,
                'email'   => static::$FAKER->safeEmail,
                'phone'   => '9544944444',
                'address' => [
                    'street'  => static::$FAKER->streetAddress,
                    'city'    => static::$FAKER->city,
                    'state'   => static::$FAKER->stateAbbr,
                    'postal'  => static::$FAKER->numerify('1####'),
                    'country' => 'USA',
                ],
            ],
            'payment_method' => [
                'name'         => 'Test Visa',
                'account_name' => static::$FAKER->name,
                'type'         => 'credit',
                'sub_type'     => 'visa',
                'account'      => '4242424242424242',
                'exp'          => (new \DateTime())->modify('last day of next month')->format('my'),
                'cvv'          => '999',
                'address'      => [
                    'postal'  => '840435770',
                    'country' => 'USA',
                ],
            ],
            'invoice' => [
                'number'      => static::$FAKER->bothify('???-####'),
                'description' => 'red hot walnuts',
                'items'       => [
                    ['name' => 'Dank memes', 'quantity' => 40, 'price' => 28],
                ],
                'total' => 1134,
            ],
        ]);

        $last_response = self::$CLIENT->getLastResponse();

        $this->assertEquals(201, $last_response['status'], $this->dumpClient());

        return $transaction->id;
    }

    /**
     * @test
     * @depends authorizes_transactions
     */
    public function captures_transactions(string $transaction_id)
    {
        $transaction = static::$CLIENT->capture($transaction_id);

        $last_response = self::$CLIENT->getLastResponse();

        $this->assertEquals(200, $last_response['status'], $this->dumpClient());
    }

    /**
     * @test
     * @depends authorizes_transactions
     */
    public function gets_transactions(string $transaction_id)
    {
        $transaction = static::$CLIENT->getTransaction($transaction_id);

        $last_response = self::$CLIENT->getLastResponse();

        $this->assertEquals(200, $last_response['status'], $this->dumpClient());
    }

    /**
     * @test
     */
    public function processes_transactions()
    {
        $transaction = static::$CLIENT->process([
            'amount'   => 1134,
            'currency' => 'USD',
            'payer'    => [
                'name'    => static::$FAKER->name,
                'email'   => static::$FAKER->safeEmail,
                'phone'   => '9544944444',
                'address' => [
                    'street'  => static::$FAKER->streetAddress,
                    'city'    => static::$FAKER->city,
                    'state'   => static::$FAKER->stateAbbr,
                    'postal'  => static::$FAKER->numerify('1####'),
                    'country' => 'USA',
                ],
            ],
            'payment_method' => [
                'name'         => 'Test Visa',
                'account_name' => static::$FAKER->name,
                'type'         => 'credit',
                'sub_type'     => 'visa',
                'account'      => '4242424242424242',
                'exp'          => (new \DateTime())->modify('last day of next month')->format('my'),
                'cvv'          => '999',
                'address'      => [
                    'postal'  => '840435770',
                    'country' => 'USA',
                ],
            ],
            'invoice' => [
                'number'      => static::$FAKER->bothify('???-####'),
                'description' => 'red hot walnuts',
                'items'       => [
                    ['name' => 'Dank memes', 'quantity' => 40, 'price' => 28],
                ],
                'total' => 1134,
            ],
        ]);

        $last_response = self::$CLIENT->getLastResponse();

        $this->assertEquals(201, $last_response['status'], $this->dumpClient());

        return $transaction->id;
    }

    /**
     * @test
     * @depends processes_transactions
     */
    public function reverses_transactions(string $transaction_id)
    {
        $transaction = static::$CLIENT->reverse($transaction_id);

        $last_response = self::$CLIENT->getLastResponse();

        $this->assertEquals(200, $last_response['status'], $this->dumpClient());
    }
}
