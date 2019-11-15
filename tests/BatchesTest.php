<?php

namespace Tests;

use PawnPay\Merchant\Models\Batch;
use PawnPay\Merchant\Models\Transaction;
use PawnPay\Merchant\Responses\Batches;
use PawnPay\Merchant\Responses\BatchTransactions;

class BatchesTest extends TestCase
{
    /**
     * @test
     */
    public function gets_batches()
    {
        $response = static::$CLIENT->listBatches();

        $last_response = self::$CLIENT->getLastResponse();

        $this->assertEquals(200, $last_response['status'], $this->dumpClient());
        $this->assertInstanceOf(Batches::class, $response);
        if (!empty($response->batches)) {
            $this->assertInstanceOf(Batch::class, $response->batches[0]);
        }
    }

    /**
     * if you got a batch you can test it by adding @test.
     */
    public function gets_batch()
    {
        $batch_id = 1234;

        $response = self::$CLIENT->getBatch($batch_id);

        $last_response = self::$CLIENT->getLastResponse();

        $this->assertEquals(200, $last_response['status'], $this->dumpClient());
        $this->assertInstanceOf(BatchTransactions::class, $response);
        if (!empty($response->transactions)) {
            $this->assertInstanceOf(Transaction::class, $response->transactions[0]);
        }
    }
}
