<?php declare(strict_types=1);

namespace Vouchers;

class Bag extends Bag\Collection
{
    /**
     * Default validation message.
     *
     * @const string
     */
    const VALIDATION_MESSAGE = 'Validation failed.';

    /**
     * Store our validators.
     *
     * @var array
     */
    protected $validators = [];

    /**
     * We have a model?
     *
     * @var \Vouchers\Voucher\Model
     */
    protected $model = null;

    /**
     * Start a new bag.
     *
     * @param \Vouchers\Voucher\Model
     *
     * @return void
     */
    public function __construct(\Vouchers\Voucher\Model $model = null)
    {
        if ($model) {
            $this->model = $model;
        }
    }

    /**
     * Fill our bag with vouchers.
     *
     * @param int $number
     *
     * @return void
     */
    public function fill($number = 0) :void
    {
        for ($i = 0; $i < $number; $i++) {
            $this->add(new Voucher());
        }
    }

    /**
     * Pick a random voucher.
     *
     * @return Voucher $voucher
     */
    public function pick(callable $callback = null)
    {
        if ($callback) {
            return $this->pickWithCallback($callback);
        }

        return $this->values[array_rand($this->values)];
    }

    /**
     * Receive a callback and use it to pick
     * a voucher.
     *
     * @param callable $callback
     *
     * @throws NoValidVouchers
     *
     * @return Voucher $voucher
     */
    public function pickWithCallback(callable $callback) :Voucher
    {
        foreach ($this->values as $voucher) {
            if ($callback($voucher)) {
                return $voucher;
            }
        }

        throw new \Vouchers\Exceptions\NoValidVouchers();
    }

    /**
     * Add an array to our collection.
     *
     * @param array    $data
     * @param callable $callback
     *
     * @return void
     */
    public function map(array $data, callable $callback) :void
    {
        foreach ($data as $item) {
            $result = $callback($item);
            $this->add($result);
        }
    }

    /**
     * Walk through all vouchers
     * until we find a valid one.
     *
     * @param string $code
     *
     * @throws VoucherValidationFailed
     *
     * @return bool
     */
    public function pickValid() :Voucher
    {
        $voucher = null;
        foreach ($this->values as $value) {
            try {
                $result = $this->validate((string) $value);
                if ($result) {
                    $voucher = $value;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        if ($voucher == null) {
            throw new \Vouchers\Exceptions\NoValidVouchers();
        }

        return $voucher;
    }

    /**
     * Validate a code.
     *
     * @param string $code
     *
     * @throws VoucherValidationFailed
     *
     * @return bool
     */
    public function validate($code) :bool
    {
        foreach ($this->validators as $rule) {
            $callback = $rule['callback'];
            $message = $rule['message'];

            if (!$callback($code)) {
                throw new \Vouchers\Exceptions\ValidationCallbackFail($message);
            }
        }

        return true;
    }

    /**
     * Add a validation function.
     *
     * @param callable $callback
     * @param $string message
     *
     * @return void
     */
    public function validator(callable $callback, $message = self::VALIDATION_MESSAGE) :void
    {
        $rule = [
            'message'  => $message,
            'callback' => $callback,
        ];
        array_push($this->validators, $rule);
    }

    /**
     * Add a voucher to our collection.
     *
     * @param Voucher $voucher
     *
     * @return void
     */
    public function add(Voucher $voucher) :void
    {
        if ($this->model) {
            $this->model->validate($voucher->toArray());
        }

        array_push($this->values, $voucher);
    }

    /**
     * Get a voucher by code.
     *
     * @param string $code
     *
     * @return bool|Voucher
     */
    public function find($code) :bool|Voucher
    {
        foreach ($this->values as $key => $voucher) {
            if ($code == (string) $voucher) {
                return $voucher;
            }
        }

        return false;
    }
}
