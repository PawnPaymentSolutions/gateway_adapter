<?php

namespace PawnPay\Merchant\Responses;

use PawnPay\Merchant\Models\Transaction as TransModel;
use PawnPay\Merchant\Response;
use Psr\Http\Message\ResponseInterface;

class Transaction extends Response
{
    /**
     * @var \PawnPay\Merchant\Models\Transaction
     */
    public $transaction = null;

    public function __construct(ResponseInterface $response = null)
    {
        parent::__construct($response);

        if ($this->success) {
            $data              = \json_decode($this->raw_response->getBody(), true);
            $this->transaction = new TransModel($data);
        }
    }
}
