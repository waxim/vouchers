<?php

namespace Vouchers\Exceptions;

class NoValidVouchers extends \Exception
{
    public $message = "Sorry, no voucher match your validations.";
}
