# PawnPay Merchant API
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)

## About
An API adapter for [gateway.pawn-pay.com](gateway.pawn-pay.com)

## Table of Contents
- [Installation](#installation)
- [Usage](#usage)
    - [Initialize](#initialize)
    - Payers
        - [Create Payer](#create-payer)
        - [Update Payer](#update-payer)
        - [Delete Payer](#delete-payer)
        - [Get Payer Methods](#get-payment-methods)
    - Payment Methods
        - [Create Method](#create-payment-method)
        - [Update Method](#update-payment-method)
        - [Delete Method](#delete-payment-method)
    - Transactions
        - [Authorize Transaction](#authorize-transaction)
        - [Capture Transaction](#capture-transaction)
        - [Process Transaction](#process-transaction)
        - [Reverse Transaction](#reverse-transaction)
        - [Get Transaction](#get-transaction)
    - Webhooks
        - [Create Webhook](#create-webhook)
        - [Update Webhook](#update-webhook)
        - [Get Webhook](#get-webhook)
        - [Delete Webhook](#delete-webhook)
        - [List Webhooks](#list-webhooks)
        - [Validate Webhook](#validate-webhook)
    - [Debugging](#debugging)
- [Testing](#testing)
- [License](#license)

## Installation
Require the `pawnpay/merchant_api` package in your composer.json:
```sh
$ composer require pawnpay/merchant_api
```

## Usage
For a full list of the API specs please see our [API Documentation](https://dev.gateway.pawn-pay.com/docs/api/v1#merchant)
### Initialize
```php
<?php
require_once 'vendor/autoload.php';

use PawnPay\Merchant\MerchantClient;

$client = new MerchantClient(
    'merchant_id',
    'merchant_key',
    'merchant_secret',
    'https://gateway.pawn-pay.com/api/v1/merchant/'
);
```
### Create Payer
```php
$payer = $client->createPayer([
    'name'    => 'Johnny Test',
    'email'   => 'j.test@example.com',
    'phone'   => '+19544941234',
    'address' => [
        'street'  => '1234 Test St.',
        'city'    => 'Townsville',
        'state'   => 'GA',
        'postal'  => 30380,
        'country' => 'USA',
    ],
]);

$payer_id = $payer->id;
```
### Update Payer
```php
$payer = $client->updatePayer($payer_id, [
    'name'    => 'Johnny Test',
    'email'   => 'j.test@example.com',
    'phone'   => '+19544941234',
    'address' => [
        'street'  => '1234 Test St.',
        'city'    => 'Townsville',
        'state'   => 'GA',
        'postal'  => 30380,
        'country' => 'USA',
    ],
]);
```
### Delete Payer
```php
$success = $client->deletePayer($payer_id);
```
### Create Payment Method
```php
$method = $client->createMethod($payer_id, [
    'name'         => 'Test Visa',
    'type'         => 'credit',
    'sub_type'     => 'visa',
    'account_name' => 'Test Cardholder',
    'account'      => '4242424242424242',
    'exp'          => '0124',
    'cvv'          => '123',
    'address' => [
        'street'  => '1234 Test St.',
        'city'    => 'Townsville',
        'state'   => 'GA',
        'postal'  => 30380,
        'country' => 'USA',
    ],
]);
$method_id = $method->id;
```
### Update Payment Method
```php
$method = $client->updateMethod($method_id, [
    'name'    => 'Test Update',
    'address' => [
        'street' => '43211 Test St.',
    ],
]);
```
### Get Payment Methods
```php
$methods = $client->getMethods($payer_id);
```
### Delete Payment Method
```php
$success = $client->deleteMethod($method_id);
```
### Authorize Transaction
```php
$transaction = $client->authorize([
    'amount'   => 1134,
    'currency' => 'USD',
    'payer'    => [
        'name'    => 'Johnny Test',
        'email'   => 'j.test@example.com',
        'phone'   => '+19544941234',
        'address' => [
            'street'  => '1234 Test St.',
            'city'    => 'Townsville',
            'state'   => 'GA',
            'postal'  => 30380,
            'country' => 'USA',
        ],
    ],
    'payment_method' => [
        'name'         => 'Test Visa',
        'type'         => 'credit',
        'sub_type'     => 'visa',
        'account_name' => 'Test Cardholder',
        'account'      => '4242424242424242',
        'exp'          => '0124',
        'cvv'          => '123',
        'address'      => [
            'street'  => '1234 Test St.',
            'city'    => 'Townsville',
            'state'   => 'GA',
            'postal'  => 30380,
            'country' => 'USA',
        ],
    ],
    'invoice' => [
        'number'      => 'I-0001',
        'total'       => 1134,
        'description' => 'Invoice description',
        'items'       => [
            [
                'name'        => 'Invoice item',
                'description' => 'Invoice item description',
                'quantity'    => 1,
                'price'       => 1134,
            ],
        ],
        'discounts' => [],
    ],
]);

$trans_id = $transaction->id;
```
### Capture Transaction
```php
$transaction = $client->capture($trans_id);
```
### Process Transaction
```php
$transaction = $client->process([
    'amount'   => 1134,
    'currency' => 'USD',
    'payer'    => [
        'name'    => 'Johnny Test',
        'email'   => 'j.test@example.com',
        'phone'   => '+19544941234',
        'address' => [
            'street'  => '1234 Test St.',
            'city'    => 'Townsville',
            'state'   => 'GA',
            'postal'  => 30380,
            'country' => 'USA',
        ],
    ],
    'payment_method' => [
        'name'         => 'Test Visa',
        'type'         => 'credit',
        'sub_type'     => 'visa',
        'account_name' => 'Test Cardholder',
        'account'      => '4242424242424242',
        'exp'          => '0124',
        'cvv'          => '123',
        'address'      => [
            'street'  => '1234 Test St.',
            'city'    => 'Townsville',
            'state'   => 'GA',
            'postal'  => 30380,
            'country' => 'USA',
        ],
    ],
    'invoice' => [
        'number'      => 'I-0001',
        'total'       => 1134,
        'description' => 'Invoice description',
        'items'       => [
            [
                'name'        => 'Invoice item',
                'description' => 'Invoice item description',
                'quantity'    => 1,
                'price'       => 1134,
            ],
        ],
        'discounts' => [],
    ],
]);

$trans_id = $transaction->id;
```
### Reverse Transaction
```php
$transaction = $client->reverse($trans_id);
```
### Get Transaction
```php
$transaction = $client->getTransaction($trans_id);
```
### Create Webhook
```php
$webhook = $client->createWebhook(
    'transaction.created',
    'https://www.example.com/webhooks'
);

$hook_id = $webhook->id;
```
### Update Webhook
```php
$webhook = $client->updateWebhook(
    $hook_id,
    'transaction.created',
    'https://www.example.com/webhooks'
);
```
### Get Webhook
```php
$webhook = $client->getWebhook($hook_id);
```
### Delete Webhook
```php
$success = $client->deleteWebhook($hook_id);
```
### List Webhooks
```php
$webhooks = $client->listWebhooks('transaction.created');
```
### Validate Webhook
```php
$timestamp         = $_SERVER['HTTP_TIMESTAMP'];
$token             = $_SERVER['HTTP_TOKEN'];
$request_signature = $_SERVER['HTTP_SIGNATURE'];

$valid = $client->validateWebhook($timestamp, $token, $request_signature);
```
### Debugging
The last request and response are stored as arrays in the MerchantClient instance.
```php
$last_request = $client->getLastRequest();
$last_response = $client->getLastResponse();
```

## Testing
Install the dev-dependencies and put your testing credentials in a .env file according to .env.example
```env
API_URL=https://gateway.pawn-pay.com/api/v1/merchant/
MERCHANT_ID=testing
MERCHANT_KEY=testing1234
MERCHANT_SECRET=SECRET_BOIZ
```
Then run
```sh
$ composer test
```

## License
This package is released under the MIT License. See the bundled LICENSE file for details.