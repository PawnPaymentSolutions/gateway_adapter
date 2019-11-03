<?php

namespace PawnPay\Merchant;

use Psr\Http\Message\ResponseInterface;

class Response
{
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $raw_response;

    public $success = false;

    public function __construct(ResponseInterface $response = null)
    {
        $this->raw_response = $response;

        if (!$response) {
            return;
        }

        $this->success = (2 == (int) ($response->getStatusCode() / 100)); // 200 status codes
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getRawResponse()
    {
        return $this->raw_response;
    }
}
