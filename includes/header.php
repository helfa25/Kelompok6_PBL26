<?php
/**
 * Fastender Pre-Order Platform
 * Header Component (includes/header.php)
 */
require_once __DIR__ . '/functions.php';

$pageTitle = $pageTitle ?? 'FastTender - Platform E-Commerce Pre-Order Apparel & Merchandise';
$activePage = $activePage ?? 'beranda';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?></title>
  
  <!-- Google Fonts: Poppins (Heading) & Inter (Body) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- Global Stylesheet -->
  <link rel="stylesheet" href="css/style.css">
  <?php if (!empty($extraCss)): ?>
    <link rel="stylesheet" href="<?= htmlspecialchars($extraCss) ?>">
  <?php endif; ?>

  <!-- Favicon / Brand Icon -->
  <link rel="icon" type="image/png" href="assets/images/favicon.png">
  <link rel="apple-touch-icon" href="assets/images/favicon.png">
</head>
<body>
