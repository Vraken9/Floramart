<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Name pools per folder for auto-generating product names.
     * Each folder has multiple names to pick from randomly.
     */
    private array $autoNamePools = [
        'bunga_stand' => [
            'Standing Flower Elegan Congratulations',
            'Karangan Bunga Standing Mewah Premium',
            'Standing Flower Grand Opening Exclusive',
            'Bunga Standing Ucapan Selamat Megah',
            'Standing Arrangement Tropis Modern',
            'Karangan Standing Corporate Deluxe',
            'Standing Flower Sympathy Damai Abadi',
            'Bunga Standing Peresmian Kantor VIP',
        ],
        'bunga_segar' => [
            'Buket Bunga Segar Hand-Tied Classic',
            'Fresh Cut Flower Bouquet Romantis',
            'Rangkaian Bunga Potong Segar Harian',
            'Buket Segar Mixed Flower Colorful',
            'Bunga Potong Premium Wrapped Elegan',
            'Fresh Bouquet Market Style Cantik',
            'Buket Segar Seasonal Bloom Spesial',
            'Hand Bouquet Bunga Segar Daily Fresh',
        ],
        'bunga_box' => [
            'Bloom Box Mawar Segar Luxury Edition',
            'Flower Box Premium Velvet Elegan',
            'Rose Box Anniversary Spesial',
            'Bloom Box Pastel Korean Style',
            'Luxury Flower Box Mixed Roses',
            'Bloom Box Mini Sweet Surprise Gift',
            'Grand Bloom Box Preserved Premium',
            'Flower Box Celebration Festive Edition',
            'Bloom Box Anggrek Bulan Exclusive',
            'Velvet Bloom Box Romantic Collection',
        ],
        'bunga_perayaan' => [
            'Hampers Bunga Perayaan Premium Eksklusif',
            'Gift Box Bunga Celebration Mewah',
            'Parcel Bunga Hari Raya Istimewa',
            'Rangkaian Perayaan Anniversary Romantis',
        ],
        'bunga_buket' => [
            'Buket Mawar Premium Korean Wrap',
            'Hand Bouquet Wisuda Elegan Cantik',
            'Buket Bunga Campur Cheerful Vibes',
            'Bouquet Romantis Valentine Special',
            'Buket Bunga Lily Harum Exclusive',
            'Hand Bouquet Baby Breath Dreamy',
            'Buket Tulip Import Fresh Holland',
            'Bouquet Rustic Wildflower Garden Style',
            'Buket Anggrek Exotic Premium Wrap',
            'Hand Bouquet Hydrangea Pastel Soft',
        ],
        'bunga_meja' => [
            'Rangkaian Bunga Meja Minimalis Modern',
            'Table Flower Arrangement Elegan Classic',
            'Bunga Meja Centerpiece Acara Formal',
            'Vas Bunga Meja Ceramic Artistic',
            'Mini Vase Arrangement Fresh Daily',
            'Rangkaian Meja Anggrek Bulan Premium',
            'Bunga Meja Tropis Bird of Paradise',
            'Table Flower Rustic Greenery Style',
            'Rangkaian Meja Rose Garden Cantik',
            'Bunga Meja Crystal Vase Mewah',
        ],
        'bunga_hias' => [
            'Tanaman Hias Indoor Estetik Premium',
            'Sukulen Terrarium Geometric Modern',
            'Anggrek Bulan Pot Keramik Eksklusif',
            'Bonsai Mini Seni Hidup Unik',
            'Monstera Variegata Collector Edition',
            'Philodendron Rare Tropical Plant',
            'Alocasia Dragon Scale Langka',
            'Calathea Orbifolia Daun Jumbo Indoor',
            'Aglaonema Red Valentine Super',
            'Anthurium Crystal Velvet Premium',
            'Sansevieria Gold Flame Eksotis',
            'Kokedama Moss Ball Japanese Art',
            'Caladium Pink Cloud Koleksi Baru',
            'Pothos Marble Queen Rambat Cantik',
            'Peace Lily Pembersih Udara Alami',
            'Ficus Elastica Burgundy Statement Plant',
            'Begonia Rex Painted Leaf Koleksi',
            'Hoya Carnosa Tricolor Gantung Estetik',
            'Adenium Obesum Double Petal Mekar',
            'ZZ Plant Gold Rare Indoor Cantik',
        ],
    ];

    /**
     * Description pools per folder for auto-generating product descriptions.
     */
    private array $autoDescPools = [
        'bunga_stand' => [
            'Standing flower premium dengan rangkaian bunga segar pilihan yang megah dan menawan. Cocok untuk ucapan selamat pembukaan usaha, pernikahan, atau momen seremonial penting lainnya. Termasuk pita ucapan kustom dengan tulisan emas.',
            'Karangan bunga standing eksklusif berdesain modern yang akan menjadi pusat perhatian di setiap acara. Menggunakan bunga-bunga segar berkualitas tinggi yang dirangkai oleh florist profesional berpengalaman.',
            'Rangkaian standing flower bernuansa elegan dengan perpaduan bunga tropis dan dedaunan hijau yang segar. Dilengkapi standing besi kokoh berdesain geometris modern. Pengiriman dan setup gratis di area kota.',
        ],
        'bunga_segar' => [
            'Buket bunga potong segar yang dipetik langsung dari kebun bunga terbaik. Kesegaran terjamin karena diproses dan dikirim di hari yang sama. Dibungkus cantik dengan kertas premium bergaya modern.',
            'Rangkaian bunga segar pilihan dengan kualitas terbaik, cocok untuk hadiah spesial di berbagai momen bahagia. Setiap tangkai dipilih dengan teliti dan dibungkus menggunakan wrapping paper elegan.',
            'Koleksi bunga potong fresh premium yang tersedia setiap hari. Dirawat dengan teknik cold chain untuk menjaga kesegaran optimal. Dilengkapi sachet flower food agar tahan lebih lama di vas.',
        ],
        'bunga_box' => [
            'Bloom box premium dengan rangkaian bunga segar pilihan, dikemas dalam kotak elegan yang cocok untuk hadiah spesial. Bunga ditata dengan penuh cinta oleh florist profesional kami. Tersedia pengiriman same-day.',
            'Flower box cantik dengan kombinasi bunga-bunga terbaik yang dirangkai secara artistik. Kotak premium dengan finishing matte dan pita satin eksklusif. Sempurna untuk merayakan momen bahagia orang tersayang.',
            'Koleksi bloom box mewah dengan desain modern dan minimalis. Menggunakan bunga-bunga segar berkualitas tinggi yang dipilih langsung dari petani terbaik. Dilengkapi kartu ucapan gratis dan garansi kesegaran 5 hari.',
            'Flower box eksklusif dengan sentuhan Korean style yang sedang tren. Bunga dirangkai dengan teknik khusus agar tetap segar lebih lama. Packaging premium cocok untuk dijadikan hadiah di segala momen.',
        ],
        'bunga_perayaan' => [
            'Rangkaian bunga perayaan spesial untuk momen-momen istimewa. Dari pesta ulang tahun hingga perayaan anniversary, bunga kami siap memeriahkan acara. Tersedia custom design sesuai tema perayaan.',
            'Paket bunga perayaan lengkap dengan hampers eksklusif berisi bunga segar premium dan hadiah pilihan. Dikemas dalam box mewah dengan pita dan kartu ucapan elegan. Pengiriman express tersedia.',
            'Dekorasi bunga perayaan yang akan membuat acara Anda semakin berkesan dan memorable. Tim dekorator kami siap membantu mewujudkan konsep impian Anda. Konsultasi gratis untuk pemesanan premium.',
        ],
        'bunga_buket' => [
            'Buket bunga segar premium yang dirangkai dengan penuh keindahan oleh florist berpengalaman. Setiap tangkai dipilih teliti untuk memastikan kualitas terbaik. Wrapping premium dengan pilihan warna yang elegan.',
            'Buket bunga istimewa untuk momen-momen berharga. Dibungkus dengan kertas premium dan dihiasi pita satin mewah. Cocok untuk wisuda, ulang tahun, anniversary, atau ungkapan kasih sayang.',
            'Rangkaian buket fresh pilihan dengan desain trendy dan kekinian. Menggunakan teknik wrapping ala Korea yang sedang populer. Tersedia dalam berbagai ukuran dari mini hingga jumbo.',
            'Buket cantik dengan perpaduan bunga dan dedaunan hijau yang harmonis. Setiap buket dibuat fresh on order untuk menjamin kesegaran. Free kartu ucapan dan bonus sachet flower food.',
        ],
        'bunga_meja' => [
            'Rangkaian bunga meja segar yang menambah keindahan dan aroma harum di setiap ruangan. Cocok untuk meja kerja, meja makan, atau lobi kantor. Dirangkai dengan gaya modern dan elegan oleh florist profesional.',
            'Bunga meja premium dengan desain yang disesuaikan untuk berbagai suasana. Menggunakan vas berkualitas tinggi yang bisa digunakan kembali. Cocok untuk corporate gift atau dekorasi event.',
            'Rangkaian bunga meja artistik yang akan mempercantik suasana ruangan Anda. Dibuat dengan bunga-bunga segar pilihan yang tahan hingga 7 hari. Tersedia layanan subscription mingguan.',
            'Table flower arrangement minimalis namun berkesan. Perpaduan bunga segar dan greenery yang harmonis menciptakan nuansa natural dan fresh. Ideal untuk dekorasi rumah, kantor, atau restaurant.',
        ],
        'bunga_hias' => [
            'Tanaman hias berkualitas tinggi yang telah melewati proses seleksi ketat. Ditanam dan dirawat oleh nursery profesional dengan media tanam premium. Dilengkapi panduan perawatan lengkap dan garansi hidup 30 hari.',
            'Koleksi tanaman hias indoor pilihan yang mudah perawatannya dan cocok untuk dekorasi rumah maupun kantor. Pot eksklusif menambah nilai estetika. Cocok untuk pemula maupun kolektor.',
            'Tanaman hias langka dan unik yang akan menjadi statement piece di ruangan Anda. Difoto sesuai kondisi aktual. Dikirim dengan packaging aman anti-damage dan media tanam premium.',
            'Tanaman hias premium yang telah diadaptasi untuk lingkungan indoor. Bermanfaat sebagai pembersih udara alami sekaligus elemen dekoratif. Termasuk pot cantik dengan desain minimalis modern.',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ==========================================
        // STEP 0: WIPE ALL EXISTING PRODUCTS (safe reset)
        // ==========================================
        \App\Models\Product::query()->delete();
        $this->command->warn('🗑️  All existing products deleted.');

        // ==========================================
        // STEP 1: FETCH ONLY APPROVED SHOPS
        // ==========================================
        $approvedShopIds = \App\Models\Shop::where('status', 'approved')->pluck('id')->toArray();

        if (empty($approvedShopIds)) {
            $this->command->error('❌ No approved shops found. Please approve at least one shop first.');
            return;
        }

        $this->command->info('✅ Found ' . count($approvedShopIds) . ' approved shop(s).');

        // ==========================================
        // STEP 2: DYNAMIC CATEGORY CREATION
        // ==========================================
        $standCategory = Category::firstOrCreate(
            ['slug' => Str::slug('Standing Flower')],
            ['name' => 'Standing Flower']
        );

        $segarCategory = Category::firstOrCreate(
            ['slug' => Str::slug('Buket Segar')],
            ['name' => 'Buket Segar']
        );

        $boxCategory = Category::firstOrCreate(
            ['slug' => Str::slug('Bloom Box')],
            ['name' => 'Bloom Box']
        );

        $perayaanCategory = Category::firstOrCreate(
            ['slug' => Str::slug('Premium Gift Box')],
            ['name' => 'Premium Gift Box']
        );

        $buketPremiumCategory = Category::firstOrCreate(
            ['slug' => Str::slug('Buket Premium')],
            ['name' => 'Buket Premium']
        );

        $bungaMejaCategory = Category::firstOrCreate(
            ['slug' => Str::slug('Bunga Meja')],
            ['name' => 'Bunga Meja']
        );

        $tanamanHiasCategory = Category::firstOrCreate(
            ['slug' => Str::slug('Tanaman Hias')],
            ['name' => 'Tanaman Hias']
        );

        // Folder → Category mapping for dynamic assignment
        $folderCategoryMap = [
            'bunga_stand'    => $standCategory->id,
            'bunga_segar'    => $segarCategory->id,
            'bunga_box'      => $boxCategory->id,
            'bunga_perayaan' => $perayaanCategory->id,
            'bunga_buket'    => $buketPremiumCategory->id,
            'bunga_meja'     => $bungaMejaCategory->id,
            'bunga_hias'     => $tanamanHiasCategory->id,
        ];

        // ==========================================
        // STEP 3: 32 HAND-CRAFTED PRODUCTS (Phase 1+2+3)
        // ==========================================
        $curatedProducts = [

            // --- PHASE 1: BUNGA STAND (5 products) ---
            [
                'image_path'  => 'products/bunga_stand/1.png',
                'name'        => 'Tropical Elegance Standing Flower',
                'description' => 'Rangkaian bunga standing bergaya tropis premium dengan paduan Anthurium merah merona dan daun Monstera. Disusun di atas standing besi geometris berwarna emas dengan balutan kain tulle terracotta. Sangat cocok untuk ucapan Grand Opening atau peresmian kantor.',
                'price'       => 850000,
                'category_id' => $standCategory->id,
            ],
            [
                'image_path'  => 'products/bunga_stand/2.png',
                'name'        => 'Sunset Paradise Standing Arrangement',
                'description' => 'Standing flower eksotis yang memadukan keindahan bunga Heliconia, Protea, dan dedaunan tropis segar. Menggunakan standing geometris elegan, memberikan kesan mewah dan meriah untuk acara seremonial perusahaan Anda.',
                'price'       => 950000,
                'category_id' => $standCategory->id,
            ],
            [
                'image_path'  => 'products/bunga_stand/3.png',
                'name'        => 'Orchid Majesty Geometric Stand',
                'description' => 'Perpaduan sempurna antara keanggunan Anggrek Bulan (Phalaenopsis) ungu dan Anthurium merah. Desain minimalis modern pada standing emasnya membuat karangan bunga ini terlihat sangat eksklusif untuk ucapan selamat kepada kolega VIP.',
                'price'       => 1200000,
                'category_id' => $standCategory->id,
            ],
            [
                'image_path'  => 'products/bunga_stand/4.png',
                'name'        => 'Serene Sympathy Standing Wreath',
                'description' => 'Karangan bunga duka cita (Condolences) yang dirangkai syahdu menggunakan bunga Lily putih suci, Mawar putih, dan sentuhan Delphinium biru. Memberikan kesan damai dan penghormatan terakhir yang elegan. Termasuk pita ucapan kustom.',
                'price'       => 750000,
                'category_id' => $standCategory->id,
            ],
            [
                'image_path'  => 'products/bunga_stand/5.png',
                'name'        => 'Exotic Anthurium Gold Stand',
                'description' => 'Standing flower bernuansa merah berani dari Anthurium segar yang dipadukan dengan rimbunnya daun Monstera. Cocok untuk memberikan nuansa semangat dan kesuksesan pada acara peluncuran produk atau pembukaan toko baru.',
                'price'       => 800000,
                'category_id' => $standCategory->id,
            ],

            // --- PHASE 1: BUNGA SEGAR (7 products) ---
            [
                'image_path'  => 'products/bunga_segar/1.png',
                'name'        => 'Radiant Sunflower Mixed Bouquet',
                'description' => 'Buket ceria yang memadukan Bunga Matahari cerah dengan Mawar oranye dan kuning. Dibungkus menggunakan premium craft paper bergaya rustic. Pilihan paling sempurna untuk hadiah wisuda atau menyemangati hari seseorang.',
                'price'       => 250000,
                'category_id' => $segarCategory->id,
            ],
            [
                'image_path'  => 'products/bunga_segar/2.png',
                'name'        => 'Royal Purple Rose Bouquet',
                'description' => 'Buket mawar ungu premium yang melambangkan keanggunan dan pesona, dipadukan dengan bunga Lavender kering. Dibungkus dengan kertas wrapping matte berwarna lilac yang elegan. Hadiah romantis yang tak terlupakan untuk anniversary.',
                'price'       => 350000,
                'category_id' => $segarCategory->id,
            ],
            [
                'image_path'  => 'products/bunga_segar/3.png',
                'name'        => 'Pastel Dream Hydrangea Bouquet',
                'description' => 'Kelembutan warna pastel dari paduan Mawar merah muda dan Hydrangea (Panca Warna) biru muda. Rangkaian manis ini dibalut kertas premium pink pastel, sangat cocok untuk kado ulang tahun atau ungkapan kasih sayang.',
                'price'       => 450000,
                'category_id' => $segarCategory->id,
            ],
            [
                'image_path'  => 'products/bunga_segar/4.png',
                'name'        => 'Midnight Crimson Rose Bouquet',
                'description' => 'Buket mawar merah marun (burgundy) bernuansa gelap dan misterius, dilengkapi dengan dedaunan eksotis berwarna senada. Dibungkus rapi dalam balutan kertas hitam elegan. Sangat mewah untuk makan malam romantis atau perayaan istimewa.',
                'price'       => 500000,
                'category_id' => $segarCategory->id,
            ],
            [
                'image_path'  => 'products/bunga_segar/5.png',
                'name'        => 'Pure Elegance White Lily Bouquet',
                'description' => 'Rangkaian mawar putih klasik dan bunga Lily putih yang mekar sempurna. Menghasilkan aroma wangi yang lembut. Dibungkus dengan kertas warna beige, merepresentasikan ketulusan dan cinta suci.',
                'price'       => 380000,
                'category_id' => $segarCategory->id,
            ],
            [
                'image_path'  => 'products/bunga_segar/6.png',
                'name'        => 'Blush Peony Spring Bouquet',
                'description' => 'Buket mewah yang menghadirkan pesona bunga Peony merah muda yang mekar penuh, diselingi Mawar dan dedaunan hijau segar. Rangkaian padat bergaya Korea ini adalah pilihan kado paling didambakan.',
                'price'       => 650000,
                'category_id' => $segarCategory->id,
            ],
            [
                'image_path'  => 'products/bunga_segar/7.png',
                'name'        => 'Joyful Carnival Mixed Bouquet',
                'description' => 'Ledakan warna ceria dari paduan Mawar dan Anyelir (Carnation) berwarna merah, kuning, dan pink. Buket padat ini dibungkus simpel dengan craft paper cokelat, siap membawa kebahagiaan untuk momen perayaan apa pun.',
                'price'       => 280000,
                'category_id' => $segarCategory->id,
            ],

            // --- PHASE 2: BUNGA BOX (7 products) ---
            [
                'image_path'  => 'products/bunga_box/1.png',
                'name'        => 'Sapphire Velvet Hydrangea Bloom Box',
                'description' => 'Kotak bunga beludru (velvet) eksklusif berwarna biru dongker yang diisi dengan perpaduan mewah Mawar putih, Mawar peach, dan rimbunnya Hydrangea biru muda. Sangat anggun untuk hadiah lamaran atau kejutan ulang tahun berkelas.',
                'price'       => 550000,
                'category_id' => $boxCategory->id,
            ],
            [
                'image_path'  => 'products/bunga_box/2.png',
                'name'        => 'Golden Star Celebration Box',
                'description' => 'Rangkaian bunga pastel cantik di dalam box silinder anyaman alami, dilengkapi dengan balon foil berbentuk bintang emas yang meriah. Pilihan kado paling populer untuk merayakan kelulusan, promosi jabatan, atau pembukaan usaha baru.',
                'price'       => 420000,
                'category_id' => $boxCategory->id,
            ],
            [
                'image_path'  => 'products/bunga_box/3.png',
                'name'        => 'Midnight Majestic Purple Bloom Box',
                'description' => 'Ekspresi kemewahan sejati melalui perpaduan Mawar ungu tua dan Mawar putih bersih yang disusun rapat di dalam kotak hitam premium berhias pita satin. Hadiah sempurna untuk menunjukkan apresiasi mendalam kepada seseorang yang spesial.',
                'price'       => 480000,
                'category_id' => $boxCategory->id,
            ],
            [
                'image_path'  => 'products/bunga_box/4.png',
                'name'        => 'Emerald Forest Sunshine Box',
                'description' => 'Kombinasi kontras yang memukau antara Mawar kuning keemasan dengan tingginya bunga Delphinium biru cerah. Disusun dalam box silinder hijau tua yang memberikan kesan segar, alami, dan penuh energi positif.',
                'price'       => 500000,
                'category_id' => $boxCategory->id,
            ],
            [
                'image_path'  => 'products/bunga_box/5.png',
                'name'        => 'Orchid Luxe Velvet Cylinder',
                'description' => 'Rangkaian Anggrek Bulan pink keunguan berkelas dipadukan dengan Mawar krem lembut di dalam kotak beludru biru tua bergaris emas. Sebuah mahakarya florikultura yang sangat cocok sebagai pajangan meja VIP atau hadiah korporat.',
                'price'       => 750000,
                'category_id' => $boxCategory->id,
            ],
            [
                'image_path'  => 'products/bunga_box/9.png',
                'name'        => 'Infinite Love Red Rose Box',
                'description' => 'Simbol romantis abadi yang menghadirkan puluhan tangkai Mawar merah segar kualitas super, dirangkai padat membentuk kubah sempurna di dalam kotak hitam legam. Pilihan mutlak untuk momen Hari Valentine atau menyatakan cinta.',
                'price'       => 650000,
                'category_id' => $boxCategory->id,
            ],
            [
                'image_path'  => 'products/bunga_box/14.png',
                'name'        => 'Sweetheart Crimson Box',
                'description' => 'Rangkaian puluhan mawar merah premium yang disusun membentuk pola hati (heart-shaped) di dalam kotak hitam berbentuk hati yang romantis. Dilengkapi dengan aksen pita merah menyala, menjadikannya hadiah paling berkesan untuk melamar pasangan.',
                'price'       => 700000,
                'category_id' => $boxCategory->id,
            ],

            // --- PHASE 2: BUNGA PERAYAAN (4 products) ---
            [
                'image_path'  => 'products/bunga_perayaan/1.png',
                'name'        => 'Blush Lily Luxury Acrylic Box',
                'description' => 'Kotak hadiah akrilik transparan (clear glass look) yang menawan, melindungi rangkaian Bunga Lily pink dan Anyelir di atas dudukan beludru merah muda yang lembut. Diikat dengan pita satin emas, memberikan kesan kado yang sangat berharga dan eksklusif.',
                'price'       => 680000,
                'category_id' => $perayaanCategory->id,
            ],
            [
                'image_path'  => 'products/bunga_perayaan/2.png',
                'name'        => 'Amethyst Lavender Ceramic Bowl',
                'description' => 'Rangkaian bunga Mawar pink dan ungu yang rimbun dan merekah indah di dalam vas keramik bulat berwarna lavender murni. Desainnya yang membulat penuh sangat cantik untuk dijadikan hiasan tengah meja makan atau ruang tamu saat perayaan keluarga.',
                'price'       => 390000,
                'category_id' => $perayaanCategory->id,
            ],
            [
                'image_path'  => 'products/bunga_perayaan/3.png',
                'name'        => 'Grand Celebration Basket with Lily',
                'description' => 'Keranjang anyaman hitam premium yang penuh dengan kombinasi Mawar merah merona dan Lily putih yang harum menenangkan. Dihiasi pita marun besar yang megah, dirancang khusus sebagai hantaran formal atau ucapan selamat hari raya.',
                'price'       => 580000,
                'category_id' => $perayaanCategory->id,
            ],
            [
                'image_path'  => 'products/bunga_perayaan/4.png',
                'name'        => 'Eternal Gold & Crimson Rose Acrylic Case',
                'description' => 'Kotak akrilik premium berisi mawar merah pilihan yang dipadukan dengan mawar berwarna emas mewah (preserved flowers yang tahan bertahun-tahun). Hadiah masterpiece berskala luxury untuk kolektor atau momen anniversary yang sangat sakral.',
                'price'       => 950000,
                'category_id' => $perayaanCategory->id,
            ],

            // --- PHASE 3: BUNGA BUKET (3 products) ---
            [
                'image_path'  => 'products/bunga_buket/1.png',
                'name'        => 'Sweet Violet Bear Bouquet',
                'description' => 'Buket mawar ungu muda dan pink pastel yang sangat menggemaskan, dilengkapi dengan boneka beruang mini di tengahnya. Dibungkus dengan kertas wrapping ungu elegan bergaya Korea. Hadiah paling manis untuk sahabat atau pasangan tercinta.',
                'price'       => 320000,
                'category_id' => $buketPremiumCategory->id,
            ],
            [
                'image_path'  => 'products/bunga_buket/2.png',
                'name'        => 'Sunshine & Ocean Breeze Bouquet',
                'description' => 'Kombinasi cerah nan eksotis dari Bunga Matahari kuning yang memancarkan energi positif dan bunga Delphinium biru yang menjulang tinggi. Dibungkus dengan kertas craft rustic, sangat sempurna untuk buket kelulusan (wisuda).',
                'price'       => 290000,
                'category_id' => $buketPremiumCategory->id,
            ],
            [
                'image_path'  => 'products/bunga_buket/8.png',
                'name'        => 'Spring Awakening Tulip Bouquet',
                'description' => 'Buket eksklusif berisi bunga Tulip kuning segar kualitas impor yang dipadukan dengan pesona ungu dari bunga Iris. Rangkaian ini memberikan nuansa musim semi Eropa yang menyegarkan. Sangat cocok untuk kado Hari Ibu.',
                'price'       => 450000,
                'category_id' => $buketPremiumCategory->id,
            ],

            // --- PHASE 3: BUNGA MEJA (3 products) ---
            [
                'image_path'  => 'products/bunga_meja/4.png',
                'name'        => 'Sapphire Cloud Ceramic Vase',
                'description' => 'Bunga meja elegan dengan perpaduan Lily putih bersih dan rimbunnya Hydrangea biru di dalam vas keramik putih minimalis. Membawa nuansa tenang, mewah, dan sejuk ke dalam ruang tamu atau meja resepsionis kantor Anda.',
                'price'       => 520000,
                'category_id' => $bungaMejaCategory->id,
            ],
            [
                'image_path'  => 'products/bunga_meja/9.png',
                'name'        => 'Rainbow Tulip Glass Vase',
                'description' => 'Rangkaian bunga Tulip aneka warna (kuning, merah muda, ungu) yang disusun rapi menawan di dalam vas kaca bening dengan air segar. Siap mencerahkan suasana ruangan seketika dengan pesona warna-warninya.',
                'price'       => 550000,
                'category_id' => $bungaMejaCategory->id,
            ],
            [
                'image_path'  => 'products/bunga_meja/11.png',
                'name'        => 'Peach Dynasty Porcelain Vase',
                'description' => 'Rangkaian mewah Mawar warna peach dan salem yang mekar sempurna, disajikan secara estetik di dalam vas porselen biru putih bergaya klasik (Chinoiserie). Memberikan kesan aristokrat dan sangat cocok untuk dekorasi rumah klasik.',
                'price'       => 780000,
                'category_id' => $bungaMejaCategory->id,
            ],

            // --- PHASE 3: BUNGA HIAS (3 products) ---
            [
                'image_path'  => 'products/bunga_hias/1.png',
                'name'        => 'Monstera Adansonii Woven Pot',
                'description' => 'Tanaman hias indoor Monstera Adansonii (Janda Bolong) yang menjuntai cantik nan estetik. Ditanam dalam pot anyaman rotan natural bergaya boho. Perawatannya sangat mudah, cocok untuk dekorasi meja cafe atau sudut meja kerja.',
                'price'       => 180000,
                'category_id' => $tanamanHiasCategory->id,
            ],
            [
                'image_path'  => 'products/bunga_hias/11.png',
                'name'        => 'Golden Geometric Terrarium',
                'description' => 'Ekosistem mini dalam wadah kaca geometris bersudut emas premium. Berisi perpaduan tanaman Tillandsia (air plants) dan sukulen eksotis. Dekorasi modern bergaya industrial yang tidak memakan banyak tempat namun sangat memanjakan mata.',
                'price'       => 350000,
                'category_id' => $tanamanHiasCategory->id,
            ],
            [
                'image_path'  => 'products/bunga_hias/16.png',
                'name'        => 'Crystal Clear White Orchid',
                'description' => 'Anggrek Bulan (Phalaenopsis) putih suci bercabang dua yang mekar anggun, ditanam secara estetik di dalam pot kaca kotak transparan berisi batu hias alami. Melambangkan kemurnian, keanggunan, dan kemewahan yang tahan lama.',
                'price'       => 650000,
                'category_id' => $tanamanHiasCategory->id,
            ],
        ];

        // Build a set of curated image paths for quick lookup
        $curatedPaths = collect($curatedProducts)->pluck('image_path')->toArray();

        // ==========================================
        // STEP 4: INSERT 32 CURATED PRODUCTS
        // ==========================================
        $curatedCount = 0;
        foreach ($curatedProducts as $item) {
            $slug = Str::slug($item['name']) . '-' . Str::random(5);
            $randomShopId = $approvedShopIds[array_rand($approvedShopIds)];

            Product::create([
                'shop_id'            => $randomShopId,
                'category_id'        => $item['category_id'],
                'name'               => $item['name'],
                'slug'               => $slug,
                'description'        => $item['description'],
                'price'              => $item['price'],
                'image_path'         => $item['image_path'],
                'is_active'          => 1,
                'is_hidden_by_admin' => 0,
            ]);
            $curatedCount++;
        }

        $this->command->info("📝 Inserted {$curatedCount} hand-crafted curated products.");

        // ==========================================
        // STEP 5: SCAN & AUTO-GENERATE REMAINING PRODUCTS
        // ==========================================
        $basePath = storage_path('app/public/products');
        $autoCount = 0;
        $usedNames = []; // Track used names to avoid duplicates within this run

        if (File::isDirectory($basePath)) {
            $folders = File::directories($basePath);

            foreach ($folders as $folderPath) {
                $folderName = basename($folderPath);
                $files = File::files($folderPath);

                foreach ($files as $file) {
                    $extension = strtolower($file->getExtension());
                    if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                        continue;
                    }

                    $relPath = 'products/' . $folderName . '/' . $file->getFilename();

                    // Skip if this image was already covered by our curated products
                    if (in_array($relPath, $curatedPaths)) {
                        continue;
                    }

                    // Auto-generate product data
                    $name = $this->pickUniqueName($folderName, $usedNames);
                    $usedNames[] = $name;

                    $description = $this->pickDescription($folderName);
                    $price = $this->generatePrice();
                    $categoryId = $folderCategoryMap[$folderName] ?? $tanamanHiasCategory->id;
                    $slug = Str::slug($name) . '-' . Str::random(5);
                    $randomShopId = $approvedShopIds[array_rand($approvedShopIds)];

                    Product::create([
                        'shop_id'            => $randomShopId,
                        'category_id'        => $categoryId,
                        'name'               => $name,
                        'slug'               => $slug,
                        'description'        => $description,
                        'price'              => $price,
                        'image_path'         => $relPath,
                        'is_active'          => 1,
                        'is_hidden_by_admin' => 0,
                    ]);
                    $autoCount++;
                }
            }
        }

        $this->command->info("🤖 Auto-generated {$autoCount} additional products from remaining images.");

        // ==========================================
        // STEP 6: FINAL VERIFICATION
        // ==========================================
        $totalProducts = Product::count();
        $productsOnApprovedShops = Product::whereIn('shop_id', $approvedShopIds)->count();

        // Count total images in storage
        $totalImages = 0;
        if (File::isDirectory($basePath)) {
            foreach (File::directories($basePath) as $dir) {
                foreach (File::files($dir) as $f) {
                    $ext = strtolower($f->getExtension());
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                        $totalImages++;
                    }
                }
            }
        }

        $this->command->newLine();
        $this->command->info("✅ Seeding complete!");
        $this->command->info("   Hand-crafted: {$curatedCount} products");
        $this->command->info("   Auto-generated: {$autoCount} products");
        $this->command->info("   Total in DB: {$totalProducts} products");
        $this->command->info("   Total images in storage: {$totalImages}");
        $this->command->info("   On approved shops: {$productsOnApprovedShops}/{$totalProducts}");

        if ($totalProducts === $totalImages) {
            $this->command->info("🎉 Perfect match! Every image has a product entry.");
        } else {
            $this->command->warn("⚠️  Mismatch: {$totalProducts} products vs {$totalImages} images. Please investigate.");
        }

        if ($productsOnApprovedShops === $totalProducts) {
            $this->command->info("🎉 All products are correctly assigned to approved shops!");
        } else {
            $this->command->error("⚠️  Some products are NOT on approved shops.");
        }
    }

    /**
     * Pick a unique product name from the auto-generation pool for the given folder.
     */
    private function pickUniqueName(string $folder, array $usedNames): string
    {
        $pool = $this->autoNamePools[$folder] ?? $this->autoNamePools['bunga_hias'];

        // Find names not yet used
        $available = array_diff($pool, $usedNames);

        if (!empty($available)) {
            return $available[array_rand($available)];
        }

        // All names used — append a unique suffix
        $baseName = $pool[array_rand($pool)];
        $suffixes = [
            'Edisi Spesial', 'Exclusive Series', 'Limited Edition', 'Best Seller',
            'New Arrival', 'Signature Collection', 'Deluxe', 'Premium Grade',
            'Koleksi Baru', 'Top Pick', 'Seasonal Favorite', 'Customer Choice',
            'Flash Sale', 'Edisi Terbatas', 'Super Deal', 'Hot Item',
        ];
        $suffix = $suffixes[array_rand($suffixes)];

        $candidate = "{$baseName} - {$suffix}";

        // Ensure the suffixed name is also unique
        $attempt = 0;
        while (in_array($candidate, $usedNames) && $attempt < 50) {
            $suffix = $suffixes[array_rand($suffixes)] . ' ' . Str::random(3);
            $candidate = "{$baseName} - {$suffix}";
            $attempt++;
        }

        return $candidate;
    }

    /**
     * Pick a random description from the pool for the given folder.
     */
    private function pickDescription(string $folder): string
    {
        $pool = $this->autoDescPools[$folder] ?? $this->autoDescPools['bunga_hias'];
        return $pool[array_rand($pool)];
    }

    /**
     * Generate a realistic price between 150,000 and 1,500,000, rounded to nearest 5,000.
     */
    private function generatePrice(): int
    {
        $raw = rand(150000, 1500000);
        return (int) (round($raw / 5000) * 5000);
    }
}
