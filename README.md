# Voucher Generation & Validation
Generate vouchers and check them.

# Kitchen Sink

Generate any voucher

```php
$voucher = new Discovery\Vouchers\Voucher();
print $voucher; // DFHS-JERJ-KILP-SDSA
```

Seed existing vouchers, get new one.

```php
$bag = new Discovery\Vouchers\Bag();
foreach ($vouchers as $voucher) {
    $bag->add(new Discovery\Vouchers\Voucher($voucher));
}

$voucher = new Discovery\Vouchers\Voucher(null, $bag);
print $voucher // UNIQ-VOUC-HERM-ADEO
```
or you can ask the bag for a new one.

```php
$voucher = $bag->create();
print $voucher // UNIQ-VOUC-HERM-ADEO

```

Pick a voucher from the bag

```php
$bag = new Discovery\Vouchers\Bag();
foreach ($vouchers as $voucher) {
    $bag->add(new Discovery\Vouchers\Voucher($voucher));
}

$voucher = $bag->pick() // picks any random voucher
```

You can also pass a callback to pick to "validate" its selection.

```php
$voucher = $bag->pick(function($voucher){
    return $voucher->used !== true;
});
```

If the bag has validators, you can ask pick to check those too.

```php
$voucher = $bag->pickValid();
```

If you have a voucher code you want to validate.

```php
$bag = new Discovery\Vouchers\Bag();
foreach ($vouchers as $voucher) {
    $bag->add(new Discovery\Vouchers\Voucher($voucher));
}

$bag->validate("DFHS-JERJ-KILP-SDSA");
```

You can add additional validators as a callback that account `$voucher`

```php
$bag = new Discovery\Vouchers\Bag();
foreach ($vouchers as $voucher) {
    $bag->add(new Discovery\Vouchers\Voucher($voucher));
}

// A validation to check voucher isn't expired.
$bag->validator(function($voucher){
    return new DateTime($voucher->expires) > new DateTime();
}, "Sorry, that voucher has expired");


$bag->validate("DFHS-JERJ-KILP-SDSA");

```

If you wish you can pass values to validate that will be passed to all subsiquent validators.

```php
$bag = new Discovery\Vouchers\Bag();
foreach ($vouchers as $voucher) {
    $bag->add(new Discovery\Vouchers\Voucher($voucher));
}

// A validation to check voucher isn't expired.
$bag->validator(function($voucher, $user){
    return $voucher->user == $user->id;
}, "Sorry, this voucher does not belong to you.");

$bag->validate("DFHS-JERJ-KILP-SDSA", $user);

```

You can update most of the values for vouchers in bags.

```php
$bag = new Discovery\Vouchers\Bag();
foreach ($vouchers as $voucher) {
    $bag->add(new Discovery\Vouchers\Voucher($voucher));
}

$voucher = $bag->pick() // picks any random voucher

$voucher->set("used", true);
```

Bags are itterable as well. So getting bag information back out is easy.

```php
foreach ($bag as $voucher) {
    // save $voucher to a db?
}
```

You can also set options for voucher attributes on a bag.

```php
$model = new Discovery\Vouchers\Voucher\Model([
    'code' => [
        'required'  => true,
        'immutable' => true
    ],
    'assigned' => [
        'required' => true,
        'default'   => function() { return DateTime(); }
    ],
    'used' => [
        'required' => true,
        'default'   => false
    ]
]);

$bag = new Discovery\Vouchers\Bag($model);
```
Any attribute called `code` will be required and immutable by default. Anything immutable will not be editable by `set()` however as the object is not protected direct overriding is possible.

Optionally you can pass a model as the 3rd option to a plain voucher, the model will then only apply to said voucher.

```php
$voucher = new Discovery\Vouchers\Voucher(null, null, $model);
```

Fill an empty bag.

```php
$bag = new Discovery\Vouchers\Bag($model);
$bag->fill(1000);

$csv = fopen('vouchers.csv', 'w');

foreach ($bag as $voucher) {
    fputcsv($csv, $voucher);
}

fclose($csv);
```
