<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

echo "ContactLead columns: " . json_encode(Schema::getColumnListing('contact_leads')) . "\n";
echo "Program columns: " . json_encode(Schema::getColumnListing('programs')) . "\n";
echo "Plan columns: " . json_encode(Schema::getColumnListing('plans')) . "\n";
echo "Order columns: " . json_encode(Schema::getColumnListing('orders')) . "\n";
echo "Membership columns: " . json_encode(Schema::getColumnListing('memberships')) . "\n";
echo "Service columns: " . json_encode(Schema::getColumnListing('services')) . "\n";
