<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;

try {
    $output = new BufferedOutput();
    echo "Running tenant:seed dentalsmile -vvv...\n";
    $exitCode = Artisan::call('tenant:seed', ['slug' => 'dentalsmile'], $output);
    
    $result = $output->fetch();
    echo "Exit Code: $exitCode\n";
    echo "Output:\n$result\n";
    
    file_put_contents('seeder_debug.txt', "Exit Code: $exitCode\nOutput:\n$result");
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    file_put_contents('seeder_debug.txt', "Exception: " . $e->getMessage() . "\nTrace:\n" . $e->getTraceAsString());
}
