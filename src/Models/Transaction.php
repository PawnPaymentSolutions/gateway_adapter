<?php

namespace PawnPay\Merchant\Models;

use PawnPay\Merchant\Fluent;

/**
 * @property string                           $id
 * @property int                              $amount
 * @property string                           $description
 * @property string                           $status
 * @property string                           $captured_at
 * @property string                           $voided_at
 * @property string                           $refunded_at
 * @property string                           $settled_at
 * @property string                           $payer_id
 * @property string                           $method_id
 * @property float                            $charge_rate
 * @property int                              $trans_fee
 * @property int                              $cost
 * @property int                              $net
 * @property \PawnPay\Merchant\Models\Invoice $invoice
 */
class Transaction extends Fluent
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->invoice = new Invoice($this->invoice ? $this->invoice->toArray() : []);
    }
}
