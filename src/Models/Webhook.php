<?php

namespace PawnPay\Merchant\Models;

use PawnPay\Merchant\Fluent;

/**
 * @property string                             $id
 * @property string                             $event_type
 * @property string                             $url
 * @property \PawnPay\Merchant\Models\Hook|null $last_hook
 */
class Webhook extends Fluent
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if ($this->last_hook) {
            $this->last_hook = new Hook($this->last_hook);
        }
    }
}
