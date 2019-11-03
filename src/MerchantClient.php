<?php

namespace PawnPay\Merchant;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * @see https://gateway.pawn-pay.com/docs/api/v1#merchant
 */
class MerchantClient
{
    /** @var string */
    protected $merchant_id;

    /** @var string */
    protected $merchant_key;

    /** @var string */
    protected $merchant_secret;

    /** @var float */
    protected $timeout = 60.0;

    /** @var array */
    protected $last_request = [];

    /** @var array */
    protected $last_response = [];

    /** @var string */
    protected $api_url;

    /** @var \GuzzleHttp\Client */
    protected $client;

    /** @var string */
    protected $user_agent = 'PawnPay-PHP-M';

    /** @var string */
    protected $version = '0.1';

    public function __construct(
        string $merchant_id,
        string $merchant_key,
        string $merchant_secret = null,
        string $api_url = 'https://gateway.pawn-pay.com/api/v1/merchant/'
    ) {
        $this->merchant_id     = $merchant_id;
        $this->merchant_key    = $merchant_key;
        $this->merchant_secret = $merchant_secret;
        $this->api_url         = $api_url;

        $this->client = new Client([
            'base_uri' => $this->api_url,
        ]);
    }

    /**
     * Check if the current credentials are valid.
     *
     * @see https://gateway.pawn-pay.com/docs/api/v1#merchant-test-auth
     *
     * @return bool
     */
    public function testAuth()
    {
        try {
            $this->request('GET', '');
        } catch (ClientException $th) {
            return false;
        }

        return 200 === $this->last_response['status'];
    }

    /**
     * Create a new payer record.
     *
     * @see https://gateway.pawn-pay.com/docs/api/v1#create-payer
     *
     * @param array $payer
     *
     * @return \PawnPay\Merchant\Response
     */
    public function createPayer(array $payer)
    {
        $payer = $this->request('POST', 'payers', $payer);

        return new Response($payer);
    }

    /**
     * Update a payer record.
     *
     * @see https://gateway.pawn-pay.com/docs/api/v1#update-payer
     *
     * @param string $payer_id
     * @param array  $payer
     *
     * @return \PawnPay\Merchant\Response
     */
    public function updatePayer(string $payer_id, array $payer)
    {
        $payer = $this->request('PATCH', "payers/{$payer_id}", $payer);

        return new Response($payer);
    }

    /**
     * Delete a new payer record.
     *
     * @see https://gateway.pawn-pay.com/docs/api/v1#update-payer
     *
     * @param string $payer_id
     *
     * @return bool
     */
    public function deletePayer(string $payer_id)
    {
        try {
            $this->request('DELETE', "payers/{$payer_id}");
        } catch (ClientException $th) {
            return false;
        }

        return 204 === $this->last_response['status'];
    }

    /**
     * Create a payment method record.
     *
     * @see https://gateway.pawn-pay.com/docs/api/v1#create-credit-method
     * @see https://gateway.pawn-pay.com/docs/api/v1#create-ach-method
     *
     * @param string $payer_id
     * @param array  $method
     *
     * @return \PawnPay\Merchant\Response
     */
    public function createMethod(string $payer_id, array $method)
    {
        $method = $this->request('POST', "payers/{$payer_id}/methods", $method);

        return new Response($method);
    }

    /**
     * Update a payment method record.
     *
     * @see https://gateway.pawn-pay.com/docs/api/v1#update-method
     *
     * @param string $method_id
     * @param array  $method
     *
     * @return \PawnPay\Merchant\Response
     */
    public function updateMethod(string $method_id, array $method)
    {
        $method = $this->request('PATCH', "methods/{$method_id}", $method);

        return new Response($method);
    }

    /**
     * Delete a payment method record.
     *
     * @see https://gateway.pawn-pay.com/docs/api/v1#delete-method
     *
     * @param string $method_id
     *
     * @return bool
     */
    public function deleteMethod(string $method_id)
    {
        $method = $this->request('DELETE', "methods/{$method_id}");

        return 204 === $this->last_response['status'];
    }

    /**
     * Get a payers payment method records.
     *
     * @see https://gateway.pawn-pay.com/docs/api/v1#payer-methods
     *
     * @param string $payer_id
     *
     * @return \PawnPay\Merchant\Response[]
     */
    public function getMethods(string $payer_id)
    {
        $res = $this->request('GET', "payers/{$payer_id}/methods");

        $methods = [];

        foreach ($res as $method) {
            $methods[] = new Response($method);
        }

        return $methods;
    }

    /**
     * Authorize a transaction.
     *
     * @see https://gateway.pawn-pay.com/docs/api/v1#auth-trans
     *
     * @param array $request
     *
     * @return \PawnPay\Merchant\Response
     */
    public function authorize(array $request)
    {
        $trans = $this->request('POST', 'transactions/authorize', $request);

        return new Response($trans);
    }

    /**
     * Capture a transaction.
     *
     * @see https://gateway.pawn-pay.com/docs/api/v1#capture-transaction
     *
     * @param string $trans_id
     * @param array  $request
     *
     * @return \PawnPay\Merchant\Response
     */
    public function capture(string $trans_id, array $request = [])
    {
        $trans = $this->request('POST', "transactions/{$trans_id}/capture", $request);

        return new Response($trans);
    }

    /**
     * Process a transaction.
     *
     * @see https://gateway.pawn-pay.com/docs/api/v1#process-transaction
     *
     * @param array $request
     *
     * @return \PawnPay\Merchant\Response
     */
    public function process(array $request = [])
    {
        $trans = $this->request('POST', 'transactions', $request);

        return new Response($trans);
    }

    /**
     * Get a transaction record.
     *
     * @see https://gateway.pawn-pay.com/docs/api/v1#get-transaction
     *
     * @param string $trans_id
     *
     * @return \PawnPay\Merchant\Response
     */
    public function getTransaction(string $trans_id)
    {
        $trans = $this->request('GET', "transactions/{$trans_id}");

        return new Response($trans);
    }

    /**
     * Reverse a transaction.
     *
     * @see https://gateway.pawn-pay.com/docs/api/v1#refund-transaction
     *
     * @param string $trans_id
     * @param array  $request
     *
     * @return \PawnPay\Merchant\Response
     */
    public function reverse(string $trans_id, array $request = [])
    {
        $trans = $this->request('POST', "transactions/{$trans_id}/reverse", $request);

        return new Response($trans);
    }

    /**
     * @param string $method
     * @param string $resource
     * @param array  $request
     * @param array  $options
     *
     * @return array
     */
    public function request(
        string $method,
        string $resource,
        $request = [],
        array $options = []
    ) {
        $options = array_merge([
            // 'allow_redirects' => false,
            'auth'            => [$this->merchant_id, $this->merchant_key],
            'headers'         => [
                'User-Agent'   => "{$this->user_agent} v{$this->version}",
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ],
            'timeout' => $this->timeout, // seconds
            'verify'  => false, // self signed certs
        ], $options);

        $options['json'] = $request;

        $this->last_request = [
            'method'  => $method,
            'url'     => $this->client->getConfig('base_uri').$resource,
            'options' => $options,
            'body'    => $request,
        ];

        $res = $this->client->request($method, $resource, $options);

        $body = \json_decode($res->getBody(), true);

        $this->last_response = [
            'status'  => $res->getStatusCode(),
            'headers' => $res->getHeaders(),
            'body'    => $body,
        ];

        return $body;
    }

    /**
     * Create a new webhook.
     *
     * @see https://gateway.pawn-pay.com/docs/api/v1#webhook-create
     *
     * @param string $event_type
     * @param string $url
     *
     * @return \PawnPay\Merchant\Response
     */
    public function createWebhook(string $event_type, string $url)
    {
        $hook = $this->request('POST', 'webhooks', [
            'event_type' => $event_type,
            'url'        => $url,
        ]);

        return new Response($hook);
    }

    /**
     * Update a webhook.
     *
     * @see https://gateway.pawn-pay.com/docs/api/v1#webhook-update
     *
     * @param string $webhook_id
     * @param string $event_type
     * @param string $url
     *
     * @return \PawnPay\Merchant\Response
     */
    public function updateWebhook(string $webhook_id, string $event_type, string $url)
    {
        $hook = $this->request('PATCH', "webhooks/{$webhook_id}", [
            'event_type' => $event_type,
            'url'        => $url,
        ]);

        return new Response($hook);
    }

    /**
     * Delete a webhook.
     *
     * @see https://gateway.pawn-pay.com/docs/api/v1#webhook-delete
     *
     * @param string $webhook_id
     *
     * @return bool
     */
    public function deleteWebhook(string $webhook_id)
    {
        $hook = $this->request('DELETE', "webhooks/{$webhook_id}");

        return 204 === $this->last_response['status'];
    }

    /**
     * Get a webhook.
     *
     * @see https://gateway.pawn-pay.com/docs/api/v1#webhook-show
     *
     * @param string $webhook_id
     *
     * @return \PawnPay\Merchant\Response
     */
    public function getWebhook(string $webhook_id)
    {
        $hook = $this->request('GET', "webhooks/{$webhook_id}");

        return new Response($hook);
    }

    /**
     * Get all webhooks for a given event type.
     *
     * @see https://gateway.pawn-pay.com/docs/api/v1#webhook-list
     *
     * @param string $event_type
     *
     * @return \PawnPay\Merchant\Response[]
     */
    public function listWebhooks(string $event_type)
    {
        $response = $this->request('GET', "webhooks/events/{$event_type}");

        $hooks = [];

        foreach ($response as $hook) {
            $hooks[] = new Response($hook);
        }

        return $hooks;
    }

    /**
     * Validate a webhook request by its signature.
     *
     * @see https://gateway.pawn-pay.com/docs/api/v1#webhooks
     *
     * @param string $timestamp
     * @param string $token
     * @param string $signature
     *
     * @return bool
     */
    public function validateWebhook(string $timestamp, string $token, string $signature)
    {
        $computed_signature = hash_hmac('sha256', $token.$timestamp, $this->merchant_secret);

        return hash_equals($computed_signature, $signature);
    }

    /**
     * GETTERS AND SETTERS.
     */

    /**
     * @return array
     */
    public function getLastRequest()
    {
        return $this->last_request;
    }

    /**
     * @return array
     */
    public function getLastResponse()
    {
        return $this->last_response;
    }

    /**
     * @return float
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @return $this
     */
    public function setTimeout(float $timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setApiUrl(string $url)
    {
        $this->api_url = $url;

        $this->client = new Client([
            'base_uri' => $this->api_url,
        ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getApiUrl()
    {
        return $this->api_url;
    }
}
