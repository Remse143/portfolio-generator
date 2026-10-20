
<?php
$currentPage = basename($_SERVER['PHP_SELF']);

$navPages = [
    'dashboard.php' => [
        'label' => 'Dashboard',
        'icon' => 'fa-house'
    ],
    'manage.php' => [
        'label' => 'My Portfolio',
        'icon' => 'fa-folder-open'
    ],
    'templates.php' => [
        'label' => 'Templates',
        'icon' => 'fa-layer-group'
    ]
];
?>

<header class="mg-navbar">
    <div class="mg-nav-inner">

        <a href="index.php" class="mg-logo">
            Portfolio<span>Gen</span>
        </a>

        <nav class="mg-nav-links" aria-label="Main navigation">

            <?php foreach ($navPages as $url => $item): ?>
                <a
                    href="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>"
                    class="<?= $currentPage === $url ? 'active' : '' ?>"
                    <?= $currentPage === $url ? 'aria-current="page"' : '' ?>
                >
                    <i class="fa-solid <?= htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8') ?>"></i>
                    <?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?>
                </a>
            <?php endforeach; ?>

            <a href="logout.php" class="mg-logout">
                <i class="fa-solid fa-right-from-bracket"></i>
                Logout
            </a>

        </nav>
    </div>
</header>