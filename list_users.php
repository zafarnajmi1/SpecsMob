<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

$users = User::all(['id', 'name', 'image']);
foreach ($users as $user) {
    echo $user->id . ': ' . $user->name . ' -> ' . ($user->image ?? 'NULL') . "\n";
}
