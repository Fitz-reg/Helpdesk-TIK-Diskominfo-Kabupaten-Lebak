<?php
global $hesk_settings, $hesklang;
/**
 * @var bool $customerLoggedIn - `true` if a customer is logged in, `false` otherwise
 * @var array $customerUserContext - User info for a customer if logged in. `null` if a customer is not logged in.
 */

// This guard is used to ensure that users can't hit this outside of actual HESK code
if (!defined('IN_SCRIPT')) {
    die();
}
define('EXTRA_PAGE_CLASSES','page-category-select');
define('ALERTS',1);
define('RENDER_COMMON_ELEMENTS',1);

global $BREADCRUMBS;
$BREADCRUMBS = array(
    array('url' => $hesk_settings['site_url'], 'title' => $hesk_settings['site_title']),
    array('url' => $hesk_settings['hesk_url'], 'title' => $hesk_settings['hesk_title']),
    array('title' => 'Pilih Layanan TIK')
);

/* Print header */
require_once(TEMPLATE_PATH . 'customer/inc/header.inc.php');

$categoryImages = array(
    1 => 'img/Absen.jpeg',
    2 => 'img/TandaTangan.jpeg',
    3 => 'img/LayananSubdomain.avif',
    4 => 'img/Layanan Migrasi Hosting.webp',
    5 => 'img/Layanan Opendata.jpg',
    6 => 'img/Layanan PPID Kabupaten.avif',
    7 => 'img/Layanan Jaringan Intra.jpg',
    8 => 'img/Layanan Fasilitas Zoom Meeting.avif',
    9 => 'img/Layanan Live Streaming.jpeg',
    10 => 'img/Layanan Integrasi Aplikasi Website.png',
    11 => 'img/Layanan Pengembangan Aplikasi Website.jpg'
);
?>

<!-- HERO BANNER -->
<section class="dkt-ticket-hero">
    <div class="dkt-ticket-hero-container">
        <div class="dkt-category-badge">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="12" y1="18" x2="12" y2="12"></line><line x1="9" y1="15" x2="15" y2="15"></line></svg>
            Pilih Kategori Permohonan
        </div>
        <h1 class="dkt-ticket-hero-title">Layanan TIK Diskominfo Kabupaten Lebak</h1>
        <p class="dkt-ticket-hero-sub">Pilih salah satu jenis layanan TIK di bawah ini untuk melanjutkan pengisian formulir permohonan atau pelaporan kendala teknis.</p>
    </div>
</section>

<div class="main__content" style="padding-top: 36px; padding-bottom: 60px;">
    <div class="contr">
        <div style="margin-bottom: 20px;">
            <?php hesk3_show_messages($messages); ?>
            <?php hesk3_show_messages($serviceMessages); ?>
        </div>

        <!-- SEARCH INPUT -->
        <div class="dkt-service-search-wrapper" style="margin-top: 0;">
            <div class="dkt-service-search-inner">
                <svg class="dkt-search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" id="dktCatSelectSearch" class="dkt-service-search-input" placeholder="Cari kategori layanan TIK ..." onkeyup="dktFilterCatSelect()">
            </div>
        </div>

        <!-- 4-COLUMN SERVICE IMAGE CARD GRID -->
        <div class="dkt-card-grid-5col">
            <?php foreach ($hesk_settings['categories'] as $k => $v): 
                $imgSrc = isset($categoryImages[$k]) ? HESK_PATH . $categoryImages[$k] : HESK_PATH . 'img/hero-bg.png';
            ?>
            <a href="index.php?a=add&amp;category=<?php echo $k; ?>" class="dkt-service-card-new dkt-cat-select-card" data-title="<?php echo htmlspecialchars($v['name']); ?>">
                <div class="dkt-service-card-img-wrap">
                    <img src="<?php echo $imgSrc; ?>" alt="<?php echo htmlspecialchars($v['name']); ?>">
                </div>
                <div class="dkt-service-card-banner">
                    <?php echo htmlspecialchars($v['name']); ?>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
function dktFilterCatSelect() {
    var input = document.getElementById('dktCatSelectSearch');
    var filter = input.value.toLowerCase().trim();
    var cards = document.querySelectorAll('.dkt-cat-select-card');
    cards.forEach(function(card) {
        var title = card.getAttribute('data-title').toLowerCase();
        if (title.indexOf(filter) > -1) {
            card.style.display = "flex";
        } else {
            card.style.display = "none";
        }
    });
}
</script>

<?php
/* Print Footer */
require_once(TEMPLATE_PATH . 'customer/inc/footer.inc.php');
?>
