<?php

namespace Database\Seeders;

use App\Models\Deal;
use Illuminate\Database\Seeder;

class DealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $deals = [
            [
                'title' => 'Samsung Galaxy Watch8',
                'slug' => 'samsung-galaxy-watch8-deal-amazon-us',
                'link' => 'https://www.amazon.com/dp/B0F7PZNZQD',
                'image_url' => 'https://m.media-amazon.com/images/I/616KEp7qQvL._AC_SX466_.jpg',
                'price' => '$ 279.99',
                'original_price' => '$ 379.99',
                'discount_percent' => '16.7%',
                'store_name' => 'Amazon US',
                'store_logo' => 'https://fdn.gsmarena.com/imgroot/static/stores/amazon-com1.png',
                'region' => 'United States',
                'memory' => '32GB 2GB RAM',
                'description' => 'Samsung Galaxy Watch 8 (2025) 44mm Bluetooth Smartwatch, Cushion Design, Fitness Tracker, Sleep Coaching.',
            ],
            [
                'title' => 'Apple iPhone 16e',
                'slug' => 'apple-iphone-16e-deal-amazon-uk',
                'link' => 'https://www.amazon.co.uk/dp/B0DXRFK8VS',
                'image_url' => 'https://m.media-amazon.com/images/I/31W+GSEQNiL._SL500_.jpg',
                'price' => '£ 599.00',
                'original_price' => '£ 649.00',
                'discount_percent' => '7.7%',
                'store_name' => 'Amazon UK',
                'store_logo' => 'https://fdn.gsmarena.com/imgroot/static/stores/amazon-uk1.png',
                'region' => 'United Kingdom',
                'memory' => '256GB 8GB RAM',
                'description' => 'Apple iPhone 16e 256GB: Built for Apple Intelligence, A18 Chip, long battery life, 48MP camera.',
            ],
            [
                'title' => 'Google Pixel 9 Pro',
                'slug' => 'google-pixel-9-pro-deal-bestbuy',
                'link' => '#',
                'image_url' => 'https://fdn.gsmarena.com/imgroot/news/24/08/google-pixel-9-pro-xl-review/lifestyle/-1200w5/gsmarena_004.jpg',
                'price' => '$ 899.00',
                'original_price' => '$ 999.00',
                'discount_percent' => '10%',
                'store_name' => 'Best Buy',
                'store_logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f5/Best_Buy_Logo.svg/2560px-Best_Buy_Logo.svg.png',
                'region' => 'United States',
                'memory' => '128GB',
                'description' => 'Great deal on the latest Pixel flagship with top-tier camera performance.',
            ],
            [
                'title' => 'Xiaomi 14 Ultra',
                'slug' => 'xiaomi-14-ultra-deal-eu',
                'link' => '#',
                'image_url' => 'https://fdn.gsmarena.com/imgroot/news/24/02/xiaomi-14-ultra-review/lifestyle/-1200w5/gsmarena_005.jpg',
                'price' => '€ 1199.00',
                'original_price' => '€ 1499.00',
                'discount_percent' => '20%',
                'store_name' => 'Amazon DE',
                'store_logo' => 'https://fdn.gsmarena.com/imgroot/static/stores/amazon-de1.png',
                'region' => 'Europe',
                'memory' => '512GB 16GB RAM',
                'description' => 'Photography powerhouse now at a significantly reduced price.',
            ],
            [
                'title' => 'OnePlus 13',
                'slug' => 'oneplus-13-deal-intl',
                'link' => '#',
                'image_url' => 'https://fdn.gsmarena.com/imgroot/news/24/11/oneplus-13-review/lifestyle/-1200w5/gsmarena_003.jpg',
                'price' => '$ 699.00',
                'original_price' => '$ 799.00',
                'discount_percent' => '12%',
                'store_name' => 'AliExpress',
                'store_logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3b/AliExpress_logo.svg/2560px-AliExpress_logo.svg.png',
                'region' => 'International',
                'memory' => '256GB 12GB RAM',
                'description' => 'Global version of the latest flagship killer.',
            ],
            [
                'title' => 'Google Pixel 8a',
                'slug' => 'google-pixel-8a-deal-ca',
                'link' => '#',
                'image_url' => 'https://fdn.gsmarena.com/imgroot/news/24/05/google-pixel-8a-review/lifestyle/-1200w5/gsmarena_002.jpg',
                'price' => 'C$ 499.00',
                'original_price' => 'C$ 649.00',
                'discount_percent' => '23%',
                'store_name' => 'Best Buy Canada',
                'store_logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f5/Best_Buy_Logo.svg/2560px-Best_Buy_Logo.svg.png',
                'region' => 'Canada',
                'memory' => '128GB',
                'description' => 'Mid-range champion at an unbeatable price.',
            ],
        ];

        foreach ($deals as $deal) {
            Deal::updateOrCreate(['slug' => $deal['slug']], $deal);
        }
    }
}
