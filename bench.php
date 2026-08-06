<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$start = microtime(true);
echo "Count: " . \App\Models\Visitor::count() . "\n";
echo "Time: " . (microtime(true) - $start) . "s\n";
