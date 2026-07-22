<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Course;
use App\Models\FooterSetting;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Seed Footer Settings for Ghousia Traders
        $footerData = [
            'brand_name' => 'Ghousia Traders',
            'brand_tagline' => 'Little Essentials, Big Joy',
            'brand_description' => 'Your trusted destination for premium baby care products and exciting ride-on toys. Quality you can trust, happiness they deserve.',
            'facebook_url' => 'https://facebook.com/ghousiatraders',
            'instagram_url' => 'https://instagram.com/ghousiatraders',
            'tiktok_url' => 'https://tiktok.com/@ghousiatraders',
            'youtube_url' => 'https://youtube.com/ghousiatraders',
            'linkedin_url' => null,
            'address' => 'Shop # 12, Main Market, DHA Phase 6, Lahore, Pakistan',
            'email' => 'info@ghousiatraders.com',
            'phone' => '0321-1234567',
            'copyright_name' => 'Ghousia Traders',
            'copyright_url' => url('/'),
            'footer_text' => 'All Rights Reserved.',
        ];

        if (Schema::hasTable('footer_settings')) {
            $existing = DB::table('footer_settings')->first();
            if ($existing) {
                DB::table('footer_settings')->where('id', $existing->id)->update($footerData);
            } else {
                DB::table('footer_settings')->insert(array_merge($footerData, ['created_at' => now(), 'updated_at' => now()]));
            }
        }

        // 2. Seed Categories
        $categories = [
            'Baby Care' => 'baby-care',
            'B/O Bikes' => 'bo-bikes',
            'B/O Cars' => 'bo-cars',
        ];

        $categoryIds = [];
        foreach ($categories as $name => $slug) {
            $cat = Category::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'theme' => 'polani',
                    'theme_id' => 1
                ]
            );
            $categoryIds[$name] = $cat->id;
        }

        // 3. Seed Products (Courses)
        $products = [
            // B/O Cars
            [
                'name' => 'Mercedes B/O Car (AMG)',
                'slug' => 'mercedes-amg',
                'category' => 'B/O Cars',
                'weekly_price' => 29999,
                'image_path' => 'ghousiatraders/assets/mercedes_amg_front.png',
                'description' => 'Premium licensed Mercedes AMG ride-on car for kids with remote control, LED lights, music player and rechargeable battery for a real driving experience.',
                'long_description' => "Licensed Mercedes AMG design with realistic looks.\nDual control: Kids can drive or parents can control remotely.\nBright LED headlights and taillights.\nBuilt-in music player with USB & MP3 support.\nSmooth start function for safe acceleration.\nComfortable seat with safety belt.",
            ],
            [
                'name' => 'Jeep Wrangler 4WD Car (JM09)',
                'slug' => 'jeep-wrangler',
                'category' => 'B/O Cars',
                'weekly_price' => 34999,
                'image_path' => 'ghousiatraders/assets/toy_jeep.png',
                'description' => 'Heavy duty 4-wheel drive Jeep Wrangler electric ride-on for off-road fun, equipped with 4 motors, leather seat, and parental remote.',
                'long_description' => "Authentic 4WD Jeep Wrangler design with high ground clearance.\nPowerful 4-motor drive system for all terrains.\n2.4GHz Bluetooth remote control for parent override.\nLeather seat with 5-point safety harness.\nSpring suspension on all 4 wheels for smooth ride.",
            ],
            [
                'name' => 'Toyota Land Cruiser B/O Car',
                'slug' => 'land-cruiser',
                'category' => 'B/O Cars',
                'weekly_price' => 32999,
                'image_path' => 'ghousiatraders/assets/black_suv.png',
                'description' => 'Spacious SUV Toyota Land Cruiser ride-on car with dual doors, LED lights, working horn, and remote control.',
                'long_description' => "Licensed Land Cruiser style SUV body with chrome accents.\nOpening doors with safety lock system.\nDashboard battery indicator display.\nShock absorption suspension for comfortable ride.",
            ],
            [
                'name' => 'Range Rover B/O Car',
                'slug' => 'range-rover',
                'category' => 'B/O Cars',
                'weekly_price' => 36999,
                'image_path' => 'ghousiatraders/assets/black_suv.png',
                'description' => 'Luxury Edition Range Rover battery operated ride-on car with touchscreen dashboard, leather seats, and metallic finish.',
                'long_description' => "Luxury Range Rover SUV design.\nSoft EVA rubber tires for quiet smooth ride.\nBuilt-in MP4 Video Player screen.\nSoft padded leather seats.",
            ],
            [
                'name' => 'Audi SQ5 B/O Car',
                'slug' => 'audi-r8',
                'category' => 'B/O Cars',
                'weekly_price' => 29999,
                'image_path' => 'ghousiatraders/assets/sports_car_yellow.png',
                'description' => 'Sleek yellow Audi sports ride-on car with low-profile sports design, racing steering wheel, and LED grill lights.',
                'long_description' => "Official Audi sports styling.\nSteering wheel with horn and sound buttons.\nWorking LED headlights.\nParent remote control for safety.",
            ],

            // B/O Bikes
            [
                'name' => 'Sports B/O Bike (Rechargeable)',
                'slug' => 'sports-bike',
                'category' => 'B/O Bikes',
                'weekly_price' => 24999,
                'image_path' => 'ghousiatraders/assets/sport_bike.png',
                'description' => 'Exciting kids electric sports superbike with training wheels, hand throttle acceleration, LED headlight, and music engine sounds.',
                'long_description' => "Futuristic aerodynamic sports bike design.\nHand twist throttle for authentic motorcycle experience.\nDetachable auxiliary training wheels for safety.\nLED light-up wheels and front headlight.",
            ],
            [
                'name' => 'Adventure Trail B/O Bike',
                'slug' => 'adventure-bike',
                'category' => 'B/O Bikes',
                'weekly_price' => 32999,
                'image_path' => 'ghousiatraders/assets/blue_adventure_bike.png',
                'description' => 'Heavy-duty touring adventure motorcycle for kids with dual side storage boxes, high wind visor, and key start.',
                'long_description' => "Touring adventure style with dual side panniers.\nKey ignition with realistic start sound.\nFoot pedal brake for safe quick stopping.\nBluetooth audio connectivity with built-in speakers.",
            ],
            [
                'name' => 'Vespa Vintage Scooter (6V)',
                'slug' => 'vespa-scooter',
                'category' => 'B/O Bikes',
                'weekly_price' => 19999,
                'image_path' => 'ghousiatraders/assets/vespa_scooter.png',
                'description' => 'Classic Italian retro style Vespa electric scooter with gentle acceleration, foot pedal, chrome mirrors, and backrest.',
                'long_description' => "Charming vintage Vespa scooter styling.\nSoft start pedal control ideal for toddlers.\nComfortable backrest for safe support.",
            ],

            // Baby Care
            [
                'name' => "Johnson's Baby Lotion 500ml",
                'slug' => 'baby-lotion',
                'category' => 'Baby Care',
                'weekly_price' => 1250,
                'image_path' => 'ghousiatraders/assets/baby_lotion.png',
                'description' => 'Deeply hydrating 24-hour moisture baby lotion infused with organic shea butter and natural almond oil for baby soft skin.',
                'long_description' => "Locks in skin moisture for 24 hours.\nNon-greasy fast absorbing texture.\nMild soothing fragrance.\nPediatrician approved and safe for newborns.",
            ],
            [
                'name' => 'Baby Wipes 80 Pcs',
                'slug' => 'baby-wipes',
                'category' => 'Baby Care',
                'weekly_price' => 450,
                'image_path' => 'ghousiatraders/assets/baby_wipes.png',
                'description' => '99% pure water wipes with thick honeycomb cotton cloth for extra soft and soothing skin cleaning.',
                'long_description' => "Alcohol-free, fragrance-free, unscented water wipes.\nThick embossed honeycomb cotton fabric prevents tearing during diaper changes.",
            ],
            [
                'name' => 'Premium Sippy Cup 260ml',
                'slug' => 'sippy-cup',
                'category' => 'Baby Care',
                'weekly_price' => 750,
                'image_path' => 'ghousiatraders/assets/sippy_cup.png',
                'description' => 'Spill-proof ergonomic trainer sippy cup with soft silicone spout and easy-grip handles for toddlers.',
                'long_description' => "Soft silicone spout gentle on teething gums.\nDual ergonomic side handles for independent drinking.\n100% BPA Free food grade silicone.",
            ],
            [
                'name' => "Johnson's Baby Shampoo 500ml",
                'slug' => 'baby-shampoo',
                'category' => 'Baby Care',
                'weekly_price' => 1150,
                'image_path' => 'ghousiatraders/assets/baby_shampoo.png',
                'description' => 'Ultra-gentle tear-free hair wash enriched with chamomile flower extract to cleanse delicate hair and scalp smoothly.',
                'long_description' => "Formulated to prevent eye irritation during bath time (Tear-Free).\nLeaves hair soft, shiny, and easy to comb.\nEnriched with soothing chamomile extract.",
            ],
            [
                'name' => "Johnson's Baby Powder 400g",
                'slug' => 'baby-powder',
                'category' => 'Baby Care',
                'weekly_price' => 950,
                'image_path' => 'ghousiatraders/assets/baby_powder.png',
                'description' => 'Soft & fresh gentle baby powder that absorbs excess moisture and keeps skin dry and comfortably soft.',
                'long_description' => "Dermatologically tested mild powder.\nSoothes skin and prevents friction chafing.\nAbsorbs excess moisture.",
            ],
            [
                'name' => 'Feeding Bottle Set 3 Pieces',
                'slug' => 'feeding-bottle-set',
                'category' => 'Baby Care',
                'weekly_price' => 1650,
                'image_path' => 'ghousiatraders/assets/feeding_bottle_set.png',
                'description' => 'Anti-colic anti-gas feeding bottle set with natural latch nipples, clear measurement markings, and hygienic protective caps.',
                'long_description' => "Anti-colic valve reduces gas and reflux.\nNatural breast-like latch nipple.\nHigh quality BPA-free polypropylene bottles.\nWide neck design for quick easy cleaning.",
            ],
            [
                'name' => 'Diaper Rash Cream 100g',
                'slug' => 'diaper-cream',
                'category' => 'Baby Care',
                'weekly_price' => 650,
                'image_path' => 'ghousiatraders/assets/diaper_cream.png',
                'description' => 'Zinc oxide protective barrier cream that instantly calms redness and seals out wetness for prompt rash recovery.',
                'long_description' => "Contains 15% Zinc Oxide for instant shield.\nForms an immediate breathable protective layer.\nRelieves discomfort from first application.",
            ],
        ];

        foreach ($products as $p) {
            Course::updateOrCreate(
                ['slug' => $p['slug']],
                [
                    'name' => $p['name'],
                    'category_id' => $categoryIds[$p['category']],
                    'weekly_price' => $p['weekly_price'],
                    'monthly_price' => $p['weekly_price'],
                    'image_path' => $p['image_path'],
                    'description' => $p['description'],
                    'long_description' => $p['long_description'],
                    'is_free' => 0,
                    'purchase_model' => 'flexible',
                    'theme' => 'polani',
                    'theme_id' => 1
                ]
            );
        }
    }

    public function down(): void
    {
        // Define rollback logic if needed (e.g. deleting seeded products and categories)
    }
};
