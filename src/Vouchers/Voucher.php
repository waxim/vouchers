<?php declare(strict_types=1);

namespace Vouchers;

use Vouchers\Voucher\Code\GeneratorInterface;

class Voucher
{
    /**
     * What our 'code' called.
     *
     * @var string
     */
    const VOUCHER_CODE_KEY = 'code';

    /**
     * Somewhere to hold data.
     *
     * @var array
     */
    private $data = [];

    /**
     * Immutable data keys.
     *
     * @var array
     */
    private $immutable = [self::VOUCHER_CODE_KEY];

    /**
     * Which generator to use?
     *
     * @var \Vouchers\Voucher\Code\GeneratorInterface
     */
    private $generator = \Vouchers\Voucher\Code\Generator::class;

    /**
     * Building a new voucher.
     *
     * @param array                  $data  Data to use for this voucher
     * @param Vouchers\Voucher\Model $Model A model to validate the model against
     *
     * @return void
     */
    public function __construct(array $data = [], Voucher\Model $model = null)
    {
        $this->parseData($data, $model);
    }

    /**
     * ToDo: DRY this and reduce complixity.
     *
     * Parse our data to build voucher
     *
     * @param array                  $data  Data to use for this voucher
     * @param Vouchers\Voucher\Model $Model A model to validate the model against
     *
     * @return void
     */
    private function parseData(array $data = [], Voucher\Model $model = null) :void
    {
        // Validate Model
        if ($model) {
            $this->processDataWithModel($data, $model);
        }

        $this->setArrayAsData($data);
        $this->generateVoucherCodeIfEmpty();
    }

    /**
     * Set an array as data.
     *
     * @param $data
     *
     * @return void
     */
    private function setArrayAsData(array $data) :void
    {
        $this->data = array_merge($data, $this->data);
    }

    /**
     * generate voucher code if empty.
     *
     * @return void
     */
    private function generateVoucherCodeIfEmpty() :void
    {
        $this->data[self::VOUCHER_CODE_KEY] = isset($this->data[self::VOUCHER_CODE_KEY]) ?
            $this->data[self::VOUCHER_CODE_KEY] :
            $this->generate();
    }

    /**
     * Check data and model.
     *
     * @param $model
     * @param $data
     *
     * @return void
     */
    private function processDataWithModel(array $data, Voucher\Model $model) :void
    {
        $model->validate($data);
        $this->data = array_merge($this->data, $data);

        // Validate Code.
        $modelGenerator = $model->getGenerator();

        if ($modelGenerator) {
            if (isset($data[self::VOUCHER_CODE_KEY])) {
                if (!$modelGenerator->validate($data[self::VOUCHER_CODE_KEY])) {
                    throw new \Vouchers\Exceptions\VoucherCodeInvalid();
                } else {
                    $this->data[self::VOUCHER_CODE_KEY] = $data[self::VOUCHER_CODE_KEY];
                }
            } else {
                $this->data[self::VOUCHER_CODE_KEY] = $modelGenerator->generate();
            }
        }
    }

    /**
     * Runs our gernator and gets a code.
     *
     * @return string
     */
    public function generate() :string
    {
        $generator = new $this->generator();

        return $generator->generate();
    }

    /**
     * Get a data value.
     *
     * @param $key
     *
     * @return mixed
     */
    public function get($key) :?string
    {
        return array_key_exists($key, $this->data) ? $this->data[$key] : null;
    }

    /**
     * Get set a data value.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed $value
     */
    public function set($key, $value) :?string
    {
        if (in_array($key, $this->immutable)) {
            throw new \Vouchers\Exceptions\ImmutableData();
        }

        $this->data[$key] = $value;

        return $value;
    }

    /**
     * Static function to get generator class.
     *
     * @return GeneratorInterface
     */
    public function getGenerator() :GeneratorInterface
    {
        return $this->generator;
    }

    /**
     * Add immutable keys.
     *
     * @param string $key
     *
     * @return void
     */
    public function addImmutableKey($key) :void
    {
        array_push($this->immutable, $key);
    }

    /**
     * What to do when cast to sting.
     *
     * @return string
     */
    public function __toString() :string
    {
        return $this->data[self::VOUCHER_CODE_KEY];
    }

    /**
     * Get all our data as an array.
     *
     * @return array
     */
    public function toArray() :array
    {
        return $this->data;
    }

    /**
     * Add a magic call to get and set.
     * 
     * @param string $method
     * @param array  $args
     * 
     * @return mixed
     */
    public function __call($method, $args)
    {
        # if we start with get
        if (substr($method, 0, 3) == 'get') {
            $key = lcfirst(substr($method, 3));

            # split the key at its capital letters
            $key = preg_split('/(?=[a-Z])/', $key);
            
            # join the key with underscores
            $key_string = "";
            foreach($key as $k => $v) {
                $key_string .= $v;
                if($k < count($key) - 1) {
                    $key_string .= "_";
                }
            }

            return $this->get($key_string);
        } 

        # if we start with set
        if (substr($method, 0, 3) == 'set') {
            $key = lcfirst(substr($method, 3));

            # split the key at its capital letters
            $key = preg_split('/(?=[a-Z])/', $key);

            # join the key with underscores
            $key_string = "";
            foreach ($key as $k => $v) {
                $key_string .= $v;
                if ($k < count($key) - 1) {
                    $key_string .= "_";
                }
            }

            return $this->set($key_string, $args[0]);
        }
    }
}
