<?php
/**
 * FastTender Pre-Order Platform
 * Halaman Katalog & Marketplace Pre-Order (katalog.php)
 * PHP Native
 */
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Katalog & Marketplace Pre-Order - FastTender';
$activePage = 'katalog';

$allProducts = getProductsList();
$selectedCat = sanitize($_GET['cat'] ?? 'all');

include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/navbar.php';
?>

<!-- ==========================================
     PAGE BREADCRUMB
     ========================================== -->
<div style="background-color: var(--primary-subtle); border-bottom: 1px solid var(--border-color); padding: 18px 0;">
  <div class="container">
    <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 4px;">
      <a href="index.php" style="color: var(--primary); font-weight: 500;">Beranda</a> &nbsp;/&nbsp; 
      <span style="color: var(--text-main); font-weight: 600;">Katalog &amp; Marketplace PO</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
      <div>
        <h1 style="font-size: 24px; font-weight: 800; color: var(--primary); margin: 0;">Marketplace Apparel Angkatan 2026</h1>
        <p style="font-size: 13px; color: var(--text-muted); margin: 2px 0 0;">Pilih produk apparel resmi, tentukan varian &amp; ukuran, lalu checkout dengan sistem DP 50% atau Lunas.</p>
      </div>
      <div style="display: flex; gap: 10px;">
        <a href="lacak.php" class="btn btn-outline-primary btn-sm">
          Lacak Status PO
        </a>
        <a href="keranjang.php" class="btn btn-accent btn-sm">
          Keranjang
        </a>
      </div>
    </div>
  </div>
</div>

<!-- ==========================================
     MAIN MARKETPLACE LAYOUT
     ========================================== -->
<section class="section-padding" style="padding-top: 30px;">
  <div class="container">
    
    <!-- BANNER PROMO MARKETPLACE -->
    <div class="marketplace-hero-banner">
      <div class="marketplace-banner-content">
        <span class="banner-pill">OFFICIAL PRE-ORDER STORE</span>
        <h2>Koleksi Apparel &amp; Atribut Resmi Angkatan 2026</h2>
        <p>Nikmati kemudahan checkout: pilih varian, bayar DP 50%, bordir komputer presisi, dan pantau produksi secara real-time.</p>
      </div>
      <div class="marketplace-banner-badges">
        <div class="banner-feature-badge">✓ Garansi Kualitas Mutu</div>
        <div class="banner-feature-badge">✓ Skema DP Mulai 50%</div>
        <div class="banner-feature-badge">✓ Ekspedisi Seluruh Indonesia</div>
        <div class="banner-feature-badge">✓ Free Bordir Nama Panitia</div>
      </div>
    </div>

    <!-- QUICK CATEGORY PILLS (Sticky Horizontal Navigation with Scroll Controls) -->
    <div class="katalog-sticky-nav-wrapper">
      <button type="button" class="nav-scroll-arrow left" id="pill-scroll-left" onclick="scrollCategoryPills(-200)" aria-label="Geser Kategori Kiri" title="Geser Kiri">
        &#8249;
      </button>
      <div class="category-filter-pills" id="category-pills">
        <button type="button" class="cat-pill <?= ($selectedCat === 'all' || empty($selectedCat)) ? 'active' : '' ?>" data-cat="all">
          Semua Koleksi (<?= count($allProducts) ?>)
        </button>
        <button type="button" class="cat-pill <?= ($selectedCat === 'jaket') ? 'active' : '' ?>" data-cat="jaket">
          Jaket &amp; Varsity
        </button>
        <button type="button" class="cat-pill <?= ($selectedCat === 'pdh') ? 'active' : '' ?>" data-cat="pdh">
          Kemeja PDH Drill
        </button>
        <button type="button" class="cat-pill <?= ($selectedCat === 'hoodie') ? 'active' : '' ?>" data-cat="hoodie">
          Hoodie Fleece
        </button>
        <button type="button" class="cat-pill <?= ($selectedCat === 'kaos') ? 'active' : '' ?>" data-cat="kaos">
          Kaos Angkatan
        </button>
      </div>
      <button type="button" class="nav-scroll-arrow right" id="pill-scroll-right" onclick="scrollCategoryPills(200)" aria-label="Geser Kategori Kanan" title="Geser Kanan">
        &#8250;
      </button>
      <button type="button" class="sticky-filter-toggle-btn" onclick="toggleFilterSidebar()" title="Buka Filter &amp; Rentang Harga">
        Filter
      </button>
    </div>

    <!-- TOMBOL FILTER MOBILE (Hanya tampil di tablet & smartphone) -->
    <button type="button" class="btn btn-outline-primary btn-mobile-filter-toggle" onclick="toggleFilterSidebar()" style="display: none;">
      Filter &amp; Harga
    </button>

    <!-- 2 KOLOM LAYOUT: SIDEBAR FILTER & PRODUCT GRID -->
    <div class="katalog-layout">
      
      <!-- KOLOM KIRI: SIDEBAR FILTER -->
      <aside class="filter-sidebar" id="filter-sidebar">
        <div class="filter-header">
          <h3 class="filter-title">Filter Pencarian</h3>
          <div style="display: flex; align-items: center; gap: 8px;">
            <button type="button" id="reset-filter-btn" style="font-size: 12px; color: var(--accent); font-weight: 600; cursor: pointer; background: none; border: none;">
              Reset Semua
            </button>
            <button type="button" class="btn-close-filter-mobile" onclick="toggleFilterSidebar()" aria-label="Tutup filter" title="Tutup filter">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6L6 18M6 6l12 12"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- Filter Pencarian Kata Kunci -->
        <div class="filter-group">
          <label class="filter-label" for="filter-search">Cari Nama / Model</label>
          <input type="text" id="filter-search" class="form-control" placeholder="Contoh: varsity, pdh, hoodie...">
        </div>

        <!-- Filter Kategori Radio -->
        <div class="filter-group">
          <label class="filter-label">Kategori Pilihan</label>
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
            <span>Kemeja PDH Drill</span>
          </label>
          <label class="filter-option">
            <input type="radio" name="category-filter" value="hoodie" <?= ($selectedCat === 'hoodie') ? 'checked' : '' ?>>
            <span>Hoodie Heavyweight</span>
          </label>
          <label class="filter-option">
            <input type="radio" name="category-filter" value="kaos" <?= ($selectedCat === 'kaos') ? 'checked' : '' ?>>
            <span>Kaos Cotton Combed</span>
          </label>
        </div>

        <!-- Filter Rentang Harga -->
        <div class="filter-group">
          <label class="filter-label">Rentang Harga (Rp)</label>
          <div class="price-range-inputs">
            <input type="number" id="price-min" placeholder="Min" value="0">
            <span>-</span>
            <input type="number" id="price-max" placeholder="Max" value="350000">
          </div>
          <button type="button" id="apply-price-btn" class="btn btn-outline-accent btn-sm btn-block" style="margin-top: 10px;">
            Terapkan Filter Harga
          </button>
        </div>

        <!-- Info Garansi Marketplace -->
        <div style="background: var(--bg-body); border-radius: var(--radius-md); padding: 14px; font-size: 12px; color: var(--text-muted); line-height: 1.6;">
          <strong style="color: var(--primary); display: block; margin-bottom: 4px;">🛍️ Sistem Pembelian FastTender:</strong>
          Setiap produk dapat langsung dimasukkan ke keranjang atau langsung di-checkout dengan opsi pembayaran DP 50% atau Pelunasan 100%.
        </div>
      </aside>

      <!-- KOLOM KANAN: TOP BAR & GRID PRODUK MARKETPLACE -->
      <main>
        
        <!-- Katalog Topbar -->
        <div class="katalog-topbar">
          <div style="font-size: 14px; color: var(--text-muted);">
            Menampilkan <strong id="product-count-text" style="color: var(--primary);"><?= count($allProducts) ?></strong> produk pre-order aktif
          </div>
          <div style="display: flex; align-items: center; gap: 10px;">
            <label for="sort-select" style="font-size: 13px; font-weight: 600; color: var(--text-main);">Urutkan:</label>
            <select id="sort-select" class="sort-select">
              <option value="popular">Paling Populer &amp; Terlaris</option>
              <option value="price-low">Harga: Rendah ke Tinggi</option>
              <option value="price-high">Harga: Tinggi ke Rendah</option>
              <option value="quota">Sisa Kuota Terbanyak</option>
            </select>
          </div>
        </div>

        <!-- Grid Produk Marketplace -->
        <div id="catalog-products-grid" class="product-grid">
          <?php foreach ($allProducts as $prod): 
            $quotaTarget = max(1, (int)($prod['quota_target'] ?? 100));
            $quotaCurrent = (int)($prod['quota_current'] ?? 0);
            $quotaPct = min(100, round(($quotaCurrent / $quotaTarget) * 100));
            $batchLabel = explode(' ', $prod['batch'] ?? 'Batch 1')[0];
            $originalPrice = !empty($prod['original_price']) ? (int)$prod['original_price'] : round($prod['price'] * 1.15);
            $rating = $prod['rating'] ?? 4.9;
            $soldCount = $prod['sold_count'] ?? (50 + $prod['id'] * 18);
          ?>
            <article class="product-card" data-id="<?= $prod['id'] ?>" data-price="<?= $prod['price'] ?>" data-quota="<?= $quotaPct ?>">
              <div class="product-thumb-wrapper">
                <span class="product-card-badge"><?= htmlspecialchars($prod['badge'] ?? 'Pre-Order') ?></span>
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
                <!-- Rating & Terjual Ala Marketplace -->
                <div class="product-rating-row">
                  <span class="rating-stars">★★★★★</span>
                  <span class="rating-val"><?= number_format($rating, 1) ?></span>
                  <span class="sold-count">• <?= $soldCount ?>+ terjual</span>
                </div>

                <span class="product-category"><?= htmlspecialchars($prod['category_name'] ?? $prod['category']) ?></span>
                <h3 class="product-title" title="<?= htmlspecialchars($prod['name']) ?>">
                  <a href="detail.php?id=<?= $prod['id'] ?>" style="color: inherit; text-decoration: none;">
                    <?= htmlspecialchars($prod['name']) ?>
                  </a>
                </h3>
                
                <!-- PO Progress Bar -->
                <div class="po-quota-box">
                  <div class="po-quota-info">
                    <span>Sisa Kuota: <strong><?= max(0, $quotaTarget - $quotaCurrent) ?> pcs</strong></span>
                    <span><?= $quotaPct ?>% Terpenuhi</span>
                  </div>
                  <div class="po-progress-bar">
                    <div class="po-progress-fill" style="width: <?= $quotaPct ?>%;"></div>
                  </div>
                </div>

                <!-- Price Box Ala Marketplace (Harga Diskon + Coret + DP) -->
                <div class="marketplace-price-box">
                  <div class="price-main-wrap">
                    <span class="product-price"><?= formatRupiah($prod['price']) ?></span>
                    <span class="product-strike-price"><?= formatRupiah($originalPrice) ?></span>
                  </div>
                  <span class="product-dp-tag">DP Min 50%: <?= formatRupiah($prod['dp_price']) ?></span>
                </div>

                <!-- Dual Action Buttons: + Keranjang & Beli Langsung (Checkout) -->
                <div class="marketplace-card-actions">
                  <button type="button" class="btn-cart-quick" onclick="handleQuickCart(<?= (int)$prod['id'] ?>)">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="9" cy="21" r="1"></circle>
                      <circle cx="20" cy="21" r="1"></circle>
                      <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <span>+ Keranjang</span>
                  </button>

                  <button type="button" class="btn-buy-now" onclick="openQuickBuyModal(<?= (int)$prod['id'] ?>)">
                    Beli Sekarang
                  </button>
                </div>

                <a href="detail.php?id=<?= $prod['id'] ?>" class="link-detail-view">
                  Lihat Rincian Bahan &amp; Size Chart &rarr;
                </a>
              </div>
            </article>
          <?php endforeach; ?>
        </div>

        <!-- Empty State Jika Filter Kosong -->
        <div id="empty-state" style="display: none; text-align: center; padding: 60px 20px; background: #ffffff; border-radius: var(--radius-lg); border: 1px dashed var(--border-color); margin-top: 20px;">
          <div style="font-size: 44px; margin-bottom: 12px;">🔍</div>
          <h3 style="font-size: 18px; color: var(--primary); font-weight: 700;">Tidak Ada Produk yang Cocok</h3>
          <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 20px;">Coba ubah kata kunci pencarian atau reset filter kategori &amp; harga.</p>
          <button type="button" id="reset-empty-btn" class="btn btn-accent btn-sm">Reset Filter Pencarian</button>
        </div>

      </main>

    </div>
  </div>
</section>

<!-- ==========================================
     QUICK CHECKOUT MODAL (POP-UP SISTEM CO MARKETPLACE)
     ========================================== -->
<div id="quick-co-modal" class="quick-co-modal-overlay">
  <div class="quick-co-modal-content">
    <div class="quick-co-header">
      <h3>
        Beli Langsung (Quick Checkout)
      </h3>
      <button type="button" class="quick-co-close-btn" onclick="closeQuickBuyModal()">&times;</button>
    </div>

    <div class="quick-co-body">
      <!-- Info Ringkas Produk -->
      <div class="quick-co-prod-info">
        <div class="quick-co-img-wrap" id="modal-img-wrap">
          <!-- Thumbnail diinjeksi via JS -->
        </div>
        <div>
          <div class="quick-co-title" id="modal-prod-title">Nama Produk</div>
          <div class="quick-co-price" id="modal-prod-price">Rp 0</div>
          <div class="quick-co-dp" id="modal-prod-dp">DP 50%: Rp 0</div>
        </div>
      </div>

      <!-- Pilih Warna -->
      <div class="quick-co-option-group">
        <label class="quick-co-option-label">Pilih Varian Warna:</label>
        <div class="quick-chips-wrap" id="modal-colors-wrap">
          <!-- Color chips via JS -->
        </div>
      </div>

      <!-- Pilih Ukuran -->
      <div class="quick-co-option-group">
        <label class="quick-co-option-label">Pilih Ukuran (Size):</label>
        <div class="quick-chips-wrap" id="modal-sizes-wrap">
          <!-- Size chips via JS -->
        </div>
      </div>

      <!-- Jumlah Pesanan (Quantity) -->
      <div class="quick-co-option-group">
        <label class="quick-co-option-label">Jumlah Pesanan (Qty):</label>
        <div class="quick-co-qty-wrap">
          <button type="button" class="qty-step-btn" onclick="changeModalQty(-1)">&minus;</button>
          <input type="number" id="modal-qty-input" class="qty-step-input" value="1" min="1" max="99" onchange="validateModalQty()">
          <button type="button" class="qty-step-btn" onclick="changeModalQty(1)">&plus;</button>
          <span style="font-size: 12px; color: var(--text-muted); margin-left: 8px;">pcs</span>
        </div>
      </div>

      <!-- Total Harga Realtime -->
      <div class="quick-co-total-box">
        <div>
          <div style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Subtotal Pesanan:</div>
          <div style="font-size: 20px; font-weight: 800; color: var(--primary);" id="modal-subtotal-text">Rp 0</div>
        </div>
        <div style="text-align: right;">
          <div style="font-size: 11px; color: var(--accent); font-weight: 700;" id="modal-dp-text">DP 50%: Rp 0</div>
          <div style="font-size: 11px; color: var(--text-muted);">Estimasi 14-21 Hari Kerja</div>
        </div>
      </div>
    </div>

    <!-- Footer Tombol Aksi Modal -->
    <div class="quick-co-footer">
      <button type="button" class="btn btn-outline-primary" onclick="confirmModalAddToCart()">
        + Keranjang
      </button>
      <button type="button" class="btn btn-accent" onclick="confirmModalDirectCheckout()">
        Lanjut ke Checkout &rarr;
      </button>
    </div>
  </div>
</div>

<!-- ==========================================
     STICKY FLOATING CART BAR (ALA SHOPEE/TOKOPEDIA)
     ========================================== -->
<div id="sticky-cart-bar" class="sticky-cart-bar" onclick="window.location.href='checkout.php'">
  <div style="display: flex; align-items: center; gap: 8px;">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <circle cx="9" cy="21" r="1"></circle>
      <circle cx="20" cy="21" r="1"></circle>
      <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
    </svg>
    <span class="sticky-cart-count" id="sticky-cart-count">0</span>
    <span style="font-size: 13px; font-weight: 600;" id="sticky-cart-total">Total: Rp 0</span>
  </div>
  <div style="font-size: 12px; font-weight: 700; background: var(--accent); color: #fff; padding: 4px 12px; border-radius: var(--radius-pill);">
    Checkout Sekarang &rarr;
  </div>
</div>

<!-- FLOATING BACK-TO-TOP BUTTON -->
<button type="button" id="btn-back-to-top" class="btn-back-to-top" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" aria-label="Kembali ke atas" title="Kembali ke atas">
  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M18 15l-6-6-6 6"/>
  </svg>
</button>

<?php include __DIR__ . '/includes/footer.php'; ?>

<!-- ==========================================
     CLIENT SCRIPTS: FILTER & MODAL CHECKOUT SYSTEM
     ========================================== -->
<script>
  // Data produk dari backend PHP
  const PRODUCTS_DATA_MAP = <?= json_encode(array_combine(array_column($allProducts, 'id'), $allProducts)) ?>;

  let currentModalProduct = null;
  let modalSelectedColor = "";
  let modalSelectedSize = "";
  let modalQty = 1;

  document.addEventListener("DOMContentLoaded", () => {
    initFilters();
    refreshStickyCartBar();
  });

  // 1. FILTER DAN SORTING
  function initFilters() {
    const grid = document.getElementById("catalog-products-grid");
    const countText = document.getElementById("product-count-text");
    const emptyState = document.getElementById("empty-state");
    const searchInput = document.getElementById("filter-search");
    const categoryRadios = document.querySelectorAll('input[name="category-filter"]');
    const pills = document.querySelectorAll("#category-pills .cat-pill");
    const minPriceInput = document.getElementById("price-min");
    const maxPriceInput = document.getElementById("price-max");
    const applyPriceBtn = document.getElementById("apply-price-btn");
    const sortSelect = document.getElementById("sort-select");
    const resetBtn = document.getElementById("reset-filter-btn");
    const resetEmptyBtn = document.getElementById("reset-empty-btn");

    function applyFilters() {
      const cards = Array.from(grid.querySelectorAll(".product-card"));
      if (cards.length === 0) return;

      const q = searchInput ? searchInput.value.toLowerCase().trim() : "";
      const selectedRadio = document.querySelector('input[name="category-filter"]:checked');
      const selectedCat = selectedRadio ? selectedRadio.value : "all";
      const minP = Number(minPriceInput.value) || 0;
      const maxP = Number(maxPriceInput.value) || Infinity;

      let visibleCount = 0;

      cards.forEach(card => {
        const prodId = Number(card.getAttribute("data-id"));
        const prod = PRODUCTS_DATA_MAP[prodId];
        if (!prod) return;

        const title = prod.name.toLowerCase();
        const cat = prod.category.toLowerCase();
        const price = Number(prod.price);

        let match = true;
        if (q && !title.includes(q) && !cat.includes(q)) match = false;
        if (selectedCat !== "all" && cat !== selectedCat) match = false;
        if (price < minP || price > maxP) match = false;

        if (match) {
          card.style.display = "flex";
          visibleCount++;
        } else {
          card.style.display = "none";
        }
      });

      if (countText) countText.textContent = visibleCount;
      if (emptyState) emptyState.style.display = (visibleCount === 0) ? "block" : "none";
    }

    // Sorting
    if (sortSelect) {
      sortSelect.addEventListener("change", () => {
        const val = sortSelect.value;
        const cards = Array.from(grid.querySelectorAll(".product-card"));
        
        cards.sort((a, b) => {
          const idA = Number(a.getAttribute("data-id"));
          const idB = Number(b.getAttribute("data-id"));
          const pA = PRODUCTS_DATA_MAP[idA];
          const pB = PRODUCTS_DATA_MAP[idB];
          if (!pA || !pB) return 0;

          if (val === "price-low") return pA.price - pB.price;
          if (val === "price-high") return pB.price - pA.price;
          if (val === "quota") return (pB.quota_target - pB.quota_current) - (pA.quota_target - pA.quota_current);
          return (pB.sold_count || 0) - (pA.sold_count || 0); // Popular default
        });

        cards.forEach(card => grid.appendChild(card));
      });
    }

    if (searchInput) searchInput.addEventListener("input", applyFilters);
    if (applyPriceBtn) applyPriceBtn.addEventListener("click", applyFilters);

    categoryRadios.forEach(radio => {
      radio.addEventListener("change", () => {
        pills.forEach(p => p.classList.toggle("active", p.getAttribute("data-cat") === radio.value));
        applyFilters();
      });
    });

    pills.forEach(pill => {
      pill.addEventListener("click", () => {
        const cat = pill.getAttribute("data-cat");
        pills.forEach(p => p.classList.remove("active"));
        pill.classList.add("active");

        const targetRadio = document.querySelector(`input[name="category-filter"][value="${cat}"]`);
        if (targetRadio) targetRadio.checked = true;

        applyFilters();
      });
    });

    function resetAll() {
      if (searchInput) searchInput.value = "";
      const defaultRadio = document.querySelector('input[name="category-filter"][value="all"]');
      if (defaultRadio) defaultRadio.checked = true;
      pills.forEach(p => p.classList.toggle("active", p.getAttribute("data-cat") === "all"));
      if (minPriceInput) minPriceInput.value = "0";
      if (maxPriceInput) maxPriceInput.value = "350000";
      applyFilters();
    }

    if (resetBtn) resetBtn.addEventListener("click", resetAll);
    if (resetEmptyBtn) resetEmptyBtn.addEventListener("click", resetAll);
  }

  // 2. QUICK ADD TO CART
  function handleQuickCart(productId) {
    const prod = PRODUCTS_DATA_MAP[productId];
    if (!prod) return;

    const defaultSize = (prod.sizes && prod.sizes.length > 0) ? prod.sizes[0] : "L";
    const defaultColor = (prod.colors && prod.colors.length > 0) ? prod.colors[0] : "Standar";

    addToCart(prod.id, 1, defaultSize, defaultColor);
    refreshStickyCartBar();
  }

  // 3. QUICK BUY / CHECKOUT MODAL
  function openQuickBuyModal(productId) {
    const prod = PRODUCTS_DATA_MAP[productId];
    if (!prod) return;

    currentModalProduct = prod;
    modalQty = 1;

    document.getElementById("modal-prod-title").textContent = prod.name;
    document.getElementById("modal-prod-price").textContent = formatRupiah(prod.price);
    document.getElementById("modal-prod-dp").textContent = "DP Min 50%: " + formatRupiah(prod.dp_price || Math.round(prod.price * 0.5));
    document.getElementById("modal-qty-input").value = 1;

    // Foto / SVG
    const imgWrap = document.getElementById("modal-img-wrap");
    if (prod.images && prod.images.length > 0) {
      imgWrap.innerHTML = `<img src="${prod.images[0]}" alt="${prod.name}">`;
    } else {
      imgWrap.innerHTML = getProductSvg(prod.icon_type || 'varsity');
    }

    // Warna
    const colors = prod.colors && prod.colors.length > 0 ? prod.colors : ["Standar"];
    modalSelectedColor = colors[0];
    const colorsWrap = document.getElementById("modal-colors-wrap");
    colorsWrap.innerHTML = colors.map((col, idx) => `
      <button type="button" class="quick-chip ${idx === 0 ? 'active' : ''}" onclick="selectModalColor('${col}', this)">
        ${col}
      </button>
    `).join("");

    // Ukuran
    const sizes = prod.sizes && prod.sizes.length > 0 ? prod.sizes : ["S", "M", "L", "XL", "XXL"];
    modalSelectedSize = sizes[0];
    const sizesWrap = document.getElementById("modal-sizes-wrap");
    sizesWrap.innerHTML = sizes.map((sz, idx) => `
      <button type="button" class="quick-chip ${idx === 0 ? 'active' : ''}" onclick="selectModalSize('${sz}', this)">
        ${sz}
      </button>
    `).join("");

    updateModalCalculation();

    const modal = document.getElementById("quick-co-modal");
    modal.classList.add("active");
  }

  function closeQuickBuyModal() {
    const modal = document.getElementById("quick-co-modal");
    modal.classList.remove("active");
  }

  function selectModalColor(color, el) {
    modalSelectedColor = color;
    document.querySelectorAll("#modal-colors-wrap .quick-chip").forEach(c => c.classList.remove("active"));
    el.classList.add("active");
  }

  function selectModalSize(size, el) {
    modalSelectedSize = size;
    document.querySelectorAll("#modal-sizes-wrap .quick-chip").forEach(c => c.classList.remove("active"));
    el.classList.add("active");
  }

  function changeModalQty(delta) {
    modalQty = Math.max(1, Math.min(99, modalQty + delta));
    document.getElementById("modal-qty-input").value = modalQty;
    updateModalCalculation();
  }

  function validateModalQty() {
    const val = Number(document.getElementById("modal-qty-input").value) || 1;
    modalQty = Math.max(1, Math.min(99, val));
    document.getElementById("modal-qty-input").value = modalQty;
    updateModalCalculation();
  }

  function updateModalCalculation() {
    if (!currentModalProduct) return;
    const subtotal = currentModalProduct.price * modalQty;
    const dp = Math.round(subtotal * 0.5);

    document.getElementById("modal-subtotal-text").textContent = formatRupiah(subtotal);
    document.getElementById("modal-dp-text").textContent = "DP 50%: " + formatRupiah(dp);
  }

  function confirmModalAddToCart() {
    if (!currentModalProduct) return;
    addToCart(currentModalProduct.id, modalQty, modalSelectedSize, modalSelectedColor);
    closeQuickBuyModal();
    refreshStickyCartBar();
  }

  function confirmModalDirectCheckout() {
    if (!currentModalProduct) return;
    // Tambahkan item ke cart, lalu langsung redirect ke formulir Checkout
    addToCart(currentModalProduct.id, modalQty, modalSelectedSize, modalSelectedColor);
    window.location.href = "checkout.php";
  }

  // 4. REFRESH STICKY FLOATING CART BAR
  function refreshStickyCartBar() {
    const cart = getCart();
    const stickyBar = document.getElementById("sticky-cart-bar");
    if (!stickyBar) return;

    if (cart.length > 0) {
      const totalCount = cart.reduce((acc, item) => acc + item.quantity, 0);
      const totalAmount = cart.reduce((acc, item) => acc + (item.price * item.quantity), 0);

      document.getElementById("sticky-cart-count").textContent = totalCount + " Item";
      document.getElementById("sticky-cart-total").textContent = "Total: " + formatRupiah(totalAmount);
      stickyBar.style.display = "flex";
    } else {
      stickyBar.style.display = "none";
    }
  }

  // 5. TOGGLE MOBILE FILTER SIDEBAR
  function toggleFilterSidebar() {
    const sidebar = document.getElementById("filter-sidebar");
    const mainBtn = document.querySelector(".btn-mobile-filter-toggle");
    const stickyBtn = document.querySelector(".sticky-filter-toggle-btn");
    
    if (sidebar) {
      const isOpen = sidebar.classList.toggle("show-mobile");
      if (mainBtn) {
        mainBtn.textContent = isOpen ? "Tutup Filter" : "Filter & Harga";
      }
      if (stickyBtn) {
        stickyBtn.textContent = isOpen ? "Tutup" : "Filter";
      }
    }
  }

  // Tutup modal jika klik background
  document.getElementById("quick-co-modal")?.addEventListener("click", (e) => {
    if (e.target.id === "quick-co-modal") {
      closeQuickBuyModal();
    }
  });

  // 6. SCROLL HORIZONTAL KATEGORI PILLS
  function scrollCategoryPills(offset) {
    const container = document.getElementById("category-pills");
    if (container) {
      container.scrollBy({ left: offset, behavior: "smooth" });
    }
  }

  // 7. LISTENER TOMBOL KEMBALI KE ATAS (BACK TO TOP)
  window.addEventListener("scroll", () => {
    const btn = document.getElementById("btn-back-to-top");
    if (!btn) return;
    if (window.scrollY > 300) {
      btn.classList.add("show");
    } else {
      btn.classList.remove("show");
    }
  });
</script>
