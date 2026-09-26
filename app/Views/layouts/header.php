<?php
/**
 * Shared document head. Views may set $bodyClass before this layout runs.
 *
 * @var string|null $pageTitle
 * @var string|null $bodyClass
 */
$pageTitle = $pageTitle ?? 'Universitas Contoh';
$bodyClass = $bodyClass ?? 'bg-slate-100 text-slate-800 font-sans antialiased';
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle); ?> - Universitas Contoh</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="<?= esc($bodyClass); ?>">
