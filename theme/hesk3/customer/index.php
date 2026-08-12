<?php
global $hesk_settings, $hesklang;

/**
 * @var array $topArticles - Collection of top knowledgebase articles
 * @var array $latestArticles - Collection of newest/latest knowledgebase articles
 * @var array $serviceMessages - Collection of service messages to be displayed
 * @var array $messages - Collection of feedback messages to be displayed (such as "You have been logged out")
 * @var bool $accountRequired - `true` if an account is required to use the helpdesk, `false` otherwise
 * @var bool $customerLoggedIn - `true` if a customer is logged in, `false` otherwise
 * @var array $customerUserContext - User info for a customer if logged in. `null` if a customer is not logged in.
 */
if (!defined('IN_SCRIPT')) {
    die();
}
define('EXTRA_PAGE_CLASSES','page-index');

define('ALERTS',1);
define('KBSEARCH',1);
define('RATING',1);

define('OUTPUT_SEARCH_STYLING',1);
define('RENDER_COMMON_ELEMENTS',1);
define('OUTPUT_SEARCH_JAVASCRIPT',1);

global $BREADCRUMBS;
$BREADCRUMBS = array(
    array('url' => 'index.php', 'title' => 'Beranda'),
    array('title' => 'Helpdesk TIK Diskominfo Kabupaten Lebak')
);

/* Print header */
require_once(TEMPLATE_PATH . 'customer/inc/header.inc.php');
?>

<!-- ==================== HERO SECTION ==================== -->
<section class="dkt-hero">
    <div class="dkt-hero-container">
        <h1 class="dkt-hero-title">Portal Layanan TIK Diskominfo Kabupaten Lebak</h1>
        <p class="dkt-hero-sub">Dukungan terpadu Absen Online E-OFFICE, Tanda Tangan Elektronik (TTE), Subdomain, Jaringan Intra, dan Pengembangan Aplikasi Pemkab Lebak.</p>

        <?php if ($hesk_settings['kb_enable']): ?>
        <div class="dkt-search-wrapper">
            <?php displayKbSearch(); ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<div class="main__content" style="padding-top: 0;">
    <div class="contr">
        <?php if (!empty($messages)): ?>
        <div style="margin-top: 20px;">
            <?php hesk3_show_messages($messages); ?>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($serviceMessages)): ?>
        <div style="margin-top: 20px;">
            <?php hesk3_show_messages($serviceMessages); ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- ==================== QUICK ACTION GRID ==================== -->
    <div class="dkt-section">
        <div class="dkt-quick-grid">
            <!-- CARD 1: Buat Tiket Bantuan -->
            <a href="index.php?a=add" class="dkt-action-card">
                <div class="dkt-action-card-img">
                    <img src="<?php echo HESK_PATH; ?>img/card_buat_tiket.png" alt="Buat Tiket Bantuan">
                </div>
                <div class="dkt-action-card-body">
                    <h3 class="dkt-action-card-title"><?php echo $hesklang['submit_ticket']; ?></h3>
                    <p class="dkt-action-card-desc">Buat tiket pengajuan permohonan layanan TIK atau pelaporan kendala teknis perangkat daerah.</p>
                </div>
            </a>

            <!-- CARD 2: Cek Status Tiket / My Tickets -->
            <?php if ($accountRequired || $customerLoggedIn): ?>
            <a href="my_tickets.php" class="dkt-action-card">
                <div class="dkt-action-card-img">
                    <img src="<?php echo HESK_PATH; ?>img/card_cek_status.png" alt="Tiket Saya">
                </div>
                <div class="dkt-action-card-body">
                    <h3 class="dkt-action-card-title"><?php echo $hesklang['customer_my_tickets_heading']; ?></h3>
                    <p class="dkt-action-card-desc">Lihat riwayat seluruh tiket pengajuan Anda dan komunikasi aktif bersama tim Diskominfo.</p>
                </div>
            </a>
            <?php else: ?>
            <a href="ticket.php" class="dkt-action-card">
                <div class="dkt-action-card-img">
                    <img src="<?php echo HESK_PATH; ?>img/card_cek_status.png" alt="Cek Status Tiket">
                </div>
                <div class="dkt-action-card-body">
                    <h3 class="dkt-action-card-title"><?php echo $hesklang['view_existing_tickets']; ?></h3>
                    <p class="dkt-action-card-desc">Cek status tiket yang telah dikirimkan menggunakan Nomor Tiket (Track ID) &amp; Email Anda.</p>
                </div>
            </a>
            <?php endif; ?>
        </div>
    </div>


    <!-- ==================== TIK SERVICES & SEARCH ==================== -->
    <div class="dkt-container">

        <!-- Service Categories Header & Search -->
        <div class="dkt-section-header">
            <div>
                <h2 class="dkt-section-title">Katalog Layanan TIK Diskominfo Kabupaten Lebak</h2>
                <div class="dkt-section-sub">Pilih jenis layanan di bawah ini untuk mengajukan permohonan atau melaporkan kendala teknis</div>
            </div>
        </div>

        <!-- SEARCH BAR -->
        <div class="dkt-service-search-wrapper">
            <div class="dkt-service-search-inner">
                <svg class="dkt-search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" id="dktServiceSearch" class="dkt-service-search-input" placeholder="Cari layanan ..." onkeyup="dktFilterServices()">
            </div>
        </div>

        <!-- 11 OFFICIAL DISKOMINFO LEBAK SERVICE IMAGE CARDS (5-COLUMN GRID) -->
        <div class="dkt-card-grid-5col">
            <a href="index.php?a=add&category=1" class="dkt-service-card-new" data-title="Layanan Absen Online E-OFFICE">
                <div class="dkt-service-card-img-wrap">
                    <img src="<?php echo HESK_PATH; ?>img/Absen.jpeg" alt="Layanan Absen Online E-OFFICE">
                </div>
                <div class="dkt-service-card-banner">
                    Layanan Absen Online E-OFFICE
                </div>
            </a>

            <a href="index.php?a=add&category=2" class="dkt-service-card-new" data-title="Sertifikat Elektronik TTE">
                <div class="dkt-service-card-img-wrap">
                    <img src="<?php echo HESK_PATH; ?>img/TandaTangan.jpeg" alt="Sertifikat Elektronik TTE">
                </div>
                <div class="dkt-service-card-banner">
                    Sertifikat Elektronik (TTE)
                </div>
            </a>

            <a href="index.php?a=add&category=3" class="dkt-service-card-new" data-title="Subdomain Lebakkab.go.id">
                <div class="dkt-service-card-img-wrap">
                    <img src="<?php echo HESK_PATH; ?>img/LayananSubdomain.avif" alt="Subdomain Lebakkab.go.id">
                </div>
                <div class="dkt-service-card-banner">
                    Subdomain Lebakkab.go.id
                </div>
            </a>

            <a href="index.php?a=add&category=4" class="dkt-service-card-new" data-title="Migrasi Hosting Server Kab. Lebak">
                <div class="dkt-service-card-img-wrap">
                    <img src="<?php echo HESK_PATH; ?>img/Layanan Migrasi Hosting.webp" alt="Migrasi Hosting Server">
                </div>
                <div class="dkt-service-card-banner">
                    Migrasi Hosting / Server
                </div>
            </a>

            <a href="index.php?a=add&category=5" class="dkt-service-card-new" data-title="Opendata Data Sektoral Kab. Lebak">
                <div class="dkt-service-card-img-wrap">
                    <img src="<?php echo HESK_PATH; ?>img/Layanan Opendata.jpg" alt="Data Sektoral Opendata">
                </div>
                <div class="dkt-service-card-banner">
                    Data Sektoral & Opendata
                </div>
            </a>

            <a href="index.php?a=add&category=6" class="dkt-service-card-new" data-title="Layanan PPID Kabupaten Lebak">
                <div class="dkt-service-card-img-wrap">
                    <img src="<?php echo HESK_PATH; ?>img/Layanan PPID Kabupaten.avif" alt="Layanan PPID Kabupaten Lebak">
                </div>
                <div class="dkt-service-card-banner">
                    Layanan PPID Kab. Lebak
                </div>
            </a>

            <a href="index.php?a=add&category=7" class="dkt-service-card-new" data-title="Layanan Jaringan Intra OPD">
                <div class="dkt-service-card-img-wrap">
                    <img src="<?php echo HESK_PATH; ?>img/Layanan Jaringan Intra.jpg" alt="Layanan Jaringan Intra">
                </div>
                <div class="dkt-service-card-banner">
                    Jaringan Intra OPD
                </div>
            </a>

            <a href="index.php?a=add&category=8" class="dkt-service-card-new" data-title="Virtual Meeting Zoom Diskominfo">
                <div class="dkt-service-card-img-wrap">
                    <img src="<?php echo HESK_PATH; ?>img/Layanan Fasilitas Zoom Meeting.avif" alt="Virtual Meeting Zoom">
                </div>
                <div class="dkt-service-card-banner">
                    Virtual Meeting (Zoom)
                </div>
            </a>

            <a href="index.php?a=add&category=9" class="dkt-service-card-new" data-title="Layanan Live Streaming Siaran">
                <div class="dkt-service-card-img-wrap">
                    <img src="<?php echo HESK_PATH; ?>img/Layanan Live Streaming.jpeg" alt="Layanan Live Streaming">
                </div>
                <div class="dkt-service-card-banner">
                    Layanan Live Streaming
                </div>
            </a>

            <a href="index.php?a=add&category=10" class="dkt-service-card-new" data-title="Integrasi Aplikasi Website API">
                <div class="dkt-service-card-img-wrap">
                    <img src="<?php echo HESK_PATH; ?>img/Layanan Integrasi Aplikasi Website.png" alt="Integrasi Aplikasi Website">
                </div>
                <div class="dkt-service-card-banner">
                    Integrasi Aplikasi (API)
                </div>
            </a>

            <a href="index.php?a=add&category=11" class="dkt-service-card-new" data-title="Pengembangan Aplikasi Website OPD">
                <div class="dkt-service-card-img-wrap">
                    <img src="<?php echo HESK_PATH; ?>img/Layanan Pengembangan Aplikasi Website.jpg" alt="Pengembangan Aplikasi Website">
                </div>
                <div class="dkt-service-card-banner">
                    Pengembangan Aplikasi
                </div>
            </a>
        </div>

        <!-- LOAD MORE BUTTON -->
        <div class="dkt-load-more-wrapper" id="dktLoadMoreWrapper">
            <button type="button" id="dktLoadMoreBtn" class="dkt-load-more-btn" onclick="dktToggleLoadMore()">
                <span>Tampilkan Lebih Banyak</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" id="dktLoadMoreIcon">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </button>
        </div>
    </div>

    <script>
    var dktIsExpanded = false;
    var dktInitialCount = 8;

    function dktApplyCardVisibility() {
        var input = document.getElementById('dktServiceSearch');
        var filter = input ? input.value.toLowerCase().trim() : '';
        var cards = document.querySelectorAll('.dkt-service-card-new');
        var wrapper = document.getElementById('dktLoadMoreWrapper');
        var btn = document.getElementById('dktLoadMoreBtn');
        var icon = document.getElementById('dktLoadMoreIcon');

        if (filter !== '') {
            // Searching active: reveal matching cards
            if (wrapper) wrapper.style.display = 'none';
            cards.forEach(function(card) {
                var title = card.getAttribute('data-title').toLowerCase();
                if (title.indexOf(filter) > -1) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        } else {
            // Normal pagination state
            if (wrapper) wrapper.style.display = 'block';
            cards.forEach(function(card, index) {
                if (dktIsExpanded || index < dktInitialCount) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });

            if (btn && icon) {
                if (dktIsExpanded) {
                    btn.querySelector('span').textContent = 'Tampilkan Lebih Sedikit';
                    icon.style.transform = 'rotate(180deg)';
                } else {
                    btn.querySelector('span').textContent = 'Tampilkan Lebih Banyak';
                    icon.style.transform = 'rotate(0deg)';
                }
            }
        }
    }

    function dktToggleLoadMore() {
        dktIsExpanded = !dktIsExpanded;
        dktApplyCardVisibility();
    }

    function dktFilterServices() {
        dktApplyCardVisibility();
    }

    document.addEventListener('DOMContentLoaded', function() {
        dktApplyCardVisibility();
    });
    </script>

    <!-- ==================== DYNAMIC KNOWLEDGEBASE ARTICLES ==================== -->
    <?php if ($hesk_settings['kb_enable']): ?>
    <div class="contr" style="margin-bottom: 40px;">
        <article class="article">
            <h2 class="article__heading">
                <a href="knowledgebase.php">
                    <span class="icon-in-circle" aria-hidden="true">
                        <svg class="icon icon-knowledge">
                            <use xlink:href="<?php echo TEMPLATE_PATH; ?>customer/img/sprite.svg#icon-knowledge"></use>
                        </svg>
                    </span>
                    <span><?php echo $hesklang['kb_text']; ?> & Panduan Penggunaan</span>
                </a>
            </h2>
            <div class="tabbed__head">
                <ul class="tabbed__head_tabs">
                    <?php if (count($topArticles) > 0): ?>
                    <li class="current" data-link="tab1">
                        <span><?php echo $hesklang['popart']; ?></span>
                    </li>
                    <?php endif; ?>
                    <?php if (count($latestArticles) > 0): ?>
                    <li <?php echo (count($topArticles) === 0) ? 'class="current"' : ''; ?> data-link="tab2">
                        <span><?php echo $hesklang['latart']; ?></span>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="tabbed__tabs">
                <?php if (count($topArticles) > 0): ?>
                <div class="tabbed__tabs_tab is-visible" data-tab="tab1">
                    <?php foreach ($topArticles as $article): ?>
                    <a href="knowledgebase.php?article=<?php echo $article['id']; ?>" class="preview">
                        <span class="icon-in-circle" aria-hidden="true">
                            <svg class="icon icon-knowledge">
                                <use xlink:href="<?php echo TEMPLATE_PATH; ?>customer/img/sprite.svg#icon-knowledge"></use>
                            </svg>
                        </span>
                        <div class="preview__text">
                            <h3 class="preview__title"><?php echo $article['subject']; ?></h3>
                            <p>
                                <span class="lightgrey"><?php echo $hesklang['kb_cat']; ?>:</span>
                                <span class="ml-1"><?php echo $article['category']; ?></span>
                            </p>
                            <p class="navlink__descr">
                                <?php echo $article['content_preview']; ?>
                            </p>
                        </div>
                        <?php if ($hesk_settings['kb_views'] || $hesk_settings['kb_rating']): ?>
                            <div class="rate">
                                <?php if ($hesk_settings['kb_views']): ?>
                                    <div style="margin-right: 10px; display: flex; align-items: center;">
                                        <svg class="icon icon-eye-close">
                                            <use xlink:href="<?php echo TEMPLATE_PATH; ?>customer/img/sprite.svg#icon-eye-close"></use>
                                        </svg>
                                        <span class="lightgrey"><?php echo $article['views_formatted']; ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if ($hesk_settings['kb_rating']): ?>
                                    <?php echo hesk3_get_customer_rating($article['rating']); ?>
                                    <?php if ($hesk_settings['kb_views']) echo '<span class="lightgrey">('.$article['votes_formatted'].')</span>'; ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if (count($latestArticles) > 0): ?>
                <div class="tabbed__tabs_tab <?php echo count($topArticles) === 0 ? 'is-visible' : ''; ?>" data-tab="tab2">
                    <?php foreach ($latestArticles as $article): ?>
                    <a href="knowledgebase.php?article=<?php echo $article['id']; ?>" class="preview">
                        <span class="icon-in-circle" aria-hidden="true">
                            <svg class="icon icon-knowledge">
                                <use xlink:href="<?php echo TEMPLATE_PATH; ?>customer/img/sprite.svg#icon-knowledge"></use>
                            </svg>
                        </span>
                        <div class="preview__text">
                            <h3 class="preview__title"><?php echo $article['subject']; ?></h3>
                            <p>
                                <span class="lightgrey"><?php echo $hesklang['kb_cat']; ?>:</span>
                                <span class="ml-1"><?php echo $article['category']; ?></span>
                            </p>
                            <p class="navlink__descr">
                                <?php echo $article['content_preview']; ?>
                            </p>
                        </div>
                        <?php if ($hesk_settings['kb_views'] || $hesk_settings['kb_rating']): ?>
                            <div class="rate">
                                <?php if ($hesk_settings['kb_views']): ?>
                                    <div style="margin-right: 10px; display: flex; align-items: center;">
                                        <svg class="icon icon-eye-close">
                                            <use xlink:href="<?php echo TEMPLATE_PATH; ?>customer/img/sprite.svg#icon-eye-close"></use>
                                        </svg>
                                        <span class="lightgrey"><?php echo $article['views_formatted']; ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if ($hesk_settings['kb_rating']): ?>
                                    <?php echo hesk3_get_customer_rating($article['rating']); ?>
                                    <?php if ($hesk_settings['kb_views']) echo '<span class="lightgrey">('.$article['votes_formatted'].')</span>'; ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="article__footer">
                <a href="knowledgebase.php" class="btn btn--blue-border" ripple="ripple"><?php echo $hesklang['viewkb']; ?></a>
            </div>
        </article>

        <?php if (!$customerLoggedIn && $hesk_settings['alink']): ?>
        <div class="article__footer" style="margin-top: 20px;">
            <a href="<?php echo $hesk_settings['admin_dir']; ?>/" class="link"><?php echo $hesklang['ap']; ?></a>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<?php
/* Print Footer */
require_once(TEMPLATE_PATH . 'customer/inc/footer.inc.php');
?>
