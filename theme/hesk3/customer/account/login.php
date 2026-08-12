<?php
global $hesk_settings, $hesklang;
/**
 * @var array $messages
 * @var array $model
 * @var array $validationFailures
 * @var bool $displayForgotPasswordLink
 * @var bool $submittedForgotPasswordForm
 * @var bool $displayForgotPasswordModal
 * @var string $redirectUrl
 * @var bool $allowAutologin
 * @var bool $selectAutologin
 * @var bool $selectSaveEmail
 * @var bool $selectDoNotRemember
 */

if (!defined('IN_SCRIPT')) { die(); }
define('EXTRA_PAGE_CLASSES','page-login');
define('ALERTS',1);
define('RENDER_COMMON_ELEMENTS',1);
define('LOAD_CSS_MODAL',1);
define('LOAD_JS_JQUERY_MODAL',1);

global $BREADCRUMBS;
$BREADCRUMBS = array(
    array('url' => $hesk_settings['site_url'], 'title' => $hesk_settings['site_title']),
    array('url' => $hesk_settings['hesk_url'], 'title' => $hesk_settings['hesk_title']),
    array('title' => 'Login Akun')
);

require_once(TEMPLATE_PATH . 'customer/inc/header.inc.php');
?>

<style>
/* === DKT LOGIN PAGE === */
.dkt-login-page {
    min-height: 100vh;
    background: #f0f4f8;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 16px;
}

.dkt-login-card {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 4px 40px rgba(0,0,0,0.10);
    width: 100%;
    max-width: 440px;
    padding: 48px 44px 40px;
}

.dkt-login-logo {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 28px;
}

.dkt-login-logo img {
    height: 56px;
    width: auto;
    object-fit: contain;
    margin-bottom: 10px;
}

.dkt-login-logo-title {
    font-size: 0.78rem;
    font-weight: 600;
    color: #64748b;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    text-align: center;
}

.dkt-login-heading {
    font-size: 1.65rem;
    font-weight: 700;
    color: #0f172a;
    text-align: center;
    margin-bottom: 6px;
    letter-spacing: -0.02em;
}

.dkt-login-sub {
    font-size: 0.92rem;
    color: #64748b;
    text-align: center;
    margin-bottom: 32px;
}

.dkt-login-alerts {
    margin-bottom: 18px;
}

.dkt-login-field {
    margin-bottom: 18px;
}

.dkt-login-field label {
    display: block;
    font-size: 0.86rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.dkt-login-input {
    width: 100%;
    height: 48px;
    padding: 0 16px;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.96rem;
    color: #0f172a;
    background: #f8fafc;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    box-sizing: border-box;
    outline: none;
    font-family: inherit;
}

.dkt-login-input:focus {
    border-color: #0284c7;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(2,132,199,0.12);
}

.dkt-login-input.iserror {
    border-color: #ef4444;
    background: #fef2f2;
}

.dkt-login-input::placeholder {
    color: #94a3b8;
}

.dkt-login-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 26px;
}

.dkt-login-remember {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.86rem;
    color: #374151;
    cursor: pointer;
    user-select: none;
}

.dkt-login-remember input[type="checkbox"] {
    width: 16px;
    height: 16px;
    accent-color: #0284c7;
    cursor: pointer;
}

.dkt-login-forgot {
    font-size: 0.86rem;
    color: #0284c7;
    font-weight: 500;
    text-decoration: none;
    transition: color 0.2s;
}

.dkt-login-forgot:hover {
    color: #0369a1;
    text-decoration: underline;
}

.dkt-login-btn {
    width: 100%;
    height: 50px;
    background: #0f172a;
    color: #ffffff;
    border: none;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    letter-spacing: 0.01em;
    transition: background 0.2s, transform 0.1s, box-shadow 0.2s;
    font-family: inherit;
}

.dkt-login-btn:hover {
    background: #1e293b;
    box-shadow: 0 4px 20px rgba(15,23,42,0.25);
    transform: translateY(-1px);
}

.dkt-login-btn:active {
    transform: translateY(0);
}

.dkt-login-divider {
    display: flex;
    align-items: center;
    gap: 14px;
    margin: 24px 0;
    color: #94a3b8;
    font-size: 0.84rem;
}

.dkt-login-divider::before,
.dkt-login-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e2e8f0;
}

.dkt-login-register-row {
    text-align: center;
    font-size: 0.9rem;
    color: #64748b;
    margin-top: 4px;
}

.dkt-login-register-row a {
    color: #0284c7;
    font-weight: 700;
    text-decoration: none;
    transition: color 0.2s;
}

.dkt-login-register-row a:hover {
    color: #0369a1;
    text-decoration: underline;
}

.dkt-login-back {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    margin-top: 22px;
    font-size: 0.86rem;
    color: #64748b;
    text-decoration: none;
    transition: color 0.2s;
}

.dkt-login-back:hover { color: #0284c7; }

.dkt-login-error-hint {
    font-size: 0.78rem;
    color: #ef4444;
    margin-top: 5px;
    display: none;
}

.dkt-login-input.iserror + .dkt-login-error-hint {
    display: block;
}
</style>

<div class="dkt-login-page">
    <div class="dkt-login-card">

        <!-- LOGO -->
        <div class="dkt-login-logo">
            <img src="<?php echo HESK_PATH; ?>img/DiskominfoLogo.png" alt="Diskominfo Kabupaten Lebak">
            <div class="dkt-login-logo-title">Diskominfo Kabupaten Lebak</div>
        </div>

        <h1 class="dkt-login-heading">Selamat Datang!</h1>
        <p class="dkt-login-sub">Masuk ke akun Helpdesk TIK Anda</p>

        <!-- ALERTS -->
        <div class="dkt-login-alerts">
            <?php hesk3_show_messages($serviceMessages); ?>
            <?php if (!$submittedForgotPasswordForm) { hesk3_show_messages($messages); } ?>
        </div>

        <!-- LOGIN FORM -->
        <form action="login.php" method="post" name="form1" id="formNeedValidation" novalidate>

            <div class="dkt-login-field">
                <label for="email">Email / Nama Pengguna</label>
                <input type="text" id="email" name="email" maxlength="255"
                       class="dkt-login-input <?php echo in_array('login_email', $validationFailures) ? 'iserror' : '' ?>"
                       value="<?php echo stripslashes(hesk_input($model['email'])); ?>"
                       placeholder="nama@lebakkab.go.id" required>
                <div class="dkt-login-error-hint">Email tidak boleh kosong</div>
            </div>

            <div class="dkt-login-field">
                <label for="password">Kata Sandi</label>
                <input type="password" id="password" name="password"
                       class="dkt-login-input"
                       placeholder="Masukkan kata sandi" required>
                <div class="dkt-login-error-hint">Kata sandi tidak boleh kosong</div>
            </div>

            <div class="dkt-login-row">
                <label class="dkt-login-remember">
                    <input type="checkbox" name="remember_user" value="JUSTUSER" <?php echo $selectSaveEmail ? 'checked' : ''; ?>>
                    Ingat saya
                </label>
                <?php if ($displayForgotPasswordLink): ?>
                <a href="login.php?forgot=1#modal-contents" data-modal="#forgot-modal" class="dkt-login-forgot">
                    Lupa kata sandi?
                </a>
                <?php endif; ?>
            </div>

            <input type="hidden" name="a" value="login">
            <input type="hidden" name="goto" value="<?php echo hesk_htmlspecialchars($redirectUrl); ?>">

            <button type="submit" class="dkt-login-btn" id="recaptcha-submit">
                Masuk
            </button>

            <?php if ($hesk_settings['customer_accounts_customer_self_register']): ?>
            <div class="dkt-login-divider">atau</div>
            <div class="dkt-login-register-row">
                Belum punya akun? <a href="register.php">Daftar sekarang</a>
            </div>
            <?php endif; ?>

        </form>

        <a href="index.php" class="dkt-login-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
            Kembali ke Beranda
        </a>

    </div>
</div>

<!-- FORGOT PASSWORD MODAL (hidden) -->
<div id="forgot-modal" role="dialog" aria-modal="true" aria-label="<?php echo $hesklang['reset_your_password']; ?>" class="<?php echo !$displayForgotPasswordModal ? 'modal' : ''; ?>">
    <div id="modal-contents" class="<?php echo !$displayForgotPasswordModal ? '' : 'notification orange'; ?>" style="padding-bottom:15px">
        <?php if ($submittedForgotPasswordForm) { hesk3_show_messages($messages); } ?>
        <b><?php echo $hesklang['reset_your_password']; ?></b><br><br>
        <?php echo $hesklang['reset_password_instructions']; ?>
        <form action="login.php" method="post" name="form2" id="form2" class="form">
            <div class="form-group">
                <label class="label screen-reader-text skiplink" for="forgot-email"><?php echo $hesklang['email']; ?></label>
                <input id="forgot-email" type="email" class="form-control" name="reset-email" value="<?php echo $model['email']; ?>">
            </div>
            <input type="hidden" name="a" value="forgot_password">
            <input type="hidden" id="js" name="forgot" value="<?php echo (hesk_GET('forgot') ? '1' : '0'); ?>">
            <button id="forgot-password-submit" type="submit" class="btn btn-full"><?php echo $hesklang['passs']; ?></button>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    $('a[data-modal]').on('click', function() {
        $($(this).data('modal')).modal();
        return false;
    });
    <?php if ($submittedForgotPasswordForm) { ?>
    $('#forgot-modal').modal();
    $('#forgot-email').select();
    <?php } ?>
});
function recaptcha_submitForm() {
    document.getElementById("formNeedValidation").submit();
}
function recaptcha_submitForgotPasswordForm() {
    document.getElementById("form2").submit();
}
</script>

<?php
require_once(TEMPLATE_PATH . 'customer/inc/footer.inc.php');
?>
