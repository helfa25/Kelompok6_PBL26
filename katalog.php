<?php
/**
 * Fastender Pre-Order Platform
 * Halaman Katalog Produk (katalog.php)
 * PHP Native
 */
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Katalog Produk Pre-Order - Fastender';
$activePage = 'katalog';

$allProducts = getProductsList();
$selectedCat = sanitize($_GET['cat'] ?? 'all');

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
?>

<!-- ==========================================
     PAGE HEADER / BREADCRUMB
     ========================================== -->
<div style="background-color: var(--primary-subtle); border-bottom: 1px solid var(--border-color); padding: 24px 0;">
  <div class="container">
    <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 6px;">
      <a href="index.php" style="color: var(--primary);">Beranda</a> &nbsp;/&nbsp; <span style="color: var(--text-main); font-weight: 600;">Katalog Produk PO</span>
    </div>
    <h1 style="font-size: 26px; font-weight: 800; color: var(--primary);">Katalog Pre-Order Angkatan</h1>
    <p style="font-size: 14px; color: var(--text-muted);">Pilih produk apparel dan atribut resmi yang sedang membuka kuota pemesanan.</p>
  </div>
</div>

<!-- ==========================================
     KATALOG PRODUK 2 KOLOM (Sidebar Filter & Grid)
     ========================================== -->
<section class="section-padding">
  <div class="container">
    <div class="katalog-layout">
      
      <!-- KOLOM KIRI: SIDEBAR FILTER -->
      <aside class="filter-sidebar">
        <div class="filter-header">
          <h3 class="filter-title">Filter Produk</h3>
          <button type="button" id="reset-filter-btn" style="font-size: 12px; color: var(--accent); font-weight: 600; cursor: pointer; background: none; border: none;">
            Reset
          </button>
        </div>

        <!-- Filter Pencarian -->
        <div class="filter-group">
          <label class="filter-label" for="filter-search">Cari Nama Produk</label>
          <input type="text" id="filter-search" class="form-control" placeholder="Ketik jaket, kaos, pdh...">
        </div>

        <!-- Filter Kategori -->
        <div class="filter-group">
          <label class="filter-label">Kategori</label>
          <label class="filter-option">
            <input type="radio" name="category-filter" value="all" <?= ($selectedCat === 'all' || empty($selectedCat)) ? 'checked' : '' ?>>
            <span>Semua Kategori</span>
          </label>
          <label class="filter-option">
            <input type="radio" name="category-filter" value="jaket" <?= ($selectedCat === 'jaket') ? 'checked' : '' ?>>
            <span>Jaket &amp; Varsity</span>
          </label>
          <label class="filter-option">
            <input type="radio" name="category-filter" value="pdh" <?= ($selectedCat === 'pdh') ? 'checked' : '' ?>>
            <span>Kemeja PDH</span>
          </label>
          <label class="filter-option">
            <input type="radio" name="category-filter" value="hoodie" <?= ($selectedCat === 'hoodie') ? 'checked' : '' ?>>
            <span>Hoodie &amp; Sweatshirt</span>
          </label>
          <label class="filter-option">
            <input type="radio" name="category-filter" value="kaos" <?= ($selectedCat === 'kaos') ? 'checked' : '' ?>>
            <span>Kaos Angkatan</span>
          </label>
          <label class="filter-option">
            <input type="radio" name="category-filter" value="merch" <?= ($selectedCat === 'merch') ? 'checked' : '' ?>>
            <span>Merchandise &amp; Aksesoris</span>
          </label>
        </div>

        <!-- Filter Rentang Harga -->
        <div class="filter-group">
          <label class="filter-label">Rentang Harga (Rp)</label>
          <div class="price-range-inputs">
            <input type="number" id="price-min" placeholder="Min" value="0">
            <span>-</span>
            <input type="number" id="price-max" placeholder="Max" value="300000">
          </div>
          <button type="button" id="apply-price-btn" class="btn btn-outline-accent btn-sm btn-block" style="margin-top: 10px;">
            Terapkan Harga
          </button>
        </div>

        <!-- Status PO -->
        <div class="filter-group" style="margin-bottom: 0;">
          <label class="filter-label">Status Pre-Order</label>
          <label class="filter-option">
            <input type="checkbox" id="filter-open" checked>
            <span>PO Sedang Dibuka</span>
          </label>
          <label class="filter-option">
            <input type="checkbox" id="filter-closing">
            <span>Segera Tutup (Kuota &gt; 80%)</span>
          </label>
        </div>
      </aside>

      <!-- KOLOM KANAN: TOP BAR & GRID PRODUK -->
      <main>
        <!-- Katalog Topbar (Count & Sort) -->
        <div class="katalog-topbar">
          <div style="font-size: 14px; color: var(--text-muted);">
            Menampilkan <strong id="product-count-text" style="color: var(--primary);"><?= count($allProducts) ?></strong> produk pre-order
          </div>
          <div style="display: flex; align-items: center; gap: 10px;">
            <label for="sort-select" style="font-size: 13px; font-weight: 600; color: var(--text-main);">Urutkan:</label>
            <select id="sort-select" class="sort-select">
              <option value="popular">Paling Populer</option>
              <option value="price-low">Harga: Rendah ke Tinggi</option>
              <option value="price-high">Harga: Tinggi ke Rendah</option>
              <option value="quota">Kuota Terbanyak</option>
            </select>
          </div>
        </div>

        <!-- Banner Notifikasi Produk Segera Hadir (Jika slot masih kosong) -->
        <div id="catalog-empty-banner">
          <?php if (empty($allProducts)): ?>
            <div class="empty-slot-banner">
              <div class="empty-slot-banner-text">
                <div style="font-size: 24px;">📦</div>
                <div>
                  <h4>Koleksi Produk Pre-Order Segera Dibuka</h4>
                  <p>Katalog apparel resmi angkatan 2026 sedang dalam proses persiapan rilis.</p>
                </div>
              </div>
              <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                <a href="https://wa.me/6281234567890?text=Halo%20FastTender,%20saya%20ingin%20konsultasi%20PO" target="_blank" class="btn btn-accent btn-sm">
                  💬 Konsultasi via WhatsApp
                </a>
                <a href="lacak.php" class="btn btn-outline-primary btn-sm">
                  🔍 Lacak Pesanan
                </a>
              </div>
            </div>
          <?php endif; ?>
        </div>

        <!-- Grid Produk / Space Kosongan -->
        <div id="catalog-products-grid" class="product-grid">
          <?php if (empty($allProducts)): ?>
            <?php for ($i = 1; $i <= 6; $i++): ?>
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
            <?php foreach ($allProducts as $prod): 
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

                  <div class="product-footer">
                    <div class="product-price-row">
                      <span class="product-price"><?= formatRupiah($prod['price']) ?></span>
                      <span class="product-dp-label">DP: <?= formatRupiah($prod['dp_price']) ?></span>
                    </div>
                    <a href="detail.php?id=<?= $prod['id'] ?>" class="btn btn-outline-accent btn-block">
                      Detail Produk
                    </a>
                  </div>
                </div>
              </article>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <!-- Empty State (Jika filter tidak menghasilkan produk) -->
        <div id="empty-state" style="display: none; text-align: center; padding: 60px 20px; background: #ffffff; border-radius: var(--radius-lg); border: 1px dashed var(--border-color); margin-top: 20px;">
          <div style="font-size: 40px; margin-bottom: 12px;">🔍</div>
          <h3 style="font-size: 18px; color: var(--primary); font-weight: 700;">Tidak Ada Produk yang Cocok</h3>
          <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 20px;">Coba ubah kata kunci pencarian atau reset filter harga dan kategori.</p>
          <button type="button" id="reset-empty-btn" class="btn btn-accent btn-sm">Reset Filter</button>
        </div>

      </main>

    </div>
  </div>
</section>

<!-- Scripts -->
<?php include __DIR__ . '/includes/footer.php'; ?>
<script>
  // Logika Filter Dinamis Sisi Pengguna
  document.addEventListener("DOMContentLoaded", () => {
    const grid = document.getElementById("catalog-products-grid");
    const countText = document.getElementById("product-count-text");
    const emptyState = document.getElementById("empty-state");
    const searchInput = document.getElementById("filter-search");
    const categoryRadios = document.querySelectorAll('input[name="category-filter"]');
    const minPriceInput = document.getElementById("price-min");
    const maxPriceInput = document.getElementById("price-max");
    const applyPriceBtn = document.getElementById("apply-price-btn");
    const sortSelect = document.getElementById("sort-select");
    const resetBtn = document.getElementById("reset-filter-btn");
    const resetEmptyBtn = document.getElementById("reset-empty-btn");
    const closingCheckbox = document.getElementById("filter-closing");

    function runClientFilter() {
      const cards = grid.querySelectorAll(".product-card");
      if (cards.length === 0) return;

      const q = searchInput ? searchInput.value.toLowerCase().trim() : "";
      const selectedRadio = document.querySelector('input[name="category-filter"]:checked');
      const selectedCat = selectedRadio ? selectedRadio.value : "all";
      const minP = Number(minPriceInput.value) || 0;
      const maxP = Number(maxPriceInput.value) || Infinity;
      const onlyClosing = closingCheckbox && closingCheckbox.checked;

      let visibleCount = 0;
      cards.forEach(card => {
        const title = card.querySelector(".product-title")?.textContent.toLowerCase() || "";
        const cat = card.querySelector(".product-category")?.textContent.toLowerCase() || "";
        
        let match = true;
        if (q && !title.includes(q) && !cat.includes(q)) match = false;
        
        if (match) {
          card.style.display = "block";
          visibleCount++;
        } else {
          card.style.display = "none";
        }
      });

      if (countText) countText.textContent = visibleCount;
      if (emptyState) emptyState.style.display = (visibleCount === 0) ? "block" : "none";
    }

    if (searchInput) searchInput.addEventListener("input", runClientFilter);
    categoryRadios.forEach(r => r.addEventListener("change", runClientFilter));
    if (applyPriceBtn) applyPriceBtn.addEventListener("click", runClientFilter);
    if (closingCheckbox) closingCheckbox.addEventListener("change", runClientFilter);

    function resetFilters() {
      if (searchInput) searchInput.value = "";
      const defaultRadio = document.querySelector('input[name="category-filter"][value="all"]');
      if (defaultRadio) defaultRadio.checked = true;
      if (minPriceInput) minPriceInput.value = "0";
      if (maxPriceInput) maxPriceInput.value = "300000";
      if (closingCheckbox) closingCheckbox.checked = false;
      runClientFilter();
    }

    if (resetBtn) resetBtn.addEventListener("click", resetFilters);
    if (resetEmptyBtn) resetEmptyBtn.addEventListener("click", resetFilters);
  });
</script>
