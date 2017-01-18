<?php

namespace Vouchers\Exceptions;

class VoucherValidationFailed extends \Exception
{
    public $message = 'This voucher was not valid.';
}
