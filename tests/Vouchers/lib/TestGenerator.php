<?php

namespace Vouchers\Tests;

use Vouchers\Voucher\Code\GeneratorInterface as Generator;

class TestGenerator implements Generator
{
    const TEST_CODE = 'VOUCHER-TEST-CODE';

    public function generate()
    {
        return self::TEST_CODE;
    }

    public function validate($code)
    {
        return $code == self::TEST_CODE;
    }
}
