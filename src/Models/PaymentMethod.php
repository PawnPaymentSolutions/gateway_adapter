<?php

namespace PawnPay\Merchant\Models;

use PawnPay\Merchant\Fluent;

/**
 * @property string                           $id
 * @property string                           $name
 * @property string                           $type
 * @property string                           $sub_type
 * @property string                           $account_name
 * @property string                           $last_four
 * @property string                           $exp
 * @property string                           $payer_id
 * @property \PawnPay\Merchant\Models\Address $address
 */
class PaymentMethod extends Fluent
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->address = new Address($this->address ?: []);
    }
}
