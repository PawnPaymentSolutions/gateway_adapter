<?php

namespace PawnPay\Merchant\Models;

use PawnPay\Merchant\Fluent;

/**
 * @property string                                 $number
 * @property int                                    $total
 * @property string                                 $description
 * @property \PawnPay\Merchant\Models\InvoiceItem[] $items
 * @property \PawnPay\Merchant\Models\InvoiceItem[] $discounts
 */
class Invoice extends Fluent
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $items = [];
        foreach ($this->items as $item) {
            $items[] = new InvoiceItem($item);
        }
        $this->items = $items;

        $discounts = [];
        foreach ($this->discounts as $discount) {
            $discounts[] = new InvoiceItem($discount);
        }
        $this->discounts = $discounts;
    }
}
