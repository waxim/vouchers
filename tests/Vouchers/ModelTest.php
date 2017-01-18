<?php

namespace Vouchers\Tests;

require 'lib/TestGenerator.php';

use PHPUnit_Framework_TestCase as PHPUnit;

class ModelTest extends PHPUnit
{
    /**
     * Test custom generator class.
     */
    public function testCustomGeneratorAsClass()
    {
        $model = new \Vouchers\Voucher\Model([
            'code' => [
                'generator' => TestGenerator::class,
            ],
        ]);

        $voucher = new \Vouchers\Voucher([], $model);
        $this->assertSame((string) $voucher, TestGenerator::TEST_CODE);
    }

    /**
     * Test custom validator error.
     *
     * @expectedException \Vouchers\Exceptions\VoucherCodeInvalid
     */
    public function testCustomValidatorError()
    {
        $model = new \Vouchers\Voucher\Model([
            'code' => [
                'generator' => "\Vouchers\Tests\TestGenerator",
            ],
        ]);

        $voucher = new \Vouchers\Voucher(['code' => 'SOMETHING-ELSE'], $model);
    }

    /**
     * Test custom validator success.
     */
    public function testCustomValidatorSuccess()
    {
        $model = new \Vouchers\Voucher\Model([
            'code' => [
                'generator' => TestGenerator::class,
            ],
        ]);

        $voucher = new \Vouchers\Voucher(['code' => TestGenerator::TEST_CODE], $model);
        $this->assertSame((string) $voucher, TestGenerator::TEST_CODE);
    }

    /**
     * Test adding immutable fields.
     *
     * @expectedException Vouchers\Exceptions\ImmutableData
     */
    public function testAddingImmutableData()
    {
        $model = new \Vouchers\Voucher\Model([
            'created' => [
                'required'  => true,
                'immutable' => true,
            ],
        ]);

        $voucher = new \Vouchers\Voucher(['created' => '12/12/12'], $model);
        $voucher->set('created', 'something else');
    }

    /**
     * Test we can validate data.
     */
    public function testDataValidationSuccess()
    {
        $model = new \Vouchers\Voucher\Model([
            'name' => [
                'required'  => true,
            ],
            'email' => [
                'required'  => true,
            ],
        ]);

        $voucher = new \Vouchers\Voucher(['name' => 'Alan Cole', 'email' => 'me@alancole.io'], $model);
        $this->assertSame($voucher->get('name'), 'Alan Cole');
    }

    /**
     * Test we get an error when data is invalid.
     *
     * @expectedException Vouchers\Exceptions\VoucherValidationFailed
     */
    public function testDataValidationError()
    {
        $model = new \Vouchers\Voucher\Model([
            'name' => [
                'required'  => true,
            ],
            'email' => [
                'required'  => true,
            ],
        ]);

        $voucher = new \Vouchers\Voucher(['name' => 'Alan Cole'], $model);
    }
}
