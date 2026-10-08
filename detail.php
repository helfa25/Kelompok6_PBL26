<?php
/**
 * Fastender Pre-Order Platform
 * Halaman Detail Produk PO (detail.php)
 * PHP Native
 */
require_once __DIR__ . '/includes/functions.php';

$prodId = isset($_GET['id']) ? (int)$_GET['id'] : 1;
$product = getProductById($prodId);

if (!$product) {
    // Jika belum ada produk tersimpan sama sekali, gunakan demo pertama
    $demos = getDefaultDemoProducts();
    $product = $demos[0];
}

$pageTitle = $product['name'] . ' - Fastender Pre-Order';
$activePage = 'katalog';

$quotaPct = min(100, round(($product['quota_current'] / max(1, $product['quota_target'])) * 100));
$hasRealImages = !empty($product['images']);

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
?>

<!-- ==========================================
     BREADCRUMB
     ========================================== -->
<div style="background-color: var(--primary-subtle); border-bottom: 1px solid var(--border-color); padding: 18px 0;">
  <div class="container">
    <div style="font-size: 13px; color: var(--text-muted);">
      <a href="index.php" style="color: var(--primary);">Beranda</a> &nbsp;/&nbsp; 
      <a href="katalog.php" style="color: var(--primary);">Katalog</a> &nbsp;/&nbsp; 
      <span style="color: var(--text-main); font-weight: 600;"><?= htmlspecialchars($product['name']) ?></span>
    </div>
  </div>
</div>

<!-- ==========================================
     DETAIL PRODUK 2 KOLOM (Gambar Besar & Rincian)
     ========================================== -->
<section class="section-padding">
  <div class="container">
    <div class="detail-layout">

      <!-- KOLOM KIRI: GAMBAR PRODUK BESAR & THUMBNAILS -->
      <div class="detail-gallery">
        <div class="detail-main-img" id="detail-image-container">
          <?php if ($hasRealImages): ?>
            <img id="detail-main-photo" src="<?= htmlspecialchars($product['images'][0]) ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: var(--radius-lg);">
          <?php else: ?>
            <div style="width: 100%; height: 100%; padding: 36px; display: flex; align-items: center; justify-content: center;">
              <?= getProductSvgPhp($product['icon_type'] ?? 'varsity') ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- Thumbnails Selector -->
        <div class="detail-thumbs" id="detail-thumbs-container">
          <?php if ($hasRealImages): ?>
            <?php foreach ($product['images'] as $idx => $imgSrc): ?>
              <button type="button" class="detail-thumb-btn <?= ($idx === 0) ? 'active' : '' ?>" onclick="switchDetailPhoto(<?= $idx ?>)" title="Foto <?= $idx + 1 ?>">
                <img src="<?= htmlspecialchars($imgSrc) ?>" alt="Thumbnail <?= $idx + 1 ?>" style="width: 100%; height: 100%; object-fit: cover;">
              </button>
            <?php endforeach; ?>
          <?php else: ?>
            <button type="button" class="detail-thumb-btn active" onclick="switchThumb(0)">
              <div style="padding: 6px; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600; color: var(--primary); background: var(--primary-subtle);">
                Tampak Depan
              </div>
            </button>
            <button type="button" class="detail-thumb-btn" onclick="switchThumb(1)">
              <div style="padding: 6px; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600; color: var(--text-muted); background: var(--bg-muted);">
                Tampak Belakang
              </div>
            </button>
            <button type="button" class="detail-thumb-btn" onclick="switchThumb(2)">
              <div style="padding: 6px; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600; color: var(--text-muted); background: var(--bg-muted);">
                Detail Bordir
              </div>
            </button>
            <button type="button" class="detail-thumb-btn" onclick="switchThumb(3)">
              <div style="padding: 6px; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600; color: var(--text-muted); background: var(--bg-muted);">
                Tekstur Bahan
              </div>
            </button>
          <?php endif; ?>
        </div>

        <!-- PO Guarantee Box -->
        <div style="background: #ffffff; border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 18px; margin-top: 8px;">
          <div style="font-size: 13px; font-weight: 700; color: var(--primary); margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
            <span>🛡️</span> Jaminan Layanan Pre-Order Fastender
          </div>
          <ul style="font-size: 12px; color: var(--text-muted); list-style: none; display: flex; flex-direction: column; gap: 6px; padding: 0;">
            <li>✓ Garansi tukar ukuran bila ada kesalahan jahit konveksi</li>
            <li>✓ Hasil bordir komputer tajam &amp; benang tidak mudah terurai</li>
            <li>✓ Dana 100% aman tersimpan hingga pesanan diterima</li>
          </ul>
        </div>
      </div>

      <!-- KOLOM KANAN: RINCIAN PRODUK & AKSI PEMESANAN -->
      <div class="detail-info">
        <div>
          <span class="badge badge-primary" style="margin-bottom: 8px;"><?= htmlspecialchars($product['category_name'] ?? $product['category']) ?></span>
          <span class="badge badge-accent"><?= htmlspecialchars($product['badge'] ?? 'BATCH AKTIF') ?></span>
        </div>

        <!-- Judul Besar -->
        <h1 class="detail-title"><?= htmlspecialchars($product['name']) ?></h1>

        <!-- Harga Besar (Warna Biru / Oranye) -->
        <div class="detail-price-box">
          <div>
            <div class="detail-price"><?= formatRupiah($product['price']) ?></div>
            <div class="detail-price-dp">Bisa Bayar DP 50%: <?= formatRupiah($product['dp_price']) ?></div>
          </div>
          <div style="text-align: right; font-size: 12px; color: var(--text-muted);">
            <div>Harga termasuk PPN</div>
            <div style="color: var(--success); font-weight: 600;">✓ Free Bordir Nama</div>
          </div>
        </div>

        <!-- Status Kuota PO Card -->
        <div class="po-batch-card">
          <div class="po-batch-header">
            <span><?= htmlspecialchars($product['batch']) ?></span>
            <span><?= $quotaPct ?>% Tercapai</span>
          </div>
          <div class="po-progress-bar" style="height: 8px; margin-bottom: 8px;">
            <div class="po-progress-fill" style="width: <?= $quotaPct ?>%;"></div>
          </div>
          <div style="font-size: 12px; color: #7c2d12; display: flex; justify-content: space-between;">
            <span>Terkumpul <strong><?= $product['quota_current'] ?></strong> dari target <strong><?= $product['quota_target'] ?> pcs</strong></span>
            <span><strong>Sisa <?= max(0, $product['quota_target'] - $product['quota_current']) ?> pcs</strong> untuk tutup batch</span>
          </div>
        </div>

        <!-- Deskripsi Singkat -->
        <p style="font-size: 14px; color: var(--text-muted); line-height: 1.6; margin-bottom: 24px;">
          <?= nl2br(htmlspecialchars($product['description'])) ?>
        </p>

        <!-- Pilihan Varian: Ukuran -->
        <div class="option-group">
          <div class="option-label">
            <span>Pilih Ukuran</span>
            <a href="#" onclick="alert('Panduan Ukuran Standar (cm):\nS: Lebar 48, Panjang 65\nM: Lebar 51, Panjang 68\nL: Lebar 54, Panjang 71\nXL: Lebar 57, Panjang 74\nXXL: Lebar 60, Panjang 77\n3XL: Lebar 63, Panjang 80'); return false;" style="color: var(--accent); font-size: 12px; font-weight: 600;">
              📏 Panduan Ukuran
            </a>
          </div>
          <div id="size-chips-container" class="size-selector">
            <?php foreach ($product['sizes'] as $idx => $s): ?>
              <button type="button" class="size-chip <?= ($idx === 0) ? 'active' : '' ?>" data-size="<?= htmlspecialchars(trim($s)) ?>"><?= htmlspecialchars(trim($s)) ?></button>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Pilihan Varian: Warna -->
        <div class="option-group">
          <div class="option-label">
            <span>Pilihan Kombinasi Warna</span>
          </div>
          <div id="color-chips-container" class="size-selector">
            <?php foreach ($product['colors'] as $idx => $c): ?>
              <button type="button" class="size-chip <?= ($idx === 0) ? 'active' : '' ?>" data-color="<?= htmlspecialchars(trim($c)) ?>"><?= htmlspecialchars(trim($c)) ?></button>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Custom Bordir / Sablon Teks -->
        <div class="option-group">
          <label class="option-label" for="custom-text-input">
            <span>Custom Nama / NIM di Dada (Opsional)</span>
            <span style="font-size: 12px; color: var(--text-light); font-weight: normal;">Maksimal 25 karakter</span>
          </label>
          <input type="text" id="custom-text-input" class="form-control" placeholder="Contoh: Fatah / 240601201 / TI 26" maxlength="25">
        </div>

        <!-- Input Kuantitas (Jumlah kuantitas dengan tombol + dan -) -->
        <div class="option-group" style="display: flex; align-items: center; gap: 20px;">
          <div>
            <span style="font-size: 14px; font-weight: 600; display: block; margin-bottom: 6px;">Jumlah Kuantitas</span>
            <div class="qty-control">
              <button type="button" class="qty-btn" id="btn-decrease-qty" aria-label="Kurang kuantitas">-</button>
              <input type="number" id="detail-qty" class="qty-input" value="1" min="1" max="99" readonly>
              <button type="button" class="qty-btn" id="btn-increase-qty" aria-label="Tambah kuantitas">+</button>
            </div>
          </div>

          <div style="flex-grow: 1; padding-top: 18px;">
            <span style="font-size: 12px; color: var(--text-muted); display: block;">Total Pembayaran Sementara:</span>
            <span id="detail-subtotal-display" style="font-size: 18px; font-weight: 800; color: var(--primary);">
              <?= formatRupiah($product['price']) ?>
            </span>
          </div>
        </div>

        <!-- Tombol Lebar "Tambah ke Keranjang" Warna Oranye Sesuai Spesifikasi -->
        <div class="detail-actions">
          <button type="button" id="add-to-cart-btn" class="btn btn-accent btn-lg btn-block">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="9" cy="21" r="1"></circle>
              <circle cx="20" cy="21" r="1"></circle>
              <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            Tambah ke Keranjang
          </button>

          <button type="button" id="buy-now-btn" class="btn btn-primary btn-lg btn-block">
            Pesan Sekarang
          </button>
        </div>

      </div>

    </div>

    <!-- Tab Rincian Spesifikasi & Kebijakan PO -->
    <div style="margin-top: 40px; background: #ffffff; border: 1px solid var(--border-color); border-radius: var(--radius-xl); padding: 32px;">
      <h3 style="font-size: 18px; font-weight: 700; color: var(--primary); margin-bottom: 16px;">Spesifikasi Lengkap &amp; Aturan PO</h3>
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
        <div>
          <h4 style="font-size: 14px; font-weight: 700; color: var(--text-main); margin-bottom: 10px;">Rincian Material:</h4>
          <ul style="font-size: 13px; color: var(--text-muted); padding-left: 20px; line-height: 1.8;">
            <?php if (!empty($product['specs'])): ?>
              <?php foreach ($product['specs'] as $spec): ?>
                <li><?= htmlspecialchars(trim($spec)) ?></li>
              <?php endforeach; ?>
            <?php else: ?>
              <li>Bahan Cotton Fleece 330gsm Standar Distro</li>
              <li>Bordir Komputer Kerapatan Tinggi</li>
            <?php endif; ?>
          </ul>
        </div>
        <div>
          <h4 style="font-size: 14px; font-weight: 700; color: var(--text-main); margin-bottom: 10px;">Ketentuan Pre-Order (PO):</h4>
          <ul style="font-size: 13px; color: var(--text-muted); padding-left: 20px; line-height: 1.8;">
            <li>Produksi massal dimulai setelah kuota batch ditutup.</li>
            <li>Waktu produksi berkisar 14 - 21 hari kerja konveksi.</li>
            <li>Pelunasan sisa 50% dapat dibayar saat pakaian siap kirim.</li>
          </ul>
        </div>
      </div>
    </div>

  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script>
  // Script Interaktif Galeri & Keranjang pada Halaman Detail
  const CURRENT_PRODUCT = {
    id: <?= (int)$product['id'] ?>,
    name: <?= json_encode($product['name']) ?>,
    categoryName: <?= json_encode($product['category_name'] ?? $product['category']) ?>,
    price: <?= (int)$product['price'] ?>,
    dpPrice: <?= (int)$product['dp_price'] ?>,
    iconType: <?= json_encode($product['icon_type'] ?? 'varsity') ?>,
    images: <?= json_encode($product['images'] ?? []) ?>,
    sizes: <?= json_encode($product['sizes'] ?? []) ?>,
    colors: <?= json_encode($product['colors'] ?? []) ?>
  };

  let selectedSize = CURRENT_PRODUCT.sizes.length > 0 ? CURRENT_PRODUCT.sizes[0] : "L";
  let selectedColor = CURRENT_PRODUCT.colors.length > 0 ? CURRENT_PRODUCT.colors[0] : "Standar";
  let quantity = 1;

  document.addEventListener("DOMContentLoaded", () => {
    // Event size chips
    document.querySelectorAll("#size-chips-container .size-chip").forEach(btn => {
      btn.addEventListener("click", () => {
        document.querySelectorAll("#size-chips-container .size-chip").forEach(b => b.classList.remove("active"));
        btn.classList.add("active");
        selectedSize = btn.getAttribute("data-size");
      });
    });

    // Event color chips
    document.querySelectorAll("#color-chips-container .size-chip").forEach(btn => {
      btn.addEventListener("click", () => {
        document.querySelectorAll("#color-chips-container .size-chip").forEach(b => b.classList.remove("active"));
        btn.classList.add("active");
        selectedColor = btn.getAttribute("data-color");
      });
    });

    // Stepper kuantitas
    const qtyInput = document.getElementById("detail-qty");
    const btnDecrease = document.getElementById("btn-decrease-qty");
    const btnIncrease = document.getElementById("btn-increase-qty");
    const subtotalDisplay = document.getElementById("detail-subtotal-display");

    function updateSubtotal() {
      qtyInput.value = quantity;
      subtotalDisplay.textContent = formatRupiah(CURRENT_PRODUCT.price * quantity);
    }

    if (btnDecrease) {
      btnDecrease.addEventListener("click", () => {
        if (quantity > 1) {
          quantity--;
          updateSubtotal();
        }
      });
    }

    if (btnIncrease) {
      btnIncrease.addEventListener("click", () => {
        if (quantity < 99) {
          quantity++;
          updateSubtotal();
        }
      });
    }

    // Action: Tambah ke Keranjang
    const addBtn = document.getElementById("add-to-cart-btn");
    if (addBtn) {
      addBtn.addEventListener("click", () => {
        const customText = document.getElementById("custom-text-input").value.trim();
        addToCart(CURRENT_PRODUCT.id, quantity, selectedSize, selectedColor, customText);
      });
    }

    // Action: Pesan Sekarang
    const buyNowBtn = document.getElementById("buy-now-btn");
    if (buyNowBtn) {
      buyNowBtn.addEventListener("click", () => {
        const customText = document.getElementById("custom-text-input").value.trim();
        addToCart(CURRENT_PRODUCT.id, quantity, selectedSize, selectedColor, customText);
        window.location.href = "checkout.php";
      });
    }
  });

  // Switch gallery thumbnail
  function switchDetailPhoto(idx) {
    const mainImg = document.getElementById("detail-main-photo");
    if (mainImg && CURRENT_PRODUCT.images[idx]) {
      mainImg.src = CURRENT_PRODUCT.images[idx];
    }
    document.querySelectorAll(".detail-thumb-btn").forEach((btn, i) => {
      btn.classList.toggle("active", i === idx);
    });
  }

  function switchThumb(index) {
    document.querySelectorAll(".detail-thumb-btn").forEach((btn, i) => {
      btn.classList.toggle("active", i === index);
    });
    showToast("Tampilan sudut pandang produk diperbarui", "info");
  }
</script>
