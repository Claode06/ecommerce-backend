<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\FileStorage;
use App\Models\PaymentAccount;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $warehouse = Warehouse::create([
            'name' => 'Gudang Utama Jakarta',
            'is_active' => true,
            'city' => 'Jakarta Pusat',
            'province' => 'DKI Jakarta',
            'postal_code' => '10110',
        ]);

        Warehouse::create([
            'name' => 'Gudang Surabaya',
            'is_active' => true,
            'city' => 'Surabaya',
            'province' => 'Jawa Timur',
            'postal_code' => '60111',
        ]);

        PaymentAccount::create([
            'bank_name' => 'BCA',
            'account_number' => '1234567890',
            'account_name' => 'PT C-Commerce Indonesia',
            'is_active' => true,
        ]);

        PaymentAccount::create([
            'bank_name' => 'Mandiri',
            'account_number' => '9876543210',
            'account_name' => 'PT C-Commerce Indonesia',
            'is_active' => true,
        ]);

        $brands = [];
        foreach (['Chanel', 'Dior', 'Gucci', 'Versace', 'Calvin Klein'] as $name) {
            $brands[] = Brand::create(['name' => $name]);
        }

        $categories = [];
        foreach ([
            ['name' => 'Eau de Parfum', 'sort_order' => 1],
            ['name' => 'Eau de Toilette', 'sort_order' => 2],
            ['name' => 'Body Mist', 'sort_order' => 3],
            ['name' => 'Parfum Pria', 'sort_order' => 4],
            ['name' => 'Parfum Wanita', 'sort_order' => 5],
        ] as $cat) {
            $categories[] = Category::create([
                'name' => $cat['name'],
                'slug' => \Illuminate\Support\Str::slug($cat['name']),
                'sort_order' => $cat['sort_order'],
                'is_active' => true,
            ]);
        }

        $productsData = [
            ['name' => 'Chanel No. 5', 'brand' => 0, 'category' => 0, 'gender' => 1, 'color' => '#fef3c7', 'text' => '#92400e', 'description' => 'Parfum ikonik dengan aroma floral yang elegan. Cocok untuk segala acara.'],
            ['name' => 'Dior Sauvage', 'brand' => 1, 'category' => 3, 'gender' => 2, 'color' => '#dbeafe', 'text' => '#1e40af', 'description' => 'Aroma maskulin yang segar dengan sentuhan citrus dan amber.'],
            ['name' => 'Gucci Bloom', 'brand' => 2, 'category' => 4, 'gender' => 1, 'color' => '#fce7f3', 'text' => '#9d174d', 'description' => 'Parfum floral yang memikat dengan wangi melati dan tuberose.'],
            ['name' => 'Versace Eros', 'brand' => 3, 'category' => 3, 'gender' => 2, 'color' => '#dcfce7', 'text' => '#166534', 'description' => 'Aroma segar dan sensual dengan kombinasi mint, apel hijau, dan vanilla.'],
            ['name' => 'CK One', 'brand' => 4, 'category' => 1, 'gender' => 3, 'color' => '#f3e8ff', 'text' => '#6b21a8', 'description' => 'Parfum unisex dengan aroma segar dan ringan untuk sehari-hari.'],
        ];

        $variantSizes = [
            ['label' => '30 ml', 'price' => 350000, 'sku_prefix' => '30'],
            ['label' => '50 ml', 'price' => 550000, 'sku_prefix' => '50'],
            ['label' => '100 ml', 'price' => 950000, 'sku_prefix' => '100'],
        ];

        foreach ($productsData as $i => $p) {
            $thumbnailUrl = $this->createPlaceholderThumbnail($p['name'], $p['color'], $p['text']);

            $product = Product::create([
                'brand_id' => $brands[$p['brand']]->id,
                'category_id' => $categories[$p['category']]->id,
                'name' => $p['name'],
                'slug' => \Illuminate\Support\Str::slug($p['name']),
                'thumbnail' => $thumbnailUrl,
                'description' => $p['description'] ?? null,
                'features' => "• Original 100%\n• Tahan lama hingga 8 jam\n• Kemasan eksklusif",
                'gender' => $p['gender'],
                'is_active' => true,
            ]);

            foreach ($variantSizes as $j => $v) {
                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'label' => $v['label'],
                    'price' => $v['price'] + ($j * 50000),
                    'sku' => strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $p['name']), 0, 3)).'-'.$v['sku_prefix'],
                    'is_active' => true,
                ]);

                WarehouseStock::create([
                    'warehouse_id' => $warehouse->id,
                    'product_variant_id' => $variant->id,
                    'quantity' => rand(10, 50),
                ]);
            }
        }
    }

    private function createPlaceholderThumbnail(string $name, string $bgColor, string $textColor): ?int
    {
        $slug = \Illuminate\Support\Str::slug($name);
        $filename = 'products/placeholder-'.$slug.'.svg';
        $initials = collect(explode(' ', $name))->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('');

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300" viewBox="0 0 400 300">
            <rect width="400" height="300" fill="'.$bgColor.'" rx="8"/>
            <circle cx="200" cy="120" r="50" fill="'.$textColor.'" opacity="0.15"/>
            <text x="200" y="135" text-anchor="middle" fill="'.$textColor.'" font-family="Arial,sans-serif" font-size="48" font-weight="bold">'.$initials.'</text>
            <text x="200" y="200" text-anchor="middle" fill="'.$textColor.'" font-family="Arial,sans-serif" font-size="16" opacity="0.6">'.$name.'</text>
        </svg>';

        Storage::disk('public')->put($filename, $svg);

        $fileStorage = FileStorage::create(['link' => $filename]);

        return $fileStorage->id;
    }
}
