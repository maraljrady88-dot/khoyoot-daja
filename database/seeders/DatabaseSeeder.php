<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductReview;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@dajaabaya.sa'],
            [
                'name' => 'مدير خيوط دعجاء',
                'phone' => '0500000000',
                'role' => 'admin',
                'password' => Hash::make('admin123456'),
                'is_active' => true,
            ]
        );

        $customer = User::firstOrCreate(
            ['email' => 'customer@dajaabaya.sa'],
            [
                'name' => 'سارة العتيبي',
                'phone' => '0555555555',
                'role' => 'customer',
                'password' => Hash::make('customer123456'),
                'is_active' => true,
            ]
        );

        $customer2 = User::firstOrCreate(
            ['email' => 'noura@dajaabaya.sa'],
            [
                'name' => 'نورة الشمري',
                'phone' => '0551234567',
                'role' => 'customer',
                'password' => Hash::make('customer123456'),
                'is_active' => true,
            ]
        );

        // 2. Settings
        $settings = [
            'site_name' => 'خيوط دعجاء',
            'site_tagline' => 'خيوط دعجاء.. حيث تلتقي الأصالة بالفخامة',
            'site_subtitle' => 'أرقى العبايات الخليجية التي تجمع بين الأصالة والحداثة.',
            'about_text' => "خيوط دعجاء.. حيث تلتقي الأصالة بالفخامة\n\nنحن متجر متخصص في تقديم أرقى أنواع العبايات، حيث نحرص على توفير تشكيلات جديدة وجميلة تواكب أحدث صيحات الموضة مع الحفاظ على الرقي والجمال. نؤمن بأن العباية ليست مجرد قطعة قماش، بل هي تعبير عن الهوية والأناقة، لذا ننتقي لكم أجود الأقمشة وأجمل التصاميم لنضمن لكم إطلالة فريدة ومتميزة في كل وقت.",
            'whatsapp_number' => '966500000000',
            'instagram_url' => '',
            'tiktok_url' => '',
            'snapchat_url' => '',
            'contact_email' => 'info@dajaabaya.sa',
            'contact_phone' => '+966 50 000 0000',
            'shipping_cost' => '25',
            'free_shipping_threshold' => '350',
            'primary_color' => '#1A1817',
            'accent_color' => '#C5A880',
            'announcement_text' => 'شحن مجاني لكافة مدن المملكة للطلبات فوق 350 ريال ✦ أصالة وفخامة تليق بكِ',
        ];

        foreach ($settings as $key => $val) {
            Setting::set($key, $val);
        }

        // 3. Branches
        $branches = [
            [
                'name' => 'الطائف الدولي',
                'city' => 'الطائف',
                'address' => 'مجمع الطائف الدولي - طريق الملك خالد، الطائف',
                'phone' => '0500000001',
                'google_maps_url' => 'https://maps.google.com/?q=Taif+International+Mall',
                'working_hours' => 'يومياً من 4:00 عصراً حتى 11:00 مساءً',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'حفر الباطن – لوريت سنتر',
                'city' => 'حفر الباطن',
                'address' => 'لوريت سنتر - طريق الملك فيصل، حفر الباطن',
                'phone' => '0500000002',
                'google_maps_url' => 'https://maps.google.com/?q=Loreat+Center+Hafar+Al+Batin',
                'working_hours' => 'يومياً من 4:00 عصراً حتى 11:00 مساءً',
                'is_active' => true,
                'sort_order' => 2,
            ],
        ];

        foreach ($branches as $branch) {
            Branch::updateOrCreate(['name' => $branch['name']], $branch);
        }

        // 4. Hero Banner
        Banner::updateOrCreate(
            ['title' => 'خيوط دعجاء'],
            [
                'subtitle' => 'حيث تلتقي الأصالة بالفخامة',
                'badge_text' => 'تشكيلة الموسم الفاخرة',
                'button_text' => 'تسوقي الآن',
                'button_url' => '/shop',
                'image_path' => 'banners/hero_banner.jpg',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        // 5. Categories
        $categoriesData = [
            [
                'name' => 'عبايات جديدة',
                'slug' => 'new-abayas',
                'description' => 'أحدث التصاميم الحصرية لموسم خيوط دعجاء الفاخر',
                'sort_order' => 1,
            ],
            [
                'name' => 'العبايات الخليجية',
                'slug' => 'gulf-abayas',
                'description' => 'أصالة التراث الخليجي بلمسات راقية تواكب الحداثة',
                'sort_order' => 2,
            ],
            [
                'name' => 'العبايات الفاخرة',
                'slug' => 'luxury-abayas',
                'description' => 'إطلالات ملكية للمناسبات والأوقات المميزة بأجود الأقمشة',
                'sort_order' => 3,
            ],
            [
                'name' => 'العبايات اليومية',
                'slug' => 'daily-abayas',
                'description' => 'تصاميم عملية، مريحة وأنيقة تناسب روتينك اليومي وساعات العمل',
                'sort_order' => 4,
            ],
            [
                'name' => 'العبايات المطرزة',
                'slug' => 'embroidered-abayas',
                'description' => 'تطريزات يدوية ونقوش دقيقة تفيض أنوثة وفخامة',
                'sort_order' => 5,
            ],
            [
                'name' => 'العروض',
                'slug' => 'offers',
                'description' => 'عروض وخصومات مميزة على أرقى تشكيلات العبايات',
                'sort_order' => 6,
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        // 6. Products
        $productsData = [
            [
                'category_id' => $categories['luxury-abayas']->id,
                'name' => 'عباية ملكية بتطريز ذهبي فاخر',
                'slug' => 'royal-gold-embroidered-abaya',
                'sku' => 'DJ-ROYAL-01',
                'short_description' => 'عباية سوداء راقية من قماش الكريب الملكي المنسوج مع تطريز خيوط القصب الذهبية على الأكمام والأطراف.',
                'description' => 'تألقي بإطلالة استثنائية مع هذه العباية الملكية المصممة بعناية فائقة من متجر خيوط دعجاء. مصنوعة من قماش الكريب الملكي الفاحم السواد الذي يتميز بنعومته وانسيابيته العالية، ومزينة بتطريزات هندسية نباتية بأجود خيوط القصب الذهبي التراثي. تأتي مع طرحة ليزر مطابقة مع حاشية مطرزة بنفس النمط لتكتمل أناقتك.',
                'details' => "• نوع القماش: كريب ملكي كوري أصلي\n• القصة: كلوش انسيابي ناعم\n• نوع القفلة: طق طق مخفي عالي الجودة\n• الطرحة: شاملة طرحة سادة بحافة مطرزة\n• بلد الصنع: المملكة العربية السعودية",
                'price' => 420.00,
                'compare_at_price' => 550.00,
                'discount_percent' => 24,
                'stock_quantity' => 15,
                'low_stock_threshold' => 3,
                'sizes' => ['52', '54', '56', '58', '60'],
                'colors' => ['أسود ملكي'],
                'is_featured' => true,
                'is_active' => true,
                'image' => 'products/abaya_royal_black.jpg',
            ],
            [
                'category_id' => $categories['gulf-abayas']->id,
                'name' => 'بشت دعجاء التراثي الفاخر بياقة مذهبة',
                'slug' => 'daja-heritage-bisht-abaya',
                'sku' => 'DJ-BISHT-02',
                'short_description' => 'بشت نسائي خليجي أصيل بقصة رحبة وأكمام مزمومة مع شريط زري ذهبي مذهب على الياقة والصدر.',
                'description' => 'رمز الهوية والوقار الخليجي الأصيل. بشت دعجاء التراثي يجسد روح الفخامة برؤية عصرية تناسب مناسباتك الكبرى وحفلات الاستقبال. القماش صالونا حريري خفيف وبارد يعطي هيبة وسلاسة في الحركة، مع حياكة زري ذهبي فاخر يدوم بريقه.',
                'details' => "• نوع القماش: حرير صالونا ياباني فاخر\n• القصة: بشت خليجي ملكي واسع\n• نوع القفلة: مفتوح مع إمكانية إضافة طقطق\n• الطرحة: طرحة ليزر كورية سوداء بحافة زري ذهبي\n• بلد الصنع: المملكة العربية السعودية",
                'price' => 480.00,
                'compare_at_price' => 590.00,
                'discount_percent' => 19,
                'stock_quantity' => 8,
                'low_stock_threshold' => 2,
                'sizes' => ['52', '54', '56', '58', '60'],
                'colors' => ['أسود مطعم بالذهب'],
                'is_featured' => true,
                'is_active' => true,
                'image' => 'products/abaya_bisht_luxury.jpg',
            ],
            [
                'category_id' => $categories['daily-abayas']->id,
                'name' => 'عباية كريب يومية ناعمة بقصة مستقيمة',
                'slug' => 'daily-soft-crepe-abaya',
                'sku' => 'DJ-DAILY-03',
                'short_description' => 'عباية عملية أنيقة للمشاوير اليومية والدوام بقماش كريب كوري بارد ومريح لا يتجعد بسهولة.',
                'description' => 'الخيار المثالي للمرأة العاملة والطالبة التي تبحث عن الرقي والراحة المطلقة طوال اليوم. تمتاز هذه العباية بقماشها الخفيف والمقاوم للتجعد وسهولة العناية به، وتفاصيل أكمام أنيقة قابلة للطي.',
                'details' => "• نوع القماش: كريب واقف ميني ميت كوري\n• القصة: A-Line نص كلوش مريح\n• نوع القفلة: طقطق كامل\n• الطرحة: طرحة شيفون أسود فاحم\n• بلد الصنع: المملكة العربية السعودية",
                'price' => 260.00,
                'compare_at_price' => 320.00,
                'discount_percent' => 19,
                'stock_quantity' => 22,
                'low_stock_threshold' => 5,
                'sizes' => ['52', '54', '56', '58', '60'],
                'colors' => ['أسود داكن', 'كحلي ليلي'],
                'is_featured' => true,
                'is_active' => true,
                'image' => 'products/abaya_royal_black.jpg',
            ],
            [
                'category_id' => $categories['embroidered-abayas']->id,
                'name' => 'عباية مطرزة بالخيوط الحريرية الناعمة',
                'slug' => 'silk-thread-embroidered-abaya',
                'sku' => 'DJ-EMB-04',
                'short_description' => 'نقوش حريرية هندسية ناعمة متناسقة على الأكمام وجوانب العباية لتمنحك إطلالة فريدة ومتميزة.',
                'description' => 'كل غرزة في هذه العباية صنعت بحرفية عالية لتجسد التناغم بين البساطة والأناقة. قماش انترنت ندى سواد فاحم، يمنحك شعوراً بالثقة والانتعاش في كافة الأجواء.',
                'details' => "• نوع القماش: ندى سوبر بلاك الفاخر\n• القصة: ربع كلوش انسيابي\n• نوع القفلة: طقطق مخفي\n• الطرحة: طرحة مع تطريز طرف الكم\n• بلد الصنع: المملكة العربية السعودية",
                'price' => 340.00,
                'compare_at_price' => 410.00,
                'discount_percent' => 17,
                'stock_quantity' => 4,
                'low_stock_threshold' => 3,
                'sizes' => ['52', '54', '56', '58'],
                'colors' => ['أسود ملكي'],
                'is_featured' => true,
                'is_active' => true,
                'image' => 'products/abaya_bisht_luxury.jpg',
            ],
            [
                'category_id' => $categories['offers']->id,
                'name' => 'عباية رسمية بياقة كتان وأزرار صدفية',
                'slug' => 'formal-linen-collar-abaya',
                'sku' => 'DJ-OFFER-05',
                'short_description' => 'عرض خاص: عباية رسمية راقية بياقة بليزر أنيقة وقماش الكريب المدمج مع الكتان.',
                'description' => 'تصميم عصري مستوحى من خطوط الأزياء العالمية ومصمم بأناقة محتشمة تناسب ذوق المرأة السعودية الرفيع. تشمل أزرار صدفية طبيعية وياقة مزدوجة أنيقة للغاية.',
                'details' => "• نوع القماش: كريب سعودي مخلوط مع لنن طبيعي\n• القصة: مستقيمة عصرية\n• نوع القفلة: لف مع أزرار جمالية وطقطق داخلي\n• الطرحة: طرحة سادة طرف لنن\n• بلد الصنع: المملكة العربية السعودية",
                'price' => 290.00,
                'compare_at_price' => 390.00,
                'discount_percent' => 25,
                'stock_quantity' => 12,
                'low_stock_threshold' => 3,
                'sizes' => ['52', '54', '56', '58', '60'],
                'colors' => ['أسود فاحم'],
                'is_featured' => true,
                'is_active' => true,
                'image' => 'products/abaya_royal_black.jpg',
            ],
            [
                'category_id' => $categories['new-abayas']->id,
                'name' => 'عباية كلوش ملكية بأكمام فرنسية فاخرة',
                'slug' => 'french-sleeve-royal-abaya',
                'sku' => 'DJ-NEW-06',
                'short_description' => 'وصل حديثاً: عباية كلوش واسعة بأكمام زم فرنسية وتفاصيل لؤلؤ أسود ناعم.',
                'description' => 'إصدار محدود من تشكيلة دعجاء الجديدة. تمتاز برحابة القصة وانسيابية حركة القماش الأسود الفاحم، مع كسرات خفيفة على الأكمام تزينها حبات لؤلؤ كريستالي أسود.',
                'details' => "• نوع القماش: حرير كريب ملكي\n• القصة: دبل كلوش راقي\n• نوع القفلة: طقطق مخفي\n• الطرحة: طرحة واسعة مترين في 75 سم\n• بلد الصنع: المملكة العربية السعودية",
                'price' => 395.00,
                'compare_at_price' => 495.00,
                'discount_percent' => 20,
                'stock_quantity' => 6,
                'low_stock_threshold' => 2,
                'sizes' => ['54', '56', '58', '60'],
                'colors' => ['أسود ملكي'],
                'is_featured' => true,
                'is_active' => true,
                'image' => 'products/abaya_bisht_luxury.jpg',
            ],
        ];

        foreach ($productsData as $pData) {
            $imagePath = $pData['image'];
            unset($pData['image']);

            $product = Product::updateOrCreate(['slug' => $pData['slug']], $pData);

            // Add product images
            ProductImage::updateOrCreate(
                ['product_id' => $product->id, 'image_path' => $imagePath],
                ['is_primary' => true, 'sort_order' => 1]
            );

            // Add secondary image
            $secondaryImage = ($imagePath === 'products/abaya_royal_black.jpg') 
                ? 'products/abaya_bisht_luxury.jpg' 
                : 'products/abaya_royal_black.jpg';

            ProductImage::updateOrCreate(
                ['product_id' => $product->id, 'image_path' => $secondaryImage],
                ['is_primary' => false, 'sort_order' => 2]
            );
        }

        // 7. Orders & Order Items
        $p1 = Product::where('slug', 'royal-gold-embroidered-abaya')->first();
        $p2 = Product::where('slug', 'daja-heritage-bisht-abaya')->first();

        if ($p1 && $p2) {
            $order1 = Order::updateOrCreate(
                ['order_number' => 'DJ-20260901-7890'],
                [
                    'user_id' => $customer->id,
                    'customer_name' => 'سارة العتيبي',
                    'customer_email' => 'customer@dajaabaya.sa',
                    'customer_phone' => '0555555555',
                    'city' => 'الرياض',
                    'district' => 'حي النرجس',
                    'address' => 'شارع عثمان بن عفان، فيلا 14',
                    'notes' => 'يرجى التوصيل بعد العصر',
                    'subtotal' => 420.00,
                    'shipping_cost' => 0.00,
                    'discount_amount' => 0.00,
                    'total' => 420.00,
                    'payment_method' => 'card',
                    'payment_status' => 'paid',
                    'status' => 'delivered',
                    'tracking_number' => 'SMSA-987654321',
                    'created_at' => now()->subDays(5),
                ]
            );

            OrderItem::updateOrCreate(
                ['order_id' => $order1->id, 'product_id' => $p1->id],
                [
                    'product_name' => $p1->name,
                    'product_price' => $p1->price,
                    'quantity' => 1,
                    'size' => '56',
                    'color' => 'أسود ملكي',
                    'subtotal' => 420.00,
                ]
            );

            $order2 = Order::updateOrCreate(
                ['order_number' => 'DJ-20260915-3421'],
                [
                    'user_id' => $customer2->id,
                    'customer_name' => 'نورة الشمري',
                    'customer_email' => 'noura@dajaabaya.sa',
                    'customer_phone' => '0551234567',
                    'city' => 'حفر الباطن',
                    'district' => 'حي الخالدية',
                    'address' => 'شارع الستين، عمارة الأمل',
                    'subtotal' => 480.00,
                    'shipping_cost' => 0.00,
                    'discount_amount' => 0.00,
                    'total' => 480.00,
                    'payment_method' => 'cod',
                    'payment_status' => 'pending',
                    'status' => 'processing',
                    'tracking_number' => 'SMSA-112233445',
                    'created_at' => now()->subDays(1),
                ]
            );

            OrderItem::updateOrCreate(
                ['order_id' => $order2->id, 'product_id' => $p2->id],
                [
                    'product_name' => $p2->name,
                    'product_price' => $p2->price,
                    'quantity' => 1,
                    'size' => '54',
                    'color' => 'أسود مطعم بالذهب',
                    'subtotal' => 480.00,
                ]
            );

            // 8. Reviews with Verified Purchase
            ProductReview::updateOrCreate(
                ['product_id' => $p1->id, 'user_id' => $customer->id],
                [
                    'order_id' => $order1->id,
                    'customer_name' => 'سارة العتيبي',
                    'rating' => 5,
                    'review_text' => 'ما شاء الله تبارك الله، العباية تفوق الوصف! السواد فاحم والتطريز متقن ونظيف جداً، وريحة العباية والباكجينج فخم يواجه.. بارك الله لكم في متجركم.',
                    'is_verified_purchase' => true,
                    'status' => 'approved',
                    'is_featured' => true,
                    'created_at' => now()->subDays(3),
                ]
            );

            ProductReview::updateOrCreate(
                ['product_id' => $p1->id, 'customer_name' => 'ريم فهد'],
                [
                    'rating' => 5,
                    'review_text' => 'الخامة خفيفة وباردة والمقاس مضبوط بالملي حسب جدول المقاسات. التوصيل كان سريع خلال يومين فقط.',
                    'is_verified_purchase' => true,
                    'status' => 'approved',
                    'is_featured' => true,
                    'created_at' => now()->subDays(2),
                ]
            );

            ProductReview::updateOrCreate(
                ['product_id' => $p2->id, 'customer_name' => 'هند القحطاني'],
                [
                    'rating' => 5,
                    'review_text' => 'البشت فخم جداً وله هيبة في المناسبات. الزري لونه ذهبي راقي مو فاقع. شكراً خيوط دعجاء على هذا الإتقان.',
                    'is_verified_purchase' => true,
                    'status' => 'approved',
                    'is_featured' => true,
                    'created_at' => now()->subDays(1),
                ]
            );
        }
    }
}
