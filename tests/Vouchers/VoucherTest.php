<?php

namespace Vouchers\Tests;

use PHPUnit_Framework_TestCase as PHPUnit;

class VoucherTest extends PHPUnit
{
    /**
     * Make a voucher
     */
    public function testCanMakeAVoucher()
    {
        $voucher = new \Vouchers\Voucher();
        $this->assertTrue(is_object($voucher));
    }

    /**
     * Test generated code
     */
    public function testCanMakeAVoucherAndGenerateCode()
    {
        $voucher = new \Vouchers\Voucher();
        $this->assertRegExp("/[\w\d]{4}\-[\w\d]{4}\-[\w\d]{4}\-[\w\d]{4}/", (string)$voucher);
    }

    /**
     * Test provided code
     */
    public function testCanMakeAVoucherAndSetACode()
    {
        $code = 'ALAN-COLE-CODE-TEST';
        $voucher = new \Vouchers\Voucher(['code' => $code]);
        $this->assertSame((string)$voucher, $code);
    }

    public function testDataGetterAndSetter()
    {
        $voucher = new \Vouchers\Voucher();
        $voucher->set("used", false);
        $this->assertFalse($voucher->get("used"));
    }

    /**
     * @expectedException Vouchers\Exceptions\ImmutableData
     */
    public function testCodeIsImmutable()
    {
        $voucher = new \Vouchers\Voucher();
        $voucher->set("code", "Something Else");
    }
}
