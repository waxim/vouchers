<?php declare(strict_types=1);

namespace Vouchers\Exceptions;

class VoucherValidationFailed extends \Exception
{
    public $message = 'This voucher was not valid.';
}
