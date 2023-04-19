<?php declare(strict_types=1);

namespace Vouchers\Exceptions;

class NoValidVouchers extends \Exception
{
    public $message = 'Sorry, no voucher match your validations.';
}
