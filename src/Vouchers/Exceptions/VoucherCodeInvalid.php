<?php

namespace Vouchers\Exceptions;

class VoucherCodeInvalid extends \Exception
{
    public $message = 'The voucher code was not valid.';
}
