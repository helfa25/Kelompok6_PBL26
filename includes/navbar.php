<?php
/**
 * Fastender Pre-Order Platform
 * Navbar Component (includes/navbar.php)
 */
$activePage = $activePage ?? 'beranda';
?>
<!-- ==========================================
     GLOBAL COMPONENT: NAVBAR (Sisi User)
     ========================================== -->
<header class="navbar">
  <div class="container navbar-container">
    
    <!-- Kiri: Logo + Nama "FastTender" -->
    <a href="index.php" class="navbar-brand">
      <img src="assets/images/logo.png" alt="FastTender Logo" class="brand-logo-img">
      <span class="brand-text">Fast<span style="color: var(--accent);">Tender</span></span>
    </a>

    <!-- Tengah: Menu Navigasi -->
    <nav>
      <ul class="navbar-menu">
        <li>
          <a href="index.php" class="navbar-link <?= ($activePage === 'beranda') ? 'active' : '' ?>">
            Beranda
          </a>
        </li>
        <li>
          <a href="katalog.php" class="navbar-link <?= ($activePage === 'katalog') ? 'active' : '' ?>">
            Katalog
          </a>
        </li>
        <li>
          <a href="lacak.php" class="navbar-link <?= ($activePage === 'lacak') ? 'active' : '' ?>">
            Lacak Pesanan
          </a>
        </li>
        <li>
          <a href="admin-dashboard.php" class="navbar-link" style="color: #93c5fd;">
            Dashboard Admin
          </a>
        </li>
      </ul>
    </nav>

    <!-- Kanan: Tombol Masuk / Admin + Keranjang Belanja -->
    <div class="navbar-actions">
      <!-- Keranjang Belanja Icon -->
      <a href="keranjang.php" class="cart-btn" aria-label="Keranjang Belanja">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="9" cy="21" r="1"></circle>
          <circle cx="20" cy="21" r="1"></circle>
          <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
        </svg>
        <span class="cart-badge" id="navbar-cart-badge">0</span>
      </a>

      <!-- Tombol Masuk / Dashboard Admin Oranye Sesuai Figma Specs -->
      <?php if (isAdminLoggedIn()): ?>
        <a href="admin-dashboard.php" class="btn btn-accent btn-sm" style="display: flex; align-items: center; gap: 6px;">
          <span>⚙️ Panel Admin</span>
        </a>
      <?php else: ?>
        <a href="admin-login.php" class="btn btn-accent btn-sm">
          Masuk / Admin
        </a>
      <?php endif; ?>

      <!-- Tombol Menu Mobile Hamburger -->
      <button class="nav-toggle-btn" aria-label="Buka Menu Navigasi">
        ☰
      </button>
    </div>

  </div>
</header>
