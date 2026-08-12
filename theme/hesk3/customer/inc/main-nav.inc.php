<?php
global $hesk_settings, $hesklang;
/**
 * @var array $customerUserContext - User info for a customer if logged in.  `null` if a customer is not logged in.
 */
/**
 *
 * This file is part of HESK - PHP Help Desk Software.
 *
 * (c) Copyright Klemen Stirn. All rights reserved.
 * https://www.hesk.com
 *
 * For the full copyright and license agreement information visit
 * https://www.hesk.com/eula.php
 *
 */

/* Check if this is a valid include */
if (!defined('IN_SCRIPT')) {die('Invalid attempt');}

require_once(TEMPLATE_PATH . 'customer/inc/login-navbar-elements.php');
?>

<header class="dkt-header">
    <div class="dkt-header-container">
        <a href="<?php echo $hesk_settings['hesk_url']; ?>" class="dkt-brand-badge">
            <img src="<?php echo HESK_PATH; ?>img/DiskominfoLogo.png" alt="Logo Diskominfo Kabupaten Lebak" class="dkt-logo-img">
            <div class="dkt-brand-text">
                <span class="dkt-brand-title">Helpdesk TIK Diskominfo</span>
                <span class="dkt-brand-sub">Pemerintah Kabupaten Lebak</span>
            </div>
        </a>
        
        <nav class="dkt-header-nav">
            <a href="index.php" class="dkt-nav-item active">Beranda</a>
            <a href="index.php?a=add" class="dkt-nav-item">Buat Tiket</a>
            <a href="ticket.php" class="dkt-nav-item">Cek Tiket</a>

        </nav>

        <div style="display: flex; align-items: center; gap: 12px;">
            <?php if (!defined('IGNORE_NAVBAR_RENDER')) {
                renderLoginNavbarElements(isset($customerUserContext) ? $customerUserContext : null);
                renderNavbarLanguageSelect();
            }
            ?>
        </div>
    </div>
</header>
<?php
// Note: We're not using define() in this exception, as there's situations where breadcrumbs can't be set as a constant,
// due to some dynamic link generation in a few cases
global $BREADCRUMBS;
if (isset($BREADCRUMBS) && !empty($BREADCRUMBS)) {
?>
<div class="breadcrumbs">
    <div class="contr">
        <div class="breadcrumbs__inner">
            <?php foreach($BREADCRUMBS as $breadcrumb):
                if (!empty($breadcrumb['url'])) {
                    // If URL is defined, assume it's not the last breadcrumb, and there's another after it
                    ?>
                    <a href="<?php echo $breadcrumb['url']; ?>">
                        <span><?php echo $breadcrumb['title']; ?></span>
                    </a>
                    <svg class="icon icon-chevron-right">
                        <use xlink:href="<?php echo TEMPLATE_PATH; ?>customer/img/sprite.svg#icon-chevron-right"></use>
                    </svg>
                <?php
                } else {
                    // If URL NOT defined, assume this is the last one, and don't print another chevron or URL
                    ?>
                    <div class="last"><?php echo $breadcrumb['title']; ?></div>
                    <?php
                }
            endforeach; ?>
        </div>
    </div>
</div>
<?php
} ?>