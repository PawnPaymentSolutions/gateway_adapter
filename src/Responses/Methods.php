<?php

namespace PawnPay\Merchant\Responses;

use PawnPay\Merchant\Models\PaymentMethod as MethodModel;
use PawnPay\Merchant\Response;
use Psr\Http\Message\ResponseInterface;

class Methods extends Response
{
    /**
     * @var \PawnPay\Merchant\Models\PaymentMethod[]
     */
    public $methods = [];

    public function __construct(ResponseInterface $response = null)
    {
        parent::__construct($response);

        $data = [];

        if ($this->success) {
            $data = \json_decode($this->raw_response->getBody(), true);
        }

        foreach ($data as $method) {
            $this->methods[] = new MethodModel($method);
        }
    }
}
