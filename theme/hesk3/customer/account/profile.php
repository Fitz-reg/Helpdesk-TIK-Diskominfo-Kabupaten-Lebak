<?php
global $hesk_settings, $hesklang;
/**
 * @var array $customerUserContext
 * @var array|null $pendingEmailChange
 * @var boolean $userCanChangeEmail
 * @var array $messages
 * @var array $serviceMessages
 * @var array $validationFailures
 */

if (!defined('IN_SCRIPT')) { die(); }
define('EXTRA_PAGE_CLASSES','page-profile');
define('ALERTS',1);
define('RENDER_COMMON_ELEMENTS',1);

global $BREADCRUMBS;
$BREADCRUMBS = array(
    array('url' => $hesk_settings['site_url'], 'title' => $hesk_settings['site_title']),
    array('url' => $hesk_settings['hesk_url'], 'title' => $hesk_settings['hesk_title']),
    array('title' => 'Profil Pengguna')
);

require_once(TEMPLATE_PATH . 'customer/inc/header.inc.php');
?>

<!-- HERO BANNER -->
<section class="dkt-profile-hero">
    <div class="dkt-profile-hero-inner">
        <div style="font-size: 0.88rem; color: rgba(255,255,255,0.8); margin-bottom: 8px;">
            <a href="index.php" style="color: rgba(255,255,255,0.9); text-decoration: none;">Beranda</a>
            <span>/</span>
            <span>Pengaturan Akun</span>
        </div>
        <h1 style="font-size: 2.1rem; font-weight: 800; color: #ffffff; margin: 0 0 6px 0;">Pengaturan Profil & Keamanan</h1>
        <p style="font-size: 0.95rem; color: rgba(255,255,255,0.85); margin: 0;">Kelola data pribadi, perbarui kata sandi, dan atur verifikasi dua langkah (MFA).</p>
    </div>
</section>

<!-- MAIN PROFILE CARDS GRID -->
<div class="dkt-profile-grid">

    <!-- CARD 1: INFORMASI PROFIL & EMAIL -->
    <div class="dkt-profile-card">
        <div class="dkt-profile-card-header">
            <div class="dkt-profile-card-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <h2 class="dkt-profile-card-title">Informasi Akun</h2>
        </div>

        <div style="margin-bottom: 16px;">
            <?php hesk3_show_messages($serviceMessages); ?>
            <?php hesk3_show_messages($messages); ?>
        </div>

        <form action="profile.php" method="post" novalidate>
            <div class="dkt-profile-field">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" maxlength="255"
                       class="dkt-profile-input <?php if (in_array('name', $validationFailures)) {echo 'isError';} ?>"
                       value="<?php echo htmlspecialchars($customerUserContext['name']); ?>" required>
            </div>

            <div class="dkt-profile-field">
                <label for="email">Alamat Email</label>
                <input type="email" id="email_display"
                       class="dkt-profile-input"
                       value="<?php echo htmlspecialchars($customerUserContext['email']); ?>"
                       <?php echo $userCanChangeEmail ? '' : 'readonly style="background:#f1f5f9; color:#64748b;"'; ?>>
            </div>

            <input type="hidden" name="action" value="profile">
            <input type="hidden" name="token" value="<?php hesk_token_echo(); ?>">
            <button type="submit" class="dkt-profile-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                Simpan Perubahan
            </button>
        </form>

        <?php if ($userCanChangeEmail && !is_null($pendingEmailChange)): ?>
            <div style="margin-top: 20px; padding: 14px; background: #fffbeb; border: 1px solid #fef3c7; border-radius: 10px; font-size: 0.88rem; color: #92400e;">
                <?php echo sprintf($hesklang['customer_change_email_pending'], htmlspecialchars($pendingEmailChange['new_email'])); ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- CARD 2: UBAH KATA SANDI -->
    <div class="dkt-profile-card">
        <div class="dkt-profile-card-header">
            <div class="dkt-profile-card-icon" style="background: #fef3c7; color: #d97706;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </div>
            <h2 class="dkt-profile-card-title">Ubah Kata Sandi</h2>
        </div>

        <form action="profile.php" method="post" novalidate>
            <div class="dkt-profile-field">
                <label for="current-password">Kata Sandi Saat Ini</label>
                <input type="password" id="current-password" name="current-password" maxlength="255"
                       class="dkt-profile-input <?php if (in_array('current-password', $validationFailures)) {echo 'isError';} ?>"
                       placeholder="Masukkan kata sandi lama" required>
            </div>

            <div class="dkt-profile-field">
                <label for="password">Kata Sandi Baru</label>
                <input type="password" id="password" name="password" maxlength="255"
                       class="dkt-profile-input <?php if (in_array('password', $validationFailures)) {echo 'isError';} ?>"
                       placeholder="Minimal 6 karakter" required>
            </div>

            <div class="dkt-profile-field">
                <label for="confirm-password">Konfirmasi Kata Sandi Baru</label>
                <input type="password" id="confirm-password" name="confirm-password" maxlength="255"
                       class="dkt-profile-input <?php if (in_array('confirm-password', $validationFailures)) {echo 'isError';} ?>"
                       placeholder="Ulangi kata sandi baru" required>
            </div>

            <input type="hidden" name="action" value="password">
            <input type="hidden" name="token" value="<?php hesk_token_echo(); ?>">
            <button type="submit" class="dkt-profile-btn" style="background: #0f172a;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                Perbarui Kata Sandi
            </button>
        </form>
    </div>

    <!-- CARD 3: KEAMANAN DUA LANGKAH (MFA) -->
    <div class="dkt-profile-card" style="grid-column: span 2;">
        <div class="dkt-profile-card-header">
            <div class="dkt-profile-card-icon" style="background: #f0fdf4; color: #16a34a;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
            </div>
            <div>
                <h2 class="dkt-profile-card-title">Verifikasi Dua Langkah (MFA)</h2>
                <div style="font-size: 0.85rem; color: #64748b; margin-top: 2px;">Lindungi akun Anda dengan menambahkan lapisan keamanan ekstra.</div>
            </div>
        </div>

        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; background: #f8fafc; padding: 18px 24px; border-radius: 12px; border: 1px solid #e2e8f0;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <span style="width: 10px; height: 10px; border-radius: 50%; background: <?php echo ($customerUserContext['mfa_enrollment'] !== '0') ? '#22c55e' : '#ef4444'; ?>;"></span>
                <span style="font-size: 0.95rem; font-weight: 600; color: #0f172a;">
                    Status MFA: <?php echo ($customerUserContext['mfa_enrollment'] !== '0') ? 'Aktif' : 'Nonaktif'; ?>
                </span>
            </div>
            <a href="manage_mfa.php" class="dkt-profile-btn" style="background: #ffffff; color: #0f172a; border: 1px solid #cbd5e1; margin-top: 0;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                Kelola MFA
            </a>
        </div>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    $('input[name="password"]').keyup(function() {
        if (typeof HESK_FUNCTIONS !== 'undefined') {
            HESK_FUNCTIONS.checkPasswordStrength(this.value);
        }
    });
});
</script>

<?php
require_once(TEMPLATE_PATH . 'customer/inc/footer.inc.php');
?>
