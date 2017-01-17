<?php

namespace Vouchers\Voucher\Code;

interface GeneratorInterface
{

    /**
     * Return a voucher code.
     *
     * @return string
     */
    public function generate();

    /**
     * Validate a voucher code.
     *
     * @param string $code
     * @return bool
     */
    public function validate($code);
}
