<?php

require __DIR__ . '/vendor/autoloader.php';

use App\A\A;
use App\B\B;

$a = new A();
$b = new B();

echo $a->getName();
echo '<hr>';
echo $b->getName();
