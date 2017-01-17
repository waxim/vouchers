<?php

namespace Vouchers\Exceptions;

class ImmutableData extends \Exception
{
    public $message = "The field your are trying to update is immutable meaning it can not be changed.";
}
