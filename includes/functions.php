<?php
/**
 * Fastender Pre-Order Platform
 * File Fungsi Utama & Helper (includes/functions.php)
 * PHP Native Functions
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

// Path data JSON untuk fallback jika MySQL belum diimport/aktif
define('DATA_PRODUCTS_FILE', __DIR__ . '/../data/products.json');
define('DATA_ORDERS_FILE', __DIR__ . '/../data/orders.json');
define('UPLOAD_DIR', __DIR__ . '/../uploads/products/');

// ----------------------------------------------------
// 1. HELPER FORMAT RUPIAH & TEKS
// ----------------------------------------------------
function formatRupiah($amount) {
    return 'Rp ' . number_format((int)$amount, 0, ',', '.');
}

function sanitize($data) {
    return htmlspecialchars(trim((string)$data), ENT_QUOTES, 'UTF-8');
}

// ----------------------------------------------------
// 2. HELPER AUTENTIKASI ADMIN
// ----------------------------------------------------
function isAdminLoggedIn() {
    return !empty($_SESSION['fastender_admin_logged_in']) && $_SESSION['fastender_admin_logged_in'] === true;
}

function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        header('Location: admin-login.php');
        exit;
    }
}

// ----------------------------------------------------
// 3. PENGELOLAAN DATA PRODUK PRE-ORDER
// ----------------------------------------------------

/**
 * Mengambil semua produk yang tersimpan (dari DB MySQL atau fallback file JSON)
 */
function getProductsList() {
    $pdo = getDbConnection();
    if ($pdo) {
        try {
            $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
            $products = $stmt->fetchAll();
            if (!empty($products)) {
                foreach ($products as &$p) {
                    // Ambil foto-foto produk
                    $imgStmt = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, id ASC");
                    $imgStmt->execute([$p['id']]);
                    $images = $imgStmt->fetchAll(PDO::FETCH_COLUMN);
                    $p['images'] = $images ?: [];
                    $p['specs'] = !empty($p['specs']) ? explode(',', $p['specs']) : [];
                    $p['sizes'] = !empty($p['sizes']) ? explode(',', $p['sizes']) : ['S', 'M', 'L', 'XL', 'XXL', '3XL'];
                    $p['colors'] = !empty($p['colors']) ? explode(',', $p['colors']) : ['Navy', 'Hitam', 'Maroon', 'Putih'];
                }
                return $products;
            }
        } catch (Exception $e) {
            // Lanjut ke fallback
        }
    }

    // Fallback file JSON
    if (file_exists(DATA_PRODUCTS_FILE)) {
        $json = file_get_contents(DATA_PRODUCTS_FILE);
        $data = json_decode($json, true);
        if (is_array($data) && !empty($data)) {
            return $data;
        }
    }

    // Default template jika belum ada produk (Auto-load katalog marketplace)
    $demos = getDefaultDemoProducts();
    if (!empty($demos)) {
        @file_put_contents(DATA_PRODUCTS_FILE, json_encode($demos, JSON_PRETTY_PRINT));
        return $demos;
    }

    return [];
}

/**
 * Mengambil 1 produk berdasarkan ID
 */
function getProductById($id) {
    $id = (int)$id;
    $all = getProductsList();
    foreach ($all as $item) {
        if ((int)$item['id'] === $id) {
            return $item;
        }
    }

    // Jika produk belum diinput, cek demo template jika diminta
    $demos = getDefaultDemoProducts();
    foreach ($demos as $demo) {
        if ((int)$demo['id'] === $id) {
            return $demo;
        }
    }

    return null;
}

/**
 * Menyimpan produk baru beserta multi-foto
 */
function saveNewProduct($postData, $filesData = null) {
    $pdo = getDbConnection();
    
    $name = sanitize($postData['name'] ?? '');
    $subtitle = sanitize($postData['subtitle'] ?? '');
    $category = sanitize($postData['category'] ?? 'jaket');
    $categoryNames = [
        'jaket' => 'Jaket & Varsity',
        'pdh' => 'Kemeja PDH',
        'hoodie' => 'Hoodie & Sweatshirt',
        'kaos' => 'Kaos Angkatan',
        'merch' => 'Merchandise & Aksesoris'
    ];
    $categoryName = $categoryNames[$category] ?? 'Apparel PO';
    $price = (int)($postData['price'] ?? 0);
    $dpPrice = (int)($postData['dp_price'] ?? round($price * 0.5));
    $quotaTarget = (int)($postData['quota_target'] ?? 100);
    $quotaCurrent = (int)($postData['quota_current'] ?? 0);
    $batch = sanitize($postData['batch'] ?? 'Batch 1');
    $badge = sanitize($postData['badge'] ?? 'PO Baru');
    $desc = sanitize($postData['description'] ?? 'Apparel pre-order berkualitas tinggi dengan standar mutu Fastender.');
    $specs = sanitize($postData['specs'] ?? 'Bahan Standar Distro, Bordir Komputer HD');
    $iconType = sanitize($postData['icon_type'] ?? 'varsity');
    
    // Proses Upload File Multi-Foto
    $savedImages = [];
    
    // 1. Cek unggahan file via PHP $_FILES
    if (!empty($filesData['name']) && is_array($filesData['name'])) {
        if (!is_dir(UPLOAD_DIR)) {
            mkdir(UPLOAD_DIR, 0777, true);
        }
        
        $totalFiles = count($filesData['name']);
        for ($i = 0; $i < $totalFiles; $i++) {
            if ($filesData['error'][$i] === UPLOAD_ERR_OK) {
                $tmpName = $filesData['tmp_name'][$i];
                $originalName = basename($filesData['name'][$i]);
                $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                    $newFileName = 'prod_' . time() . '_' . uniqid() . '.' . $ext;
                    $targetPath = UPLOAD_DIR . $newFileName;
                    if (move_uploaded_file($tmpName, $targetPath)) {
                        $savedImages[] = 'uploads/products/' . $newFileName;
                    }
                }
            }
        }
    }

    // 2. Cek juga jika ada base64 photos dari drag-and-drop / dropzone JS
    if (!empty($postData['base64_images'])) {
        $b64List = json_decode($postData['base64_images'], true);
        if (is_array($b64List)) {
            if (!is_dir(UPLOAD_DIR)) {
                mkdir(UPLOAD_DIR, 0777, true);
            }
            foreach ($b64List as $b64) {
                if (preg_match('/^data:image\/(\w+);base64,/', $b64, $type)) {
                    $b64Data = substr($b64, strpos($b64, ',') + 1);
                    $ext = strtolower($type[1]);
                    if ($ext === 'jpeg') $ext = 'jpg';
                    $decoded = base64_decode($b64Data);
                    if ($decoded !== false) {
                        $newFileName = 'prod_' . time() . '_' . uniqid() . '.' . $ext;
                        file_put_contents(UPLOAD_DIR . $newFileName, $decoded);
                        $savedImages[] = 'uploads/products/' . $newFileName;
                    }
                }
            }
        }
    }

    $newId = time();

    // Simpan ke MySQL jika PDO aktif
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO products 
                (name, subtitle, category, category_name, price, dp_price, quota_current, quota_target, badge, batch, description, specs, icon_type) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $name, $subtitle, $category, $categoryName, $price, $dpPrice, 
                $quotaCurrent, $quotaTarget, $badge, $batch, $desc, $specs, $iconType
            ]);
            $newId = (int)$pdo->lastInsertId();

            // Simpan relasi foto
            if (!empty($savedImages)) {
                $imgStmt = $pdo->prepare("INSERT INTO product_images (product_id, image_path, is_primary) VALUES (?, ?, ?)");
                foreach ($savedImages as $idx => $path) {
                    $isPrimary = ($idx === 0) ? 1 : 0;
                    $imgStmt->execute([$newId, $path, $isPrimary]);
                }
            }
        } catch (Exception $e) {
            // fallback simpan ke JSON jika query gagal
        }
    }

    // Selalu simpan juga ke file JSON data/products.json sebagai cache / fallback
    $all = getProductsList();
    $newProduct = [
        'id' => $newId,
        'name' => $name,
        'subtitle' => $subtitle,
        'category' => $category,
        'category_name' => $categoryName,
        'price' => $price,
        'dp_price' => $dpPrice,
        'quota_current' => $quotaCurrent,
        'quota_target' => $quotaTarget,
        'badge' => $badge,
        'batch' => $batch,
        'description' => $desc,
        'specs' => explode(',', $specs),
        'sizes' => ['S', 'M', 'L', 'XL', 'XXL', '3XL'],
        'colors' => ['Navy', 'Hitam', 'Maroon', 'Putih'],
        'icon_type' => $iconType,
        'images' => $savedImages
    ];
    array_unshift($all, $newProduct);
    file_put_contents(DATA_PRODUCTS_FILE, json_encode($all, JSON_PRETTY_PRINT));

    return $newId;
}

/**
 * Hapus produk berdasarkan ID
 */
function deleteProductById($id) {
    $id = (int)$id;
    $pdo = getDbConnection();
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);
        } catch (Exception $e) {}
    }

    $all = getProductsList();
    $filtered = array_values(array_filter($all, function($p) use ($id) {
        return (int)$p['id'] !== $id;
    }));
    file_put_contents(DATA_PRODUCTS_FILE, json_encode($filtered, JSON_PRETTY_PRINT));
}

/**
 * Kosongkan semua produk (Mengaktifkan mode "Space Kosongan")
 */
function clearAllProductsData() {
    $pdo = getDbConnection();
    if ($pdo) {
        try {
            $pdo->exec("DELETE FROM product_images");
            $pdo->exec("DELETE FROM products");
        } catch (Exception $e) {}
    }
    file_put_contents(DATA_PRODUCTS_FILE, json_encode([], JSON_PRETTY_PRINT));
}

/**
 * Muat produk demo bawaan ke sistem
 */
function loadDemoProductsData() {
    $demos = getDefaultDemoProducts();
    file_put_contents(DATA_PRODUCTS_FILE, json_encode($demos, JSON_PRETTY_PRINT));
}

// ----------------------------------------------------
// 4. DATA TEMPLATE PRODUK DEMO
// ----------------------------------------------------
function getDefaultDemoProducts() {
    return [
        [
            'id' => 1,
            'name' => 'Varsity Jacket Premium Angkatan 2026',
            'subtitle' => 'Custom Bordir Komputer Nama, NIM, & Logo Jurusan',
            'category' => 'jaket',
            'category_name' => 'Jaket & Varsity',
            'price' => 245000,
            'original_price' => 290000,
            'dp_price' => 122500,
            'rating' => 4.9,
            'sold_count' => 142,
            'quota_current' => 82,
            'quota_target' => 100,
            'badge' => 'Best Seller',
            'batch' => 'Batch 2 (Tutup 25 Okt)',
            'description' => 'Varsity jacket berbahan Fleece Cotton tebal 330gsm kombinasi kulit sintetis premium pada lengan. Dilengkapi furing quilting diamond yang hangat dan nyaman, kancing snap metal anti karat, dan bordir komputer kerapatan tinggi.',
            'specs' => ['Bahan Badan: Cotton Fleece 330gsm', 'Bahan Lengan: Oscar Leather Soft Grade A', 'Bordir: 4 Titik Bordir Komputer Full HD', 'Furing: Quilting Diamond Dacron 4oz', 'Estimasi Produksi: 18 - 21 Hari'],
            'sizes' => ['S', 'M', 'L', 'XL', 'XXL', '3XL'],
            'colors' => ['Navy - White', 'Black - Grey', 'Maroon - Cream'],
            'icon_type' => 'varsity',
            'images' => []
        ],
        [
            'id' => 2,
            'name' => 'Kemeja PDH Drill Custom Bordir Logo',
            'subtitle' => 'Bahan American Drill Original 1919 Tebal & Adem',
            'category' => 'pdh',
            'category_name' => 'Kemeja PDH',
            'price' => 135000,
            'original_price' => 160000,
            'dp_price' => 67500,
            'rating' => 4.8,
            'sold_count' => 98,
            'quota_current' => 64,
            'quota_target' => 80,
            'badge' => 'Bordir Presisi',
            'batch' => 'Batch 1 (Tutup 20 Okt)',
            'description' => 'Kemeja PDH/PDL resmi organisasi dan angkatan. Menggunakan bahan American Drill 1919 asli yang tidak berbulu dan adem dipakai seharian. Jahitan rantai ganda kuat standar konveksi profesional.',
            'specs' => ['Bahan: Original American Drill 1919', 'Bordir: Komputer Tajam 3 Titik', 'Jahitan: Rantai Tiga Benang Kuat', 'Kancing: Eksklusif Emboss', 'Estimasi Produksi: 14 - 17 Hari'],
            'sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
            'colors' => ['Navy Blue', 'Hitam Solid', 'Abu Khaki'],
            'icon_type' => 'shirt',
            'images' => []
        ],
        [
            'id' => 3,
            'name' => 'Hoodie Heavyweight Fleece 330gsm 2026',
            'subtitle' => 'Double Layered Hood dengan Sablon High Density',
            'category' => 'hoodie',
            'category_name' => 'Hoodie & Sweatshirt',
            'price' => 185000,
            'original_price' => 220000,
            'dp_price' => 92500,
            'rating' => 4.9,
            'sold_count' => 175,
            'quota_current' => 92,
            'quota_target' => 100,
            'badge' => 'Sisa 8 Slot',
            'batch' => 'Batch 3 (Segera Tutup)',
            'description' => 'Hoodie kasual angkatan dengan bahan Cotton Fleece heavyweight tebal tanpa campuran poliester kasar. Bagian dalam lembut, saku kanguru presisi, dan tali hoodie anyaman tebal.',
            'specs' => ['Bahan: 100% Cotton Fleece 330gsm', 'Sablon: Plastisol High Density Timbul', 'Hoodie: Double Layer Tebal', 'Rib: Elastis Spandex Anti Melar', 'Estimasi Produksi: 14 Hari'],
            'sizes' => ['S', 'M', 'L', 'XL', 'XXL', '3XL'],
            'colors' => ['Deep Navy', 'Jet Black', 'Forest Green'],
            'icon_type' => 'hoodie',
            'images' => []
        ],
        [
            'id' => 4,
            'name' => 'T-Shirt Cotton Combed 24s Sablon Plastisol',
            'subtitle' => 'Kaos Makrab & Event Angkatan Nyaman & Sejuk',
            'category' => 'kaos',
            'category_name' => 'Kaos Angkatan',
            'price' => 95000,
            'original_price' => 115000,
            'dp_price' => 47500,
            'rating' => 4.8,
            'sold_count' => 210,
            'quota_current' => 140,
            'quota_target' => 150,
            'badge' => 'Paling Laris',
            'batch' => 'Batch 1 (Tutup 30 Okt)',
            'description' => 'Kaos angkatan yang pas untuk kegiatan santai, makrab, atau gathering. Terbuat dari katun combed 24s reaktif gramasi pas, menyerap keringat maksimal, dan disablon dengan tinta plastisol premium.',
            'specs' => ['Bahan: Cotton Combed 24s Reaktif', 'Sablon: Plastisol Curing Oven Glossy', 'Jahitan: Standar Distro Rantai Pundak', 'Model: Reguler Fit Unisex', 'Estimasi Produksi: 10 - 12 Hari'],
            'sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
            'colors' => ['Biru Navy', 'Hitam', 'Putih', 'Sage Green'],
            'icon_type' => 'tshirt',
            'images' => []
        ],
        [
            'id' => 5,
            'name' => 'Coach Jacket Waterproof Taslan Angkatan',
            'subtitle' => 'Jaket Outdoor Windproof dengan Furing Katun Adem',
            'category' => 'jaket',
            'category_name' => 'Jaket & Varsity',
            'price' => 175000,
            'original_price' => 210000,
            'dp_price' => 87500,
            'rating' => 4.9,
            'sold_count' => 65,
            'quota_current' => 48,
            'quota_target' => 60,
            'badge' => 'Tahan Angin',
            'batch' => 'Batch 1 (Tutup 28 Okt)',
            'description' => 'Coach jacket multifungsi untuk kegiatan lapangan atau berkendara. Berbahan Taslan Milky kedap angin dan percikan air, dengan furing katun hero yang sejuk.',
            'specs' => ['Bahan Luar: Taslan Milky Waterproof', 'Bahan Dalam: Katun Hero Lembut', 'Kancing: Snap Button Matte', 'Tali Kerut: Bawah dengan Stopper', 'Estimasi Produksi: 14 - 16 Hari'],
            'sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
            'colors' => ['Black Obsidian', 'Navy Blue', 'Army Olive'],
            'icon_type' => 'varsity',
            'images' => []
        ],
        [
            'id' => 6,
            'name' => 'Polo Shirt Lacoste Pique Bordir Eksklusif',
            'subtitle' => 'Kerah Berdiri Kokoh dengan Bordir Dada & Lengan',
            'category' => 'pdh',
            'category_name' => 'Kemeja PDH',
            'price' => 115000,
            'original_price' => 135000,
            'dp_price' => 57500,
            'rating' => 4.8,
            'sold_count' => 88,
            'quota_current' => 55,
            'quota_target' => 70,
            'badge' => 'Bahan Adem',
            'batch' => 'Batch 2 (Tutup 30 Okt)',
            'description' => 'Kaos polo semi formal berbahan Lacoste Pique katun tebal dengan rajutan pori heksagonal yang rapi. Cocok untuk seragam divisi, panitia, atau seminar angkatan.',
            'specs' => ['Bahan: Cotton Lacoste Pique 20s', 'Kerah: Rib Rajut Anti Melar', 'Kancing: 3 Baris Plaket Rapih', 'Bordir: Bordir Komputer 2 Titik', 'Estimasi Produksi: 12 - 14 Hari'],
            'sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
            'colors' => ['Navy Gold', 'White Navy', 'Black Silver'],
            'icon_type' => 'shirt',
            'images' => []
        ]
    ];
}

// ----------------------------------------------------
// 5. PENGELOLAAN PESANAN (ORDERS)
// ----------------------------------------------------
function getAllOrdersList() {
    $pdo = getDbConnection();
    if ($pdo) {
        try {
            $stmt = $pdo->query("SELECT * FROM orders ORDER BY id DESC");
            $orders = $stmt->fetchAll();
            if (!empty($orders)) {
                return $orders;
            }
        } catch (Exception $e) {}
    }

    if (file_exists(DATA_ORDERS_FILE)) {
        $json = file_get_contents(DATA_ORDERS_FILE);
        $data = json_decode($json, true);
        if (is_array($data)) return $data;
    }

    // Default sample orders
    return [
        [
            'order_code' => 'PO-2026-8891',
            'customer_name' => 'Ahmad Fauzi',
            'organization' => 'Himpunan Informatika',
            'customer_phone' => '081234567890',
            'customer_email' => 'fauzi@example.com',
            'date' => '12 Okt 2026',
            'product_summary' => 'Varsity Jacket Angkatan (L, Navy)',
            'qty' => 24,
            'total_price' => 5895000,
            'payment_status' => 'DP 50% Lunas',
            'production_status' => 'Sedang Produksi'
        ],
        [
            'order_code' => 'PO-2026-1045',
            'customer_name' => 'Siti Rahma',
            'organization' => 'BEM FT Undip',
            'customer_phone' => '081398765432',
            'customer_email' => 'siti@example.com',
            'date' => '15 Okt 2026',
            'product_summary' => 'Kemeja PDH Drill 1919 (M, Navy)',
            'qty' => 20,
            'total_price' => 2715000,
            'payment_status' => 'Lunas 100%',
            'production_status' => 'Dalam Pengiriman'
        ],
        [
            'order_code' => 'PO-2026-7734',
            'customer_name' => 'Dewi Lestari',
            'organization' => 'Manajemen Bisnis',
            'customer_phone' => '085712345678',
            'customer_email' => 'dewi@example.com',
            'date' => '17 Okt 2026',
            'product_summary' => 'Tote Bag Canvas + Pin (3 pcs)',
            'qty' => 3,
            'total_price' => 180000,
            'payment_status' => 'Lunas 100%',
            'production_status' => 'Quality Check'
        ],
        [
            'order_code' => 'PO-2026-4412',
            'customer_name' => 'Rian Hidayat',
            'organization' => 'Teknik Sipil 2026',
            'customer_phone' => '082187654321',
            'customer_email' => 'rian@example.com',
            'date' => '18 Okt 2026',
            'product_summary' => 'T-Shirt Combed 24s (L, Navy)',
            'qty' => 12,
            'total_price' => 1155000,
            'payment_status' => 'DP 50% Lunas',
            'production_status' => 'Sedang Produksi'
        ],
        [
            'order_code' => 'PO-2026-3390',
            'customer_name' => 'Budi Santoso',
            'organization' => 'Teknik Mesin',
            'customer_phone' => '081912349999',
            'customer_email' => 'budi@example.com',
            'date' => '20 Okt 2026',
            'product_summary' => 'Hoodie Heavyweight Fleece (XXL)',
            'qty' => 1,
            'total_price' => 200000,
            'payment_status' => 'Lunas 100%',
            'production_status' => 'Selesai'
        ]
    ];
}

// ----------------------------------------------------
// 6. SVG MOCKUP HELPER (PHP RENDER)
// ----------------------------------------------------
function getProductSvgPhp($type) {
    switch ($type) {
        case 'varsity':
            return '<svg viewBox="0 0 160 160" width="100%" height="100%" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect width="160" height="160" rx="16" fill="#eff6ff"/>
              <path d="M45 42L80 56L115 42L135 68L120 82L112 74V130H48V74L40 82L25 68L45 42Z" fill="#1e3a8a"/>
              <path d="M45 42L25 68L40 82L48 74V60L45 42Z" fill="#f97316"/>
              <path d="M115 42L135 68L120 82L112 74V60L115 42Z" fill="#f97316"/>
              <path d="M78 55V130H82V55H78Z" fill="#ffffff" opacity="0.4"/>
              <circle cx="80" cy="75" r="2.5" fill="#f97316"/>
              <circle cx="80" cy="90" r="2.5" fill="#f97316"/>
              <circle cx="80" cy="105" r="2.5" fill="#f97316"/>
              <circle cx="80" cy="120" r="2.5" fill="#f97316"/>
              <text x="60" y="80" font-family="Poppins, sans-serif" font-weight="800" font-size="14" fill="#ffffff">26</text>
            </svg>';
        case 'shirt':
            return '<svg viewBox="0 0 160 160" width="100%" height="100%" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect width="160" height="160" rx="16" fill="#f0fdf4"/>
              <path d="M48 38L80 48L112 38L130 62L116 74L108 68V132H52V68L44 74L30 62L48 38Z" fill="#1e3a8a"/>
              <path d="M68 38L80 54L92 38H68Z" fill="#f8fafc"/>
              <rect x="58" y="72" width="16" height="18" rx="2" fill="#172554"/>
              <circle cx="80" cy="70" r="2" fill="#cbd5e1"/>
              <circle cx="80" cy="85" r="2" fill="#cbd5e1"/>
              <circle cx="80" cy="100" r="2" fill="#cbd5e1"/>
              <circle cx="80" cy="115" r="2" fill="#cbd5e1"/>
              <path d="M79 48V132H81V48H79Z" fill="#ffffff" opacity="0.3"/>
            </svg>';
        case 'hoodie':
            return '<svg viewBox="0 0 160 160" width="100%" height="100%" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect width="160" height="160" rx="16" fill="#f8fafc"/>
              <ellipse cx="80" cy="40" rx="26" ry="16" fill="#172554"/>
              <path d="M46 44L80 50L114 44L134 74L118 84L110 76V130H50V76L42 84L26 74L46 44Z" fill="#1e3a8a"/>
              <path d="M60 100H100L96 124H64L60 100Z" fill="#172554"/>
              <path d="M74 54V76M86 54V76" stroke="#f97316" stroke-width="2.5" stroke-linecap="round"/>
              <text x="80" y="85" text-anchor="middle" font-family="Poppins, sans-serif" font-weight="700" font-size="10" fill="#ffffff" letter-spacing="1">FASTENDER</text>
            </svg>';
        case 'tshirt':
            return '<svg viewBox="0 0 160 160" width="100%" height="100%" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect width="160" height="160" rx="16" fill="#fff7ed"/>
              <path d="M52 40L80 48L108 40L126 62L112 72L106 66V130H54V66L48 72L34 62L52 40Z" fill="#1e3a8a"/>
              <path d="M70 40C70 46 90 46 90 40Z" fill="#fff7ed"/>
              <rect x="65" y="70" width="30" height="20" rx="3" fill="#f97316"/>
              <text x="80" y="84" text-anchor="middle" font-family="Poppins, sans-serif" font-weight="800" font-size="8" fill="#ffffff">PO 2026</text>
            </svg>';
        default:
            return '<svg viewBox="0 0 160 160" width="100%" height="100%" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect width="160" height="160" rx="16" fill="#eff6ff"/>
              <path d="M52 40L80 48L108 40L126 62L112 72L106 66V130H54V66L48 72L34 62L52 40Z" fill="#1e3a8a"/>
            </svg>';
    }
}

// ----------------------------------------------------
// 8. PENGELOLAAN 3D SHOWCASE (PNG TO 3D MODEL)
// ----------------------------------------------------
define('DATA_SHOWCASE_3D_FILE', __DIR__ . '/../data/showcase_3d.json');
define('UPLOAD_3D_DIR', __DIR__ . '/../uploads/3d/');

function get3DShowcaseConfig() {
    if (file_exists(DATA_SHOWCASE_3D_FILE)) {
        $json = file_get_contents(DATA_SHOWCASE_3D_FILE);
        $data = json_decode($json, true);
        if (is_array($data) && !empty($data['image_url'])) {
            return $data;
        }
    }
    return [
        'image_url' => 'assets/images/logo.png',
        'title' => 'Emblem Resmi FastTender 2026',
        'subtitle' => 'Convert PNG to 3D Realtime • Berotasi Pelan 360°',
        'depth' => 8,
        'rotation_speed' => 0.006,
        'metalness' => 0.35,
        'roughness' => 0.3,
        'updated_at' => date('Y-m-d H:i:s')
    ];
}

function save3DShowcaseConfig($postData, $fileData = null) {
    if (!is_dir(UPLOAD_3D_DIR)) {
        mkdir(UPLOAD_3D_DIR, 0777, true);
    }
    
    $current = get3DShowcaseConfig();
    $imageUrl = $current['image_url'];
    
    // Upload via $_FILES
    if (!empty($fileData['name']) && $fileData['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($fileData['name'], PATHINFO_EXTENSION));
        if ($ext === 'png') {
            $fileName = 'model_3d_' . time() . '.png';
            $targetPath = UPLOAD_3D_DIR . $fileName;
            if (move_uploaded_file($fileData['tmp_name'], $targetPath)) {
                $imageUrl = 'uploads/3d/' . $fileName;
            }
        }
    } 
    // Atau upload via Base64 DataURL (Drag & Drop AJAX)
    elseif (!empty($postData['image_base64'])) {
        $base64 = $postData['image_base64'];
        if (preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
            $data = substr($base64, strpos($base64, ',') + 1);
            $data = base64_decode($data);
            if ($data !== false) {
                $fileName = 'model_3d_' . time() . '.png';
                file_put_contents(UPLOAD_3D_DIR . $fileName, $data);
                $imageUrl = 'uploads/3d/' . $fileName;
            }
        }
    }
    
    $config = [
        'image_url' => $imageUrl,
        'title' => sanitize($postData['title'] ?? ($current['title'] ?? '3D Apparel FastTender')),
        'subtitle' => sanitize($postData['subtitle'] ?? ($current['subtitle'] ?? 'Desain 3D Pre-Order Terkini')),
        'depth' => floatval($postData['depth'] ?? 8),
        'rotation_speed' => floatval($postData['rotation_speed'] ?? 0.006),
        'metalness' => floatval($postData['metalness'] ?? 0.35),
        'roughness' => floatval($postData['roughness'] ?? 0.3),
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    file_put_contents(DATA_SHOWCASE_3D_FILE, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    return $config;
}

