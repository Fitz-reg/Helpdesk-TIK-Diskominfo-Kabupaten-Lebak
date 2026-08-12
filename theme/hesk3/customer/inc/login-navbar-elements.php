<?php
// This guard is used to ensure that users can't hit this outside of actual HESK code
if (!defined('IN_SCRIPT')) {
    die();
}

function renderLoginNavbarElements($userContext = null) {
    global $hesk_settings, $hesklang;

    if (!$hesk_settings['customer_accounts']) {
        return;
    }

    if ($userContext !== null): ?>
        <div class="dkt-user-menu-wrap out-close">
            <button type="button" class="dkt-user-trigger" data-action="show-profile" aria-expanded="false">
                <span class="dkt-user-avatar">
                    <?php
                    $letter = hesk_mb_substr($userContext['name'], 0, 1);
                    echo hesk_mb_strtoupper($letter);
                    ?>
                </span>
                <span class="dkt-user-name"><?php echo htmlspecialchars($userContext['name']); ?></span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </button>
            <div class="profile__menu dkt-user-dropdown">
                <div class="dkt-user-dropdown-header">
                    <div class="dkt-user-dropdown-title"><?php echo htmlspecialchars($userContext['name']); ?></div>
                    <div class="dkt-user-dropdown-sub"><?php echo htmlspecialchars($userContext['email'] ?? ''); ?></div>
                </div>
                <div class="dkt-user-dropdown-divider"></div>
                <a href="my_tickets.php" class="dkt-user-dropdown-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                    <span>Tiket Saya</span>
                </a>
                <a href="index.php?a=add" class="dkt-user-dropdown-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="16"></line>
                        <line x1="8" y1="12" x2="16" y2="12"></line>
                    </svg>
                    <span>Buat Tiket Bantuan</span>
                </a>
                <a href="profile.php" class="dkt-user-dropdown-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span>Profil Akun</span>
                </a>
                <div class="dkt-user-dropdown-divider"></div>
                <a href="login.php?a=logout&amp;token=<?php hesk_token_echo(); ?>" class="dkt-user-dropdown-item logout">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span>Keluar (Logout)</span>
                </a>
            </div>
        </div>
    <?php else: ?>
    <div class="dkt-auth-btns">
        <a href="login.php" class="dkt-auth-btn login">Masuk</a>
        <?php if ($hesk_settings['customer_accounts_customer_self_register']): ?>
        <a href="register.php" class="dkt-auth-btn register">Daftar</a>
        <?php endif; ?>
    </div>
<?php
    endif;
}

function renderNavbarLanguageSelect() {
    global $hesk_settings, $hesklang;

    if ($hesk_settings['can_sel_lang']) { ?>
        <div class="header__lang">
            <form method="get" action="" aria-label="<?php echo $hesklang['set_lang']; ?>" style="margin:0;padding:0;border:0;white-space:nowrap;">
                <?php if (defined('RENDER_HIDDEN_TICKET_FIELDS') && isset($hesk_settings['hidden_data'])) { ?>
                    <input type="hidden" name="track" value="<?php echo $hesk_settings['hidden_data']['ticket']['trackid']; ?>">
                    <input type="hidden" name="e" value="<?php echo $hesk_settings['hidden_data']['email']; ?>">
                <?php } ?>
                <div class="dropdown-select center out-close">
                    <select name="language" onchange="this.form.submit()">
                        <?php hesk_listLanguages(); ?>
                    </select>
                </div>
                <?php foreach (hesk_getCurrentGetParameters() as $key => $value): ?>
                    <input type="hidden" name="<?php echo hesk_htmlentities($key); ?>"
                           value="<?php echo hesk_htmlentities($value); ?>">
                <?php endforeach; ?>
            </form>
        </div>
    <?php }
}
?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var trigger = document.querySelector(".dkt-user-trigger");
    var wrap = document.querySelector(".dkt-user-menu-wrap");
    if (trigger && wrap) {
        trigger.addEventListener("click", function(e) {
            e.stopPropagation();
            wrap.classList.toggle("open");
        });
        document.addEventListener("click", function(e) {
            if (!wrap.contains(e.target)) {
                wrap.classList.remove("open");
            }
        });
    }
});
</script>