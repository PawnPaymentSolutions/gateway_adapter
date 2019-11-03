<?php

namespace PawnPay\Merchant\Models;

use PawnPay\Merchant\Fluent;

/**
 * @property string                           $id
 * @property string                           $name
 * @property string                           $email
 * @property string                           $phone
 * @property \PawnPay\Merchant\Models\Address $address
 */
class Payer extends Fluent
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->address = new Address($this->address ?: []);
    }
}
