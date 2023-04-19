<?php declare(strict_types=1);

namespace Vouchers\Exceptions;

class VoucherCodeInvalid extends \Exception
{
    public $message = 'The voucher code was not valid.';
}
