<?php

namespace PawnPay\Merchant\Responses;

use PawnPay\Merchant\Models\PaymentMethod as MethodModel;
use PawnPay\Merchant\Response;
use Psr\Http\Message\ResponseInterface;

class Method extends Response
{
    /**
     * @var \PawnPay\Merchant\Models\Method
     */
    public $method;

    public function __construct(ResponseInterface $response = null)
    {
        parent::__construct($response);

        if ($this->success) {
            $data         = \json_decode($this->raw_response->getBody(), true);
            $this->method = new MethodModel($data);
        }
    }
}
