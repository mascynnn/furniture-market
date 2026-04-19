<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =====================
        // USER DEFAULT
        // =====================
        $testUser = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        $demoUser = User::firstOrCreate(
            ['email' => 'demo@casaforma.id'],
            [
                'name' => 'Demo User',
                'password' => bcrypt('password'),
            ]
        );

        // =====================
        // CATEGORIES
        // =====================
        $cats = [
            ['name' => 'Kursi',           'slug' => 'kursi'],
            ['name' => 'Meja',            'slug' => 'meja'],
            ['name' => 'Lemari & Rak',    'slug' => 'lemari-rak'],
            ['name' => 'Sofa',            'slug' => 'sofa'],
            ['name' => 'Tempat Tidur',    'slug' => 'tempat-tidur'],
            ['name' => 'Dekorasi',        'slug' => 'dekorasi'],
        ];

        foreach ($cats as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        $kursiId = Category::where('slug', 'kursi')->value('id');
        $mejaId  = Category::where('slug', 'meja')->value('id');
        $sofaId  = Category::where('slug', 'sofa')->value('id');

        // =====================
        // PRODUCTS
        // =====================
        $products = [
            ['name' => 'Kursi Langus Seven',     'category_id' => $kursiId, 'price' => 4250000, 'material' => 'Jati',     'weight' => 8000,  'stock' => 10],
            ['name' => 'Meja Samping Ohidara',   'category_id' => $mejaId,  'price' => 2800000, 'material' => 'Mahoni',   'weight' => 5000,  'stock' => 15],
            ['name' => 'Sofa Beludru Emerald',   'category_id' => $sofaId,  'price' => 12500000,'material' => 'Beludru',  'weight' => 25000, 'stock' => 5],
            ['name' => 'Kursi Rotan Natura',     'category_id' => $kursiId, 'price' => 3100000, 'material' => 'Rotan',    'weight' => 6000,  'stock' => 12],
            ['name' => 'Meja Kopi Walnut',       'category_id' => $mejaId,  'price' => 5750000, 'material' => 'Walnut',   'weight' => 12000, 'stock' => 8],
            ['name' => 'Sofa Minimalis Osaka',   'category_id' => $sofaId,  'price' => 8900000, 'material' => 'Linen',    'weight' => 30000, 'stock' => 6],
        ];

        foreach ($products as $p) {
            $slug = Str::slug($p['name']);

            Product::firstOrCreate(
                ['slug' => $slug],
                array_merge($p, [
                    'slug'        => $slug,
                    'description' => 'Furniture premium dengan desain modern.',
                    'is_active'   => true,
                    'user_id'     => $demoUser->id // 🔥 penting (relasi seller)
                ])
            );
        }

        // =====================
        // SAMPLE ORDER
        // =====================
        $product = Product::first();

        if ($product) {
            $order = Order::create([
                'order_number'    => 'CF-DEMO0001',
                'user_id'         => $demoUser->id,
                'status'          => 'shipped',

                'recipient_name'  => 'Demo User',
                'phone'           => '081234567890',
                'address'         => 'Jl. Contoh No. 1',

                'province'        => 'Lampung',
                'province_id'     => '18',
                'city'            => 'Bandar Lampung',
                'city_id'         => '109',
                'postal_code'     => '35111',

                'courier'         => 'jne',
                'shipping_service'=> 'REG',
                'shipping_cost'   => 45000,
                'tracking_number' => 'JNE00123456789',
                'estimated_delivery' => '3-4 hari',

                'subtotal'        => $product->price,
                'total_amount'    => $product->price + 45000,
                'weight'          => $product->weight,
            ]);

            OrderItem::create([
                'order_id'     => $order->id,
                'product_id'   => $product->id,
                'product_name' => $product->name,
                'price'        => $product->price,
                'quantity'     => 1,
                'subtotal'     => $product->price,
            ]);
        }
    }
}