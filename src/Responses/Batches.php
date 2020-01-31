<?php

namespace PawnPay\Merchant\Responses;

use PawnPay\Merchant\Models\Batch;
use PawnPay\Merchant\Response;
use Psr\Http\Message\ResponseInterface;

class Batches extends Response
{
    /**
     * @var \PawnPay\Merchant\Models\Batch[]
     */
    public $batches = null;

    public function __construct(ResponseInterface $response = null)
    {
        parent::__construct($response);

        if ($this->success) {
            $this->batches = [];
            $data          = \json_decode($this->raw_response->getBody(), true);
            foreach ($data as $batch) {
                $this->batches[] = new Batch($batch);
            }
        }
    }
}
