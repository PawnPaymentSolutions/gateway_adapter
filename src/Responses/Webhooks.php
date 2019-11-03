<?php

namespace PawnPay\Merchant\Responses;

use PawnPay\Merchant\Models\Webhook as WebhookModel;
use PawnPay\Merchant\Response;
use Psr\Http\Message\ResponseInterface;

class Webhooks extends Response
{
    /**
     * @var \PawnPay\Merchant\Models\Webhook[]
     */
    public $webhooks = [];

    public function __construct(ResponseInterface $response = null)
    {
        parent::__construct($response);

        $data = [];

        if ($this->success) {
            $data = \json_decode($this->raw_response->getBody(), true);
        }

        foreach ($data as $webhook) {
            $this->webhooks[] = new WebhookModel($webhook);
        }
    }
}
