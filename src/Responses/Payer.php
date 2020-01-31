<?php

namespace PawnPay\Merchant\Responses;

use PawnPay\Merchant\Models\Payer as PayerModel;
use PawnPay\Merchant\Response;
use Psr\Http\Message\ResponseInterface;

class Payer extends Response
{
    /**
     * @var \PawnPay\Merchant\Models\Payer
     */
    public $payer = null;

    public function __construct(ResponseInterface $response = null)
    {
        parent::__construct($response);

        if ($this->success) {
            $data        = \json_decode($this->raw_response->getBody(), true);
            $this->payer = new PayerModel($data);
        }
    }
}
