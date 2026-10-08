<?php
/**
 * Fastender Pre-Order Platform
 * Admin Header Topbar Component (includes/admin-header.php)
 */
$adminTitle = $adminTitle ?? 'Dashboard';
?>
<!-- Top Header Admin -->
<header class="admin-header">
  <div style="display: flex; align-items: center; gap: 14px;">
    <button id="toggle-admin-sidebar" class="nav-toggle-btn" style="background: none; border: none; font-size: 22px; cursor: pointer; color: var(--primary);">
      ☰
    </button>
    <div style="font-size: 14px; color: var(--text-muted);">
      <a href="admin-dashboard.php" style="color: var(--primary);">Admin</a> &nbsp;/&nbsp; <strong style="color: var(--text-main);"><?= htmlspecialchars($adminTitle) ?></strong>
    </div>
  </div>

  <div class="header-user-group">
    <div class="admin-profile">
      <div class="admin-avatar">AD</div>
      <div class="admin-info">
        <div class="admin-name">Admin FastTender</div>
        <div class="admin-role">Super Administrator</div>
      </div>
    </div>
  </div>
</header>
