<?php

namespace PawnPay\Merchant\Responses;

use PawnPay\Merchant\Models\Transaction;
use PawnPay\Merchant\Response;
use Psr\Http\Message\ResponseInterface;

class BatchTransactions extends Response
{
    /**
     * @var \PawnPay\Merchant\Models\Transaction[]
     */
    public $transactions = [];

    public function __construct(ResponseInterface $response = null)
    {
        parent::__construct($response);

        $data = [];

        if ($this->success) {
            $data = \json_decode($this->raw_response->getBody(), true);
        }

        foreach ($data as $tran) {
            $this->transactions[] = new Transaction($tran);
        }
    }
}
