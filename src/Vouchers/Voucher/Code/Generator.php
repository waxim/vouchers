<?php

namespace Vouchers\Voucher\Code;

class Generator implements GeneratorInterface
{

    /**
     * Pattern RegEx
     *
     * @format XXXX-XXXX-XXXX-XXXX
     * @var string
     */
    const REGEX = "/[\w\d]{4}\-[\w\d]{4}\-[\w\d]{4}\-[\w\d]{4}/";

    /**
     * Generate some semi random data.
     *
     * @return string
     */
    public function part()
    {
        return bin2hex(openssl_random_pseudo_bytes(2));
    }

    /**
     * Return a voucher code.
     *
     * @return string
     */
    public function generate()
    {
        return strtoupper(sprintf("%s-%s-%s-%s", $this->part(), $this->part(), $this->part(), $this->part()));
    }

    /**
     * Validate a voucher code
     *
     * @param string $code
     * @return bool
     */
    public function validate($code)
    {
        return (bool)preg_match($code, self::REGEX);
    }
}
