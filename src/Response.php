<?php

namespace PawnPay\Merchant;

use Psr\Http\Message\ResponseInterface;

class Response
{
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $raw_response;

    /**
     * @var bool
     */
    public $success = false;

    /**
     * @var string|null
     */
    public $message = null;

    /**
     * @var array|null
     */
    public $errors = null;

    public function __construct(ResponseInterface $response = null)
    {
        $this->raw_response = $response;

        if (!$response) {
            return;
        }

        $this->success = (2 == (int) ($response->getStatusCode() / 100)); // 200 status codes

        if (!$this->success) {
            // Populate message and errors
            $body          = \json_decode($response->getBody(), true);
            $this->message = array_key_exists('message', $body) ? $body['message'] : null;
            $this->errors  = array_key_exists('errors', $body) ? $body['errors'] : null;
        }
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getRawResponse()
    {
        return $this->raw_response;
    }
}
