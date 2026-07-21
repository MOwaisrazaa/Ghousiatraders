<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\PaymentMethod;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PolaniFragranceSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedRoles();
        $this->seedCatalog();
        $this->seedPaymentMethods();
    }

    private function seedRoles(): void
    {
        foreach (['Super Admin', 'Admin', 'User'] as $name) {
            Role::firstOrCreate(['name' => $name]);
        }
    }

    private function seedCatalog(): void
    {
        DB::disableQueryLog();
        Schema::disableForeignKeyConstraints();

        foreach (['shoppingcarts', 'orders', 'ratings', 'coupons', 'products', 'categories'] as $table) {
            DB::table($table)->truncate();
        }

        Schema::enableForeignKeyConstraints();

        $categories = [
            'Men',
            'Women',
            'Attars',
            'Oud',
            'Signature',
            'Scented Candles',
        ];

        $categoryByName = [];
        foreach ($categories as $name) {
            $categoryByName[$name] = Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }

        $products = [
            [
                'name' => 'Exclusive Reserve',
                'category' => 'Signature',
                'weekly_price' => 2000,
                'image_path' => 'polani/assets/product-exclusive.jpg',
                'description' => "A luxurious signature blend crafted for presence and elegance.\nWarm woods, soft spice and a refined amber trail.",
            ],
            [
                'name' => 'Qasr Al Oud',
                'category' => 'Oud',
                'weekly_price' => 1950,
                'image_path' => 'polani/assets/product-qasr-al-oud.jpg',
                'description' => "Royal oud with saffron brightness, rose warmth and deep amber richness.\nA statement scent for special occasions.",
            ],
            [
                'name' => 'Noir Elixir',
                'category' => 'Signature',
                'weekly_price' => 1900,
                'image_path' => 'polani/assets/product-noir-elixir.jpg',
                'description' => "Dark, refined and long-lasting with crisp woods and amber depth.\nPolished for evening wear.",
            ],
            [
                'name' => 'Oud Candle',
                'category' => 'Scented Candles',
                'weekly_price' => 1800,
                'image_path' => 'polani/assets/product-candle-2.svg',
                'description' => "A smoky oud-inspired candle that fills the room with warmth and depth.\nElegant ambience in a jar.",
            ],
            [
                'name' => 'Noir Candle',
                'category' => 'Scented Candles',
                'weekly_price' => 1700,
                'image_path' => 'polani/assets/product-candle.svg',
                'description' => "Soft rose, jasmine and amber in a premium scented candle.\nCalm, cozy, and beautifully balanced.",
            ],
            [
                'name' => 'Amber Woods Attar',
                'category' => 'Attars',
                'weekly_price' => 1600,
                'image_path' => 'polani/assets/product-amber-2.svg',
                'description' => "Warm amber fused with smooth woods and a soft intimate trail.\nAlcohol-free attar for everyday wear.",
            ],
            [
                'name' => 'Musk Silk Attar',
                'category' => 'Attars',
                'weekly_price' => 1500,
                'image_path' => 'polani/assets/product-amber.svg',
                'description' => "A silky musk attar with a clean, powdery finish.\nLong-lasting and easy to wear.",
            ],
            [
                'name' => 'Citrus Grove',
                'category' => 'Men',
                'weekly_price' => 1400,
                'image_path' => 'polani/assets/product-bleu.svg',
                'description' => "Bright citrus opening with clean woods and a crisp modern dry-down.\nFresh, uplifting and versatile.",
            ],
            [
                'name' => 'Velvet Rose',
                'category' => 'Women',
                'weekly_price' => 1300,
                'image_path' => 'polani/assets/product-elixir.svg',
                'description' => "A romantic rose bouquet wrapped in gentle musk and subtle sweetness.\nSoft, elegant and memorable.",
            ],
            [
                'name' => 'Bleu Mist',
                'category' => 'Men',
                'weekly_price' => 1200,
                'image_path' => 'polani/assets/product-bleu-2.svg',
                'description' => "Fresh aromatic notes with a refined blue profile and a clean finish.\nMade for daily confidence.",
            ],
            [
                'name' => 'Amber Muse',
                'category' => 'Women',
                'weekly_price' => 1100,
                'image_path' => 'polani/assets/product-amber.svg',
                'description' => "Warm amber blended with creamy vanilla and soft floral sweetness.\nGraceful and luminous.",
            ],
            [
                'name' => 'Oud Royale',
                'category' => 'Oud',
                'weekly_price' => 1000,
                'image_path' => 'polani/assets/product-qasr-al-oud-816.jpg',
                'description' => "A deep, rich oud composition with saffron, resin and velvety amber.\nFor true oud lovers.",
            ],
        ];

        foreach ($products as $product) {
            Course::create([
                'name' => $product['name'],
                'slug' => Str::slug($product['name']),
                'description' => $product['description'],
                'instructor' => null,
                'weekly_price' => $product['weekly_price'],
                'monthly_price' => $product['weekly_price'],
                'image_path' => $product['image_path'],
                'category_id' => $categoryByName[$product['category']]->id,
                'is_free' => 0,
                'purchase_model' => 'flexible',
            ]);
        }
    }

    private function seedPaymentMethods(): void
    {
        $address = 'Dany Craft Tower, 1st Floor, Shop no. F6, M.A Jinnah Road, Karachi';
        $phone = '+92 324 9206345';

        $methods = [
            [
                'name' => 'Cash on Delivery (COD)',
                'key' => 'cash',
                'description' => 'Pay with cash on delivery',
                'icon' => 'fas fa-money-bill-wave',
                'instructions' => "Pay cash to the rider at delivery.\nSupport: {$phone}",
                'is_active' => true,
                'sort_order' => 1,
                'details' => ['color' => 'text-success'],
            ],
            [
                'name' => 'JazzCash',
                'key' => 'jazzcash',
                'description' => 'Pay with JazzCash mobile wallet',
                'icon' => 'fas fa-mobile-alt',
                'instructions' => "Send payment via JazzCash.\nSupport: {$phone}",
                'is_active' => true,
                'sort_order' => 2,
                'details' => ['color' => 'text-danger'],
            ],
            [
                'name' => 'Easypaisa',
                'key' => 'easypaisa',
                'description' => 'Pay with Easypaisa mobile wallet',
                'icon' => 'fas fa-wallet',
                'instructions' => "Send payment via Easypaisa.\nSupport: {$phone}",
                'is_active' => true,
                'sort_order' => 3,
                'details' => ['color' => 'text-warning'],
            ],
            [
                'name' => 'Bank Transfer',
                'key' => 'banktransfer',
                'description' => 'Pay via bank transfer',
                'icon' => 'fas fa-university',
                'instructions' => "Bank transfer details will be shared on order confirmation.\nPickup address: {$address}\nSupport: {$phone}",
                'is_active' => true,
                'sort_order' => 4,
                'details' => ['color' => 'text-primary'],
            ],
            [
                'name' => 'Card (Stripe)',
                'key' => 'card',
                'description' => 'Pay with Visa / Mastercard',
                'icon' => 'fas fa-credit-card',
                'instructions' => 'You will be redirected to a secure payment page to enter your card details.',
                'is_active' => true,
                'sort_order' => 5,
                'details' => ['processor' => 'stripe', 'color' => 'text-info'],
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate(['key' => $method['key']], $method);
        }
    }
}
