<?php

namespace Tests;

use PawnPay\Merchant\Models\Transaction;
use PawnPay\Merchant\Responses\Transaction as TransactionResponse;

class TransactionsTest extends TestCase
{
    /**
     * @test
     */
    public function authorizes_transactions()
    {
        $response = static::$CLIENT->authorize([
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

        $this->assertTrue($response->success, $this->dumpClient());
        $this->assertInstanceOf(TransactionResponse::class, $response);
        $this->assertInstanceOf(Transaction::class, $response->transaction);

        return $response->transaction->id;
    }

    /**
     * @test
     */
    public function handles_failures_gracefully()
    {
        $response = static::$CLIENT->authorize([
            'amount'   => 'FEED_ME_ERRORS',
        ]);

        $this->assertFalse($response->success, $this->dumpClient());
        $this->assertInstanceOf(TransactionResponse::class, $response);
        $this->assertNotNull($response->message);
        $this->assertNotNull($response->errors);
        $this->assertIsArray($response->errors);
    }

    /**
     * @test
     * @depends authorizes_transactions
     */
    public function captures_transactions(string $transaction_id)
    {
        $response = static::$CLIENT->capture($transaction_id);

        $this->assertTrue($response->success, $this->dumpClient());
        $this->assertInstanceOf(TransactionResponse::class, $response);
        $this->assertInstanceOf(Transaction::class, $response->transaction);
    }

    /**
     * @test
     * @depends authorizes_transactions
     */
    public function gets_transactions(string $transaction_id)
    {
        $response = static::$CLIENT->getTransaction($transaction_id);

        $this->assertTrue($response->success, $this->dumpClient());
        $this->assertInstanceOf(TransactionResponse::class, $response);
        $this->assertInstanceOf(Transaction::class, $response->transaction);
    }

    /**
     * @test
     */
    public function processes_transactions()
    {
        $response = static::$CLIENT->process([
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

        $this->assertTrue($response->success, $this->dumpClient());
        $this->assertInstanceOf(TransactionResponse::class, $response);
        $this->assertInstanceOf(Transaction::class, $response->transaction);

        return $response->transaction->id;
    }

    /**
     * @test
     * @depends processes_transactions
     */
    public function reverses_transactions(string $transaction_id)
    {
        $response = static::$CLIENT->reverse($transaction_id);

        $this->assertTrue($response->success, $this->dumpClient());
        $this->assertInstanceOf(TransactionResponse::class, $response);
        $this->assertInstanceOf(Transaction::class, $response->transaction);
    }
}
