<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class GhousiaProductSeeder extends Seeder
{
    public function run(): void
    {
        $this->purgePolaniData();
        $this->seedGhousiaCategoriesAndProducts();
    }

    /**
     * Purge legacy Polani demo products & categories without touching users, customers, or orders.
     */
    private function purgePolaniData(): void
    {
        // 1. Polani product slugs
        $polaniSlugs = [
            'exclusive-reserve',
            'qasr-al-oud',
            'noir-elixir',
            'oud-candle',
            'noir-candle',
            'amber-woods-attar',
            'musk-silk-attar',
            'citrus-grove',
            'velvet-rose',
            'oud-royale',
            'bleu-mist',
            'amber-muse',
        ];

        Course::whereIn('slug', $polaniSlugs)->delete();

        // 2. Polani category names
        $polaniCategories = ['Attars', 'Oud', 'Scented Candles', 'Signature', 'Men', 'Women'];
        Category::whereIn('name', $polaniCategories)->delete();
    }

    /**
     * Seed Ghousia Traders categories and real products into database.
     */
    private function seedGhousiaCategoriesAndProducts(): void
    {
        // Categories mapping
        $categoriesData = [
            'Baby Care' => 'baby-care',
            'B/O Cars' => 'bo-cars',
            'B/O Bikes' => 'bo-bikes',
        ];

        $categoryModels = [];
        foreach ($categoriesData as $name => $slug) {
            $categoryModels[$slug] = Category::updateOrCreate(
                ['slug' => $slug],
                ['name' => $name]
            );
        }

        // Products Data
        $products = [
            // Baby Care
            [
                'name' => 'Johnson’s Baby Lotion 500ml',
                'slug' => 'johnsons-baby-lotion-500ml',
                'sku' => 'GT-P-10001',
                'category_slug' => 'baby-care',
                'description' => 'Gentle moisturizing baby lotion enriched with natural oils for soft and smooth skin.',
                'long_description' => 'Johnson’s Baby Lotion nourishes baby skin for 24 hours. Clinically proven mild formula designed for gentle daily massage and deep hydration.',
                'weekly_price' => 1250,
                'sale_price' => 1100,
                'stock' => 45,
                'low_stock_threshold' => 5,
                'status' => 'active',
                'is_featured' => true,
                'image_path' => 'ghousiatraders/assets/baby_lotion.png',
            ],
            [
                'name' => 'Baby Wipes 80 Pcs',
                'slug' => 'baby-wipes-80-pcs',
                'sku' => 'GT-P-10002',
                'category_slug' => 'baby-care',
                'description' => 'Alcohol-free pure water wipes extra soft for delicate baby skin care.',
                'long_description' => 'Hypoallergenic and pH balanced baby wipes enriched with aloe vera and vitamin E. Ideal for diaper changes and quick skin cleansing.',
                'weekly_price' => 450,
                'sale_price' => null,
                'stock' => 100,
                'low_stock_threshold' => 10,
                'status' => 'active',
                'is_featured' => false,
                'image_path' => 'ghousiatraders/assets/baby_wipes.png',
            ],
            [
                'name' => 'Premium Sippy Cup',
                'slug' => 'premium-sippy-cup',
                'sku' => 'GT-P-10003',
                'category_slug' => 'baby-care',
                'description' => 'Spill-proof BPA free soft silicone spout sippy cup for toddlers.',
                'long_description' => 'Easy-grip handles and leak-proof valve design make this sippy cup perfect for toddlers learning independent drinking.',
                'weekly_price' => 650,
                'sale_price' => 580,
                'stock' => 30,
                'low_stock_threshold' => 5,
                'status' => 'active',
                'is_featured' => true,
                'image_path' => 'ghousiatraders/assets/sippy_cup.png',
            ],
            [
                'name' => 'Feeding Bottle Set',
                'slug' => 'feeding-bottle-set',
                'sku' => 'GT-P-10004',
                'category_slug' => 'baby-care',
                'description' => 'Anti-colic ergonomic baby feeding bottles set with soft teat valves.',
                'long_description' => 'Set of 2 anti-colic feeding bottles with soft silicone nipples mimicking natural breastfeeding latch for comfortable feeding.',
                'weekly_price' => 1450,
                'sale_price' => 1290,
                'stock' => 25,
                'low_stock_threshold' => 5,
                'status' => 'active',
                'is_featured' => false,
                'image_path' => 'ghousiatraders/assets/feeding_bottle_set.png',
            ],
            [
                'name' => 'Baby Shampoo 500ml',
                'slug' => 'baby-shampoo-500ml',
                'sku' => 'GT-P-10005',
                'category_slug' => 'baby-care',
                'description' => 'Tear-free gentle baby shampoo formula for clean and smooth hair.',
                'long_description' => 'No more tears mild baby shampoo cleans delicate scalp without drying or irritation. Leaves hair fresh, soft, and manageable.',
                'weekly_price' => 1150,
                'sale_price' => 990,
                'stock' => 40,
                'low_stock_threshold' => 5,
                'status' => 'active',
                'is_featured' => true,
                'image_path' => 'ghousiatraders/assets/baby_shampoo.png',
            ],

            // B/O Cars
            [
                'name' => 'Toyota Land Cruiser B/O Car',
                'slug' => 'toyota-land-cruiser-bo-car',
                'sku' => 'GT-P-20001',
                'category_slug' => 'bo-cars',
                'description' => '12V battery operated ride-on Land Cruiser SUV with remote control and sound.',
                'long_description' => 'Feature-loaded electric ride-on SUV for kids featuring 2.4G parental remote control, MP3 music player, realistic engine sounds, working LED headlights, and 4-wheel suspension.',
                'weekly_price' => 28500,
                'sale_price' => 26500,
                'stock' => 12,
                'low_stock_threshold' => 2,
                'status' => 'active',
                'is_featured' => true,
                'image_path' => 'ghousiatraders/assets/black_suv.png',
            ],
            [
                'name' => 'Mercedes AMG B/O Car',
                'slug' => 'mercedes-amg-bo-car',
                'sku' => 'GT-P-20002',
                'category_slug' => 'bo-cars',
                'description' => 'Licensed Mercedes AMG battery operated electric ride-on car with LED headlights, MP3 player and leather seat.',
                'long_description' => 'Officially licensed Mercedes AMG ride-on sports car featuring opening butterfly doors, dual powerful motors, smooth acceleration pedal, Bluetooth music connectivity, and plush leather seats.',
                'weekly_price' => 34500,
                'sale_price' => 32000,
                'stock' => 8,
                'low_stock_threshold' => 2,
                'status' => 'active',
                'is_featured' => true,
                'image_path' => 'ghousiatraders/assets/mercedes_amg_front.png',
            ],
            [
                'name' => 'Jeep Wrangler B/O Car',
                'slug' => 'jeep-wrangler-bo-car',
                'sku' => 'GT-P-20003',
                'category_slug' => 'bo-cars',
                'description' => 'Heavy-duty 4WD off-road battery operated electric jeep with dual motors and suspensions.',
                'long_description' => 'Rugged 4-wheel drive battery operated electric jeep built for outdoor adventure. Equipped with spring suspension dampers, safety seatbelt, horn buttons, and high/low speed control.',
                'weekly_price' => 29900,
                'sale_price' => 27900,
                'stock' => 15,
                'low_stock_threshold' => 3,
                'status' => 'active',
                'is_featured' => false,
                'image_path' => 'ghousiatraders/assets/toy_jeep.png',
            ],
            [
                'name' => 'Audi B/O Car',
                'slug' => 'audi-bo-car',
                'sku' => 'GT-P-20004',
                'category_slug' => 'bo-cars',
                'description' => 'Sleek yellow sports Audi battery operated ride-on car with foot pedal and parental remote.',
                'long_description' => 'Stunning Audi sports electric ride-on car with aerodynamic body styling, working dashboard lights, FM radio, built-in songs, and safety brake system.',
                'weekly_price' => 26500,
                'sale_price' => null,
                'stock' => 10,
                'low_stock_threshold' => 2,
                'status' => 'active',
                'is_featured' => false,
                'image_path' => 'ghousiatraders/assets/sports_car_yellow.png',
            ],

            // B/O Bikes
            [
                'name' => 'Sports B/O Bike',
                'slug' => 'sports-bo-bike',
                'sku' => 'GT-P-30001',
                'category_slug' => 'bo-bikes',
                'description' => 'Electric rechargeable sports superbike with training wheels, music and LED wheels.',
                'long_description' => 'Rechargeable electric sports superbike designed for young riders. Includes removable support training wheels, glowing LED wheel rims, throttle handle accelerator, and built-in audio entertainment.',
                'weekly_price' => 18500,
                'sale_price' => 16900,
                'stock' => 20,
                'low_stock_threshold' => 4,
                'status' => 'active',
                'is_featured' => true,
                'image_path' => 'ghousiatraders/assets/sport_bike.png',
            ],
            [
                'name' => 'Rechargeable Kids Bike',
                'slug' => 'rechargeable-kids-bike',
                'sku' => 'GT-P-30002',
                'category_slug' => 'bo-bikes',
                'description' => 'Adventure touring electric kids bike with 6V battery, forward/reverse gears and accelerator pedal.',
                'long_description' => 'Durable adventure trail motorbike for kids powered by a 6V rechargeable battery. Features foot pedal operation, forward/backward gear switch, front headlight, and sturdy tread tires.',
                'weekly_price' => 15900,
                'sale_price' => 14500,
                'stock' => 18,
                'low_stock_threshold' => 3,
                'status' => 'active',
                'is_featured' => false,
                'image_path' => 'ghousiatraders/assets/blue_adventure_bike.png',
            ],
        ];

        foreach ($products as $p) {
            $catId = $categoryModels[$p['category_slug']]->id;

            Course::updateOrCreate(
                ['slug' => $p['slug']],
                [
                    'sku' => $p['sku'],
                    'name' => $p['name'],
                    'slug' => $p['slug'],
                    'category_id' => $catId,
                    'description' => $p['description'],
                    'long_description' => $p['long_description'],
                    'weekly_price' => $p['weekly_price'],
                    'monthly_price' => $p['weekly_price'],
                    'sale_price' => $p['sale_price'],
                    'stock' => $p['stock'],
                    'low_stock_threshold' => $p['low_stock_threshold'],
                    'status' => $p['status'],
                    'is_featured' => $p['is_featured'],
                    'image_path' => $p['image_path'],
                    'instructor' => 'Ghousia Traders',
                    'is_free' => false,
                    'purchase_model' => 'flexible',
                ]
            );
        }
    }
}
