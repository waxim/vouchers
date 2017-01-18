<?php

namespace Vouchers\Voucher;

use Vouchers\Voucher as V;

class Model
{

    /**
     * What the key for generators in model arrays?
     *
     * @var string
     */
    const GENERATOR_KEY = "generator";

    /**
     * Hold our model data.
     *
     * @var array
     */
    protected $model;

    /**
     * Custom generator?
     *
     * @var class
     */
    protected $generator;

    /**
     * Load a new model and parse it.
     *
     * @param array $model
     * @return void
     */
    public function __construct(array $model)
    {
        $this->parseModel($model);
    }

    /**
     * Actually parse the mode and set our generator
     * if we have one.
     *
     * @param  array  $model
     * @return void
     */
    public function parseModel(array $model)
    {
        $this->model = $model;
        if (isset($this->model[V::VOUCHER_CODE_KEY])) {
            if (isset($this->model[V::VOUCHER_CODE_KEY][self::GENERATOR_KEY])) {
                $this->generator = $this->model[V::VOUCHER_CODE_KEY][self::GENERATOR_KEY];
            }
        }

        foreach ($this->model as $key => $item) {
            if ($key == V::VOUCHER_CODE_KEY) {
                continue;
            }

            if (isset($item['immutable'])) {
                throw new \Vouchers\Exceptions\ImmutableData();
            }

            $this->model[$key] = $item;
        }
    }

    /**
     * Get our custom generator
     *
     * @return \Vouchers\Voucher\Code\GeneratorInterface
     */
    public function getGenerator()
    {
        return $this->generator ? new $this->generator : false;
    }

    /**
     * Validate data against model
     *
     * @param array $data
     * @return bool
     */
    public function validate(array $data = [], $voucher = null)
    {
        foreach ($this->model as $key => $rules) {
            if ($key == V::VOUCHER_CODE_KEY) {
                continue;
            }

            if (isset($rules['required']) && !isset($data[$key])) {
                throw new \Vouchers\Exceptions\VoucherValidationFailed();
            }
        }

        return true;
    }
}
