<?php

namespace Vouchers\Tests;

use PHPUnit_Framework_TestCase as PHPUnit;

class BagTest extends PHPUnit
{
    /**
     * Start a bag.
     */
    public function testCanMakeABag()
    {
        $bag = new \Vouchers\Bag();
        $this->assertTrue(is_object($bag));
    }

    /**
     * Make a voucher and add it to our bag.
     */
    public function testCanAddVouchersToBag()
    {
        $voucher = new \Vouchers\Voucher();
        $bag = new \Vouchers\Bag();
        $bag->add($voucher);
        $this->assertEquals($bag->count(), 1);
    }

    /**
     * Fill a new bag with vouchers.
     */
    public function testCanFillABag()
    {
        $bag = new \Vouchers\Bag();
        $bag->fill(20);
        $this->assertEquals($bag->count(), 20);
    }

    /**
     * Fill a new bag with vouchers from a map.
     */
    public function testCanFillFromAMap()
    {
        $vouchers = [
            'My-Voucher',
            'This02',
            'Testme',
            '12-12-12',
        ];

        $bag = new \Vouchers\Bag();
        $bag->map($vouchers, function ($voucher) {
            return new \Vouchers\Voucher(['code' => $voucher]);
        });

        $this->assertSame($bag->toArray(), $vouchers);
    }

    /**
     * Test we can pick a voucher.
     */
    public function testCanPickFromABag()
    {
        $bag = new \Vouchers\Bag();
        $bag->fill(100);

        $voucher = $bag->pick();

        $this->assertTrue(is_object($voucher));
    }

    /**
     * Test we can pickValid on a bag.
     */
    public function testCanPickValidFromABag()
    {
        $bag = new \Vouchers\Bag();
        $bag->fill(100);

        $voucher = $bag->pickValid();

        $this->assertTrue(is_object($voucher));
    }

    /**
     * Test we can pick a voucher with validation callbacks.
     */
    public function testCanPickWithValidatorCallback()
    {
        $bag = new \Vouchers\Bag();
        $bag->fill(100);

        $bag->validator(function ($voucher) {
            return strlen($voucher) > 4;
        }, 'Strlen check failed.');

        $voucher = $bag->pickValid();

        $this->assertTrue(is_object($voucher));
    }

    /**
     * Test we get an error when data is invalid.
     *
     * @expectedException \Vouchers\Exceptions\NoValidVouchers
     */
    public function testCanFailToPickWithValidatorCallback()
    {
        $bag = new \Vouchers\Bag();
        $bag->fill(100);

        $a = 12;
        $b = 13;

        $bag->validator(function ($voucher) {
            return strlen($voucher) > 1000;
        }, 'Strlen check failed.');

        $voucher = $bag->pickValid();

        $this->assertTrue(is_object($voucher));
    }

    /**
     * Test we can build some vouchers
     * against a valid model.
     */
    public function testCanApplyValidModelToBag()
    {
        $model = new \Vouchers\Voucher\Model([
            'name' => [
                'required'  => true,
            ],
            'email' => [
                'required'  => true,
            ],
        ]);

        $vouchers = [
            ['code' => 'MyTestCode1', 'name' => 'Person Name', 'email' => 'person@person.tld'],
            ['code' => 'MyTestCode2', 'name' => 'Person2 Name', 'email' => 'person2@person.tld'],
            ['code' => 'MyTestCode3', 'name' => 'Person3 Name', 'email' => 'person3@person.tld'],
            ['code' => 'MyTestCode4', 'name' => 'Person4 Name', 'email' => 'person4@person.tld'],
        ];

        $bag = new \Vouchers\Bag($model);
        $bag->map($vouchers, function ($voucher) {
            return new \Vouchers\Voucher($voucher);
        });
    }

    /**
     * Test we can build some vouchers
     * against a valid model.
     *
     * @expectedException \Vouchers\Exceptions\VoucherValidationFailed
     */
    public function testInvalidModelOnBag()
    {
        $model = new \Vouchers\Voucher\Model([
            'name' => [
                'required'  => true,
            ],
            'email' => [
                'required'  => true,
            ],
        ]);

        $vouchers = [
            ['code' => 'MyTestCode1', 'name' => 'Person Name', 'email' => 'person@person.tld'],
            ['code' => 'MyTestCode2', 'name' => 'Person2 Name', 'email' => 'person2@person.tld'],
            ['code' => 'MyTestCode3', 'name' => 'Person3 Name', 'email' => 'person3@person.tld'],
            ['code' => 'MyTestCode4', 'name' => 'Person4 Name', 'semail' => 'person4@person.tld'],
        ];

        $bag = new \Vouchers\Bag($model);
        $bag->map($vouchers, function ($voucher) {
            return new \Vouchers\Voucher($voucher);
        });
    }

    /**
     * Test collections are iterable.
     */
    public function testBagCollectionIsIterable()
    {
        $bag = new \Vouchers\Bag();
        $bag->fill(10);
        $i = 0;
        foreach ($bag as $item) {
            $i++;
        }

        $this->assertSame($i, 10);
    }

    /**
     * Test voucher find.
     */
    public function testFindAVoucher()
    {
        $bag = new \Vouchers\Bag();
        $voucher = new \Vouchers\Voucher(['code' => 'MY-VOUCHER']);
        $bag->add($voucher);
        $test = $bag->find('MY-VOUCHER');
        $this->assertSame($voucher, $test);
    }

    /**
     * Test pick with callback.
     */
    public function testPickWithACallback()
    {
        $bag = new \Vouchers\Bag();
        $voucher = new \Vouchers\Voucher([
            'code'  => 'MY-Test-code',
            'owner' => 'tester',
        ]);
        $bag->add($voucher);

        $test = $bag->pick(function ($voucher) {
            return $voucher->get('owner') == 'tester';
        });

        $this->assertSame($test, $voucher);
    }

    /**
     * Test pick with callback.
     *
     * @expectedException \Vouchers\Exceptions\NoValidVouchers
     */
    public function testPickWithACallbackFails()
    {
        $bag = new \Vouchers\Bag();
        $voucher = new \Vouchers\Voucher([
            'code'  => 'MY-Test-code',
            'owner' => 'tester',
        ]);
        $bag->add($voucher);

        $test = $bag->pick(function ($voucher) {
            return $voucher->get('owner') == 'not a tester';
        });

        $this->assertSame($test, $voucher);
    }
}
