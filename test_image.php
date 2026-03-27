<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Storage;

$user = User::first();
echo "Current image: " . $user->image . "\n";

$testPath = "users/test_path_" . time() . ".png";
$user->image = $testPath;
$user->save();

$user = User::first();
echo "New image: " . $user->image . "\n";
