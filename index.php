<?php
/**
 * Fastender Pre-Order Platform
 * Halaman Beranda (index.php)
 * PHP Native
 */
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Fastender - Pre-Order Apparel & Merchandise Angkatan 2026';
$activePage = 'beranda';

$allProducts = getProductsList();
$popularProducts = array_slice($allProducts, 0, 4);
$showcase3D = get3DShowcaseConfig();

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
?>

<!-- ==========================================
     HERO BANNER
     ========================================== -->
<section class="hero-section">
  <div class="container">
    <div class="hero-banner">
      <div class="hero-content">
        <span class="hero-badge">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
          </svg>
          BATCH PERDANA TAHUN AKADEMIK
        </span>
        <h1 class="hero-title">
          PO Angkatan 2026<br><span>Sudah Dibuka!</span>
        </h1>
        <p class="hero-desc">
          Wujudkan identitas kebanggaan kelas, jurusan, dan organisasimu bersama FastTender. Bahan standar distro, gratis konsultasi desain bordir, sistem DP 50%, dan jaminan tepat waktu.
        </p>
        <div class="hero-cta-group">
          <!-- Tombol oranye aksi sesuai spesifikasi figma -->
          <a href="katalog.php" class="btn btn-accent btn-lg">
            Lihat Katalog
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="5" y1="12" x2="19" y2="12"></line>
              <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
          </a>
          <a href="lacak.php" class="btn btn-outline-primary btn-lg" style="color: #ffffff; border-color: rgba(255,255,255,0.4);">
            Lacak Pesanan Saya
          </a>
        </div>
      </div>

      <div class="hero-visual">
        <div class="hero-3d-card">
          
          <!-- Tab Switcher: 3D Preview & Info Batch PO -->
          <div class="hero-3d-tabs">
            <button type="button" class="hero-3d-tab active" id="tab-btn-3d" onclick="switchHeroView('3d')">
              3D Live Model
            </button>
            <button type="button" class="hero-3d-tab" id="tab-btn-batch" onclick="switchHeroView('batch')">
              Info Batch &amp; Kuota
            </button>
          </div>

          <!-- VIEW 1: 3D MODEL WEBGL (BEROTASI PELAN) -->
          <div id="hero-view-3d" style="display: block;">
            <div class="hero-3d-header">
              <span class="hero-3d-badge">
                <span class="hero-3d-badge-pulse"></span>
                3D ROTATING MODEL
              </span>
              <span style="font-size: 11px; color: rgba(255,255,255,0.75);">Three.js WebGL</span>
            </div>

            <!-- 3D Canvas Viewport -->
            <div class="hero-3d-viewport" id="home-3d-viewport">
              <!-- Controls Overlay -->
              <div class="hero-3d-controls">
                <button type="button" class="hero-3d-btn-icon" id="btn-pause-3d" title="Pause / Putar Rotasi" onclick="toggleHome3DRotation()">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="4" width="4" height="16"></rect><rect x="14" y="4" width="4" height="16"></rect></svg>
                </button>
                <button type="button" class="hero-3d-btn-icon" title="Reset Sudut Pandang" onclick="resetHome3DRotation()">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path><path d="M3 3v5h5"></path></svg>
                </button>
              </div>
              <div class="hero-3d-hint">
                Drag mouse / sentuh layar untuk putar 360°
              </div>
            </div>

            <div class="hero-3d-meta">
              <h3 class="hero-3d-title" id="home-3d-title"><?= htmlspecialchars($showcase3D['title']) ?></h3>
              <p class="hero-3d-subtitle" id="home-3d-subtitle"><?= htmlspecialchars($showcase3D['subtitle']) ?></p>
              <a href="katalog.php" class="btn btn-accent btn-block btn-sm">
                Lihat Katalog Pre-Order
              </a>
            </div>
          </div>

          <!-- VIEW 2: INFO BATCH & COUNTDOWN -->
          <div id="hero-view-batch" style="display: none; padding-top: 4px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
              <span class="badge badge-accent">BATCH 2 CLOSING SOON</span>
              <span style="font-size: 11px; color: rgba(255,255,255,0.7);">Sisa Kuota: 18 pcs</span>
            </div>
            <h3 style="font-size: 16px; font-weight: 700; color: #ffffff; margin-bottom: 4px;">Varsity Angkatan 2026</h3>
            <p style="font-size: 12px; color: rgba(255,255,255,0.8); line-height: 1.4;">Bahan Cotton Fleece 330gsm &amp; Kulit Sintetis Soft Grade A</p>
            
            <div class="countdown-box">
              <div class="countdown-unit">
                <span class="countdown-num" id="cd-days">05</span>
                <span class="countdown-label">Hari</span>
              </div>
              <div class="countdown-unit">
                <span class="countdown-num" id="cd-hours">14</span>
                <span class="countdown-label">Jam</span>
              </div>
              <div class="countdown-unit">
                <span class="countdown-num" id="cd-mins">42</span>
                <span class="countdown-label">Menit</span>
              </div>
              <div class="countdown-unit">
                <span class="countdown-num" id="cd-secs">18</span>
                <span class="countdown-label">Detik</span>
              </div>
            </div>

            <a href="detail.php?id=1" class="btn btn-accent btn-block btn-sm" style="margin-top: 16px;">
              Ikut PO Sekarang
            </a>
          </div>

        </div>
      </div>
    </div>

    <!-- Fitur Keunggulan Fastender -->
    <div class="features-grid">
      <div class="feature-box">
        <div class="feature-icon">✨</div>
        <div class="feature-text">
          <h4>Bebas Custom Bordir</h4>
          <p>Gratis bordir komputer nama, NIM, dan logo angkatan di berbagai titik.</p>
        </div>
      </div>
      <div class="feature-box">
        <div class="feature-icon">🛡️</div>
        <div class="feature-text">
          <h4>Kualitas Standar Distro</h4>
          <p>Bahan tebal pilihan, tidak panas, dan jahitan rantai ganda kokoh.</p>
        </div>
      </div>
      <div class="feature-box">
        <div class="feature-icon">💳</div>
        <div class="feature-text">
          <h4>Fleksibel DP 50%</h4>
          <p>Cukup bayar uang muka untuk mulai produksi, pelunasan saat barang siap kirim.</p>
        </div>
      </div>
      <div class="feature-box">
        <div class="feature-icon">⏱️</div>
        <div class="feature-text">
          <h4>Jaminan Tepat Waktu</h4>
          <p>Pantau progress secara live mulai dari potong kain hingga pengiriman.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================
     GRID PRODUK TERPOPULER
     ========================================== -->
<section class="section-padding">
  <div class="container">
    <div class="section-header" style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px;">
      <div>
        <span class="section-tag">Paling Banyak Dipesan</span>
        <h2 class="section-title">Produk Terpopuler</h2>
        <p class="section-subtitle">Pilihan apparel dan atribut pre-order terfavorit yang paling sering dipesan oleh angkatan dan komunitas.</p>
      </div>
      <a href="katalog.php" class="btn btn-outline-primary" style="font-size: 13px;">
        Lihat Semua Produk
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="5" y1="12" x2="19" y2="12"></line>
          <polyline points="12 5 19 12 12 19"></polyline>
        </svg>
      </a>
    </div>

    <!-- Banner Notifikasi Produk Segera Hadir (Jika slot masih kosong) -->
    <div id="popular-empty-banner">
      <?php if (empty($popularProducts)): ?>
        <div class="empty-slot-banner">
          <div class="empty-slot-banner-text">
            <div style="font-size: 24px;">✨</div>
            <div>
              <h4>Batch Produk Pre-Order Segera Dibuka</h4>
              <p>Koleksi apparel edisi angkatan 2026 sedang disiapkan. Hubungi CS kami untuk pemesanan kustom angkatan.</p>
            </div>
          </div>
          <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <a href="https://wa.me/6281234567890?text=Halo%20FastTender,%20saya%20ingin%20konsultasi%20PO" target="_blank" class="btn btn-accent btn-sm">
              💬 Konsultasi Desain Angkatan
            </a>
            <a href="lacak.php" class="btn btn-outline-primary btn-sm">
              Lacak Pesanan
            </a>
          </div>
        </div>
      <?php endif; ?>
    </div>

    <!-- Grid 4 Product Card / Space Kosongan -->
    <div id="popular-products-grid" class="product-grid">
      <?php if (empty($popularProducts)): ?>
        <?php for ($i = 1; $i <= 4; $i++): ?>
          <div class="empty-slot-card">
            <span class="empty-slot-badge">Coming Soon</span>
            <div class="empty-slot-icon">
              <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="2" stroke-dasharray="4 4"></rect>
                <line x1="12" y1="8" x2="12" y2="16"></line>
                <line x1="8" y1="12" x2="16" y2="12"></line>
              </svg>
            </div>
            <h3 class="empty-slot-title">Katalog Produk PO</h3>
            <p class="empty-slot-desc">Produk baru edisi angkatan 2026 akan segera dirilis di slot ini.</p>
            <a href="lacak.php" class="btn btn-outline-accent btn-sm">
              Lacak Pesanan
            </a>
          </div>
        <?php endfor; ?>
      <?php else: ?>
        <?php foreach ($popularProducts as $prod): 
          $quotaPct = min(100, round(($prod['quota_current'] / max(1, $prod['quota_target'])) * 100));
          $batchLabel = explode(' ', $prod['batch'])[0];
        ?>
          <article class="product-card" data-id="<?= $prod['id'] ?>">
            <div class="product-thumb-wrapper">
              <span class="product-card-badge"><?= htmlspecialchars($prod['badge']) ?></span>
              <span class="product-batch-tag"><?= htmlspecialchars($batchLabel) ?></span>
              <?php if (!empty($prod['images'])): ?>
                <img src="<?= htmlspecialchars($prod['images'][0]) ?>" alt="<?= htmlspecialchars($prod['name']) ?>" class="product-thumb">
              <?php else: ?>
                <div style="width: 100%; height: 100%; padding: 20px;">
                  <?= getProductSvgPhp($prod['icon_type'] ?? 'varsity') ?>
                </div>
              <?php endif; ?>
            </div>
            <div class="product-content">
              <span class="product-category"><?= htmlspecialchars($prod['category_name'] ?? $prod['category']) ?></span>
              <h3 class="product-title" title="<?= htmlspecialchars($prod['name']) ?>"><?= htmlspecialchars($prod['name']) ?></h3>
              
              <div class="po-quota-box">
                <div class="po-quota-info">
                  <span>Kuota Terisi: <strong><?= $prod['quota_current'] ?>/<?= $prod['quota_target'] ?> pcs</strong></span>
                  <span><?= $quotaPct ?>%</span>
                </div>
                <div class="po-progress-bar">
                  <div class="po-progress-fill" style="width: <?= $quotaPct ?>%;"></div>
                </div>
              </div>

                <!-- Rating & Terjual Ala Marketplace -->
                <div class="product-rating-row">
                  <span class="rating-stars">★★★★★</span>
                  <span class="rating-val"><?= number_format($prod['rating'] ?? 4.9, 1) ?></span>
                  <span class="sold-count">• <?= $prod['sold_count'] ?? 100 ?>+ terjual</span>
                </div>

                <!-- Price Box Ala Marketplace -->
                <div class="marketplace-price-box">
                  <div class="price-main-wrap">
                    <span class="product-price"><?= formatRupiah($prod['price']) ?></span>
                    <span class="product-strike-price"><?= formatRupiah($prod['original_price'] ?? round($prod['price'] * 1.15)) ?></span>
                  </div>
                  <span class="product-dp-tag">DP Min 50%: <?= formatRupiah($prod['dp_price']) ?></span>
                </div>

                <!-- Marketplace Dual Action Buttons -->
                <div class="marketplace-card-actions">
                  <button type="button" class="btn-cart-quick" onclick="addToCart(<?= (int)$prod['id'] ?>, 1)">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="9" cy="21" r="1"></circle>
                      <circle cx="20" cy="21" r="1"></circle>
                      <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <span>+ Keranjang</span>
                  </button>
                  <a href="detail.php?id=<?= $prod['id'] ?>" class="btn-buy-now" style="text-decoration: none;">
                    Beli Sekarang
                  </a>
                </div>

                <a href="detail.php?id=<?= $prod['id'] ?>" class="link-detail-view">
                  Lihat Rincian &amp; Size Chart &rarr;
                </a>
              </div>
            </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- ==========================================
     ALUR PRE-ORDER (HOW IT WORKS)
     ========================================== -->
<section class="section-padding" style="background-color: var(--bg-muted); border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color);">
  <div class="container">
    <div class="section-header text-center">
      <span class="section-tag">Mudah &amp; Transparan</span>
      <h2 class="section-title">Alur Pre-Order di Fastender</h2>
      <p class="section-subtitle">Sistem pemesanan praktis tanpa ribet, dirancang khusus untuk kebutuhan pemesanan rombongan maupun individu.</p>
    </div>

    <div class="po-steps-grid">
      <div class="step-card">
        <div class="step-num">1</div>
        <h4>Pilih Produk &amp; Varian</h4>
        <p>Tentukan model, ukuran (S - 3XL), warna, dan masukkan detail custom nama atau logo angkatanmu.</p>
      </div>
      <div class="step-card">
        <div class="step-num">2</div>
        <h4>Checkout &amp; Bayar DP</h4>
        <p>Pilih skema pembayaran DP 50% atau lunas melalui transfer bank, QRIS, maupun dompet digital.</p>
      </div>
      <div class="step-card active">
        <div class="step-num">3</div>
        <h4>Produksi &amp; Pantau Status</h4>
        <p>Vendor mulai proses jahit dan bordir. Kamu dapat melacak nomor pesananmu secara real-time kapan saja.</p>
      </div>
      <div class="step-card">
        <div class="step-num">4</div>
        <h4>Pengiriman Sampai Lokasi</h4>
        <p>Setelah lolos quality check, barang langsung dikirim ke alamat rumah atau titik kumpul kampusmu.</p>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================
     CALL TO ACTION BANNER
     ========================================== -->
<section class="section-padding">
  <div class="container">
    <div style="background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%); border-radius: var(--radius-xl); padding: 48px; color: #ffffff; text-align: center; position: relative; overflow: hidden; box-shadow: var(--shadow-lg);">
      <h2 style="font-size: 30px; font-weight: 800; margin-bottom: 12px;">Ingin Buat Pre-Order untuk Satu Angkatan Penuh?</h2>
      <p style="font-size: 15px; color: rgba(255,255,255,0.8); max-width: 620px; margin: 0 auto 28px;">
        Dapatkan harga khusus grosir, sample kain gratis, dan formulir pendataan khusus panitia untuk pesanan di atas 30 pcs.
      </p>
      <div style="display: flex; justify-content: center; gap: 14px; flex-wrap: wrap;">
        <a href="https://wa.me/6281234567890?text=Halo%20FastTender,%20saya%20ingin%20konsultasi%20PO%20Angkatan" target="_blank" class="btn btn-accent btn-lg">
          Hubungi Konsultan PO (WhatsApp)
        </a>
        <a href="katalog.php" class="btn btn-outline-primary btn-lg" style="color: #ffffff; border-color: rgba(255,255,255,0.4);">
          Eksplor Katalog Lengkap
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Three.js Engine & FastTender 3D Viewer -->
<script src="js/three.min.js"></script>
<script src="js/fastender-3d.js"></script>
<script>
  let home3DViewer = null;

  document.addEventListener("DOMContentLoaded", function() {
    // Inisialisasi 3D Model Berotasi Pelan di Beranda
    home3DViewer = new FastTender3DViewer('home-3d-viewport', {
      imageUrl: '<?= htmlspecialchars($showcase3D['image_url']) ?>',
      depth: <?= (float)($showcase3D['depth'] ?? 8) ?>,
      rotationSpeed: <?= (float)($showcase3D['rotation_speed'] ?? 0.006) ?>,
      metalness: <?= (float)($showcase3D['metalness'] ?? 0.35) ?>,
      roughness: <?= (float)($showcase3D['roughness'] ?? 0.3) ?>
    });

    initCountdown();
  });

  function toggleHome3DRotation() {
    if (!home3DViewer) return;
    const isPaused = home3DViewer.togglePause();
    const btn = document.getElementById('btn-pause-3d');
    if (btn) {
      btn.innerHTML = isPaused 
        ? '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>'
        : '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="4" width="4" height="16"></rect><rect x="14" y="4" width="4" height="16"></rect></svg>';
    }
  }

  function resetHome3DRotation() {
    if (!home3DViewer) return;
    home3DViewer.resetRotation();
  }

  function switchHeroView(mode) {
    const view3D = document.getElementById('hero-view-3d');
    const viewBatch = document.getElementById('hero-view-batch');
    const tab3D = document.getElementById('tab-btn-3d');
    const tabBatch = document.getElementById('tab-btn-batch');

    if (mode === '3d') {
      view3D.style.display = 'block';
      viewBatch.style.display = 'none';
      tab3D.classList.add('active');
      tabBatch.classList.remove('active');
    } else {
      view3D.style.display = 'none';
      viewBatch.style.display = 'block';
      tab3D.classList.remove('active');
      tabBatch.classList.add('active');
    }
  }

  // Countdown Timer Demo
  function initCountdown() {
    let target = new Date().getTime() + (5 * 24 * 60 * 60 * 1000) + (14 * 60 * 60 * 1000);
    setInterval(() => {
      let now = new Date().getTime();
      let diff = target - now;
      if (diff <= 0) return;
      let days = Math.floor(diff / (1000 * 60 * 60 * 24));
      let hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      let mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
      let secs = Math.floor((diff % (1000 * 60)) / 1000);

      let elDays = document.getElementById("cd-days");
      let elHours = document.getElementById("cd-hours");
      let elMins = document.getElementById("cd-mins");
      let elSecs = document.getElementById("cd-secs");

      if (elDays) elDays.textContent = String(days).padStart(2, '0');
      if (elHours) elHours.textContent = String(hours).padStart(2, '0');
      if (elMins) elMins.textContent = String(mins).padStart(2, '0');
      if (elSecs) elSecs.textContent = String(secs).padStart(2, '0');
    }, 1000);
  }
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
