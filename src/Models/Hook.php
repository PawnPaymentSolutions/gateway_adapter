<?php

namespace PawnPay\Merchant\Models;

use PawnPay\Merchant\Fluent;

/**
 * @property int    $status
 * @property mixed  $payload
 * @property string $created_at
 */
class Hook extends Fluent
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->payload = new Fluent($this->payload);
    }
}
