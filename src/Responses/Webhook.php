<?php

namespace PawnPay\Merchant\Responses;

use PawnPay\Merchant\Models\Webhook as WebhookModel;
use PawnPay\Merchant\Response;
use Psr\Http\Message\ResponseInterface;

class Webhook extends Response
{
    /**
     * @var \PawnPay\Merchant\Models\Webhook
     */
    public $webhook = null;

    public function __construct(ResponseInterface $response = null)
    {
        parent::__construct($response);

        if ($this->success) {
            $data          = \json_decode($this->raw_response->getBody(), true);
            $this->webhook = new WebhookModel($data);
        }
    }
}
