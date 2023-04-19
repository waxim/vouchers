<?php declare(strict_types=1);

namespace Vouchers\Voucher\Code;

interface GeneratorInterface
{
    /**
     * Return a voucher code.
     *
     * @return string
     */
    public function generate() :string;

    /**
     * Validate a voucher code.
     *
     * @param string $code
     *
     * @return bool
     */
    public function validate(string $code) :bool;
}
