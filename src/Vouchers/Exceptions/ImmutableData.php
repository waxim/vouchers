<?php declare(strict_types=1);

namespace Vouchers\Exceptions;

class ImmutableData extends \Exception
{
    public $message = 'The field your are trying to update is immutable meaning it can not be changed.';
}
