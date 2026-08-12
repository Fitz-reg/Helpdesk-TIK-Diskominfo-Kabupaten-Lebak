<?php
global $hesk_settings, $hesklang;
/**
 * @var array $validationFailures
 * @var array $messages
 * @var array $model
 */

if (!defined('IN_SCRIPT')) { die(); }
define('EXTRA_PAGE_CLASSES','page-register');
define('ALERTS',1);
define('RENDER_COMMON_ELEMENTS',1);

global $BREADCRUMBS;
$BREADCRUMBS = array(
    array('url' => $hesk_settings['site_url'], 'title' => $hesk_settings['site_title']),
    array('url' => $hesk_settings['hesk_url'], 'title' => $hesk_settings['hesk_title']),
    array('title' => 'Daftar Akun')
);

require_once(TEMPLATE_PATH . 'customer/inc/header.inc.php');
?>

<style>
/* === DKT REGISTER PAGE === */
.dkt-reg-page {
    min-height: 100vh;
    background: #f0f4f8;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 16px;
}

.dkt-reg-card {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 4px 40px rgba(0,0,0,0.10);
    width: 100%;
    max-width: 460px;
    padding: 48px 44px 40px;
}

.dkt-reg-logo {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 24px;
}

.dkt-reg-logo img {
    height: 56px;
    width: auto;
    object-fit: contain;
    margin-bottom: 10px;
}

.dkt-reg-logo-title {
    font-size: 0.78rem;
    font-weight: 600;
    color: #64748b;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    text-align: center;
}

.dkt-reg-heading {
    font-size: 1.65rem;
    font-weight: 700;
    color: #0f172a;
    text-align: center;
    margin-bottom: 6px;
    letter-spacing: -0.02em;
}

.dkt-reg-sub {
    font-size: 0.92rem;
    color: #64748b;
    text-align: center;
    margin-bottom: 28px;
}

.dkt-reg-alerts {
    margin-bottom: 18px;
}

.dkt-reg-field {
    margin-bottom: 18px;
}

.dkt-reg-field label {
    display: block;
    font-size: 0.86rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.dkt-reg-input {
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

.dkt-reg-input:focus {
    border-color: #0284c7;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(2,132,199,0.12);
}

.dkt-reg-input.isError {
    border-color: #ef4444;
    background: #fef2f2;
}

.dkt-reg-input::placeholder {
    color: #94a3b8;
}

.dkt-reg-btn {
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
    margin-top: 10px;
    transition: background 0.2s, transform 0.1s, box-shadow 0.2s;
    font-family: inherit;
}

.dkt-reg-btn:hover {
    background: #1e293b;
    box-shadow: 0 4px 20px rgba(15,23,42,0.25);
    transform: translateY(-1px);
}

.dkt-reg-btn:active {
    transform: translateY(0);
}

.dkt-reg-divider {
    display: flex;
    align-items: center;
    gap: 14px;
    margin: 24px 0;
    color: #94a3b8;
    font-size: 0.84rem;
}

.dkt-reg-divider::before,
.dkt-reg-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e2e8f0;
}

.dkt-reg-login-row {
    text-align: center;
    font-size: 0.9rem;
    color: #64748b;
    margin-top: 4px;
}

.dkt-reg-login-row a {
    color: #0284c7;
    font-weight: 700;
    text-decoration: none;
    transition: color 0.2s;
}

.dkt-reg-login-row a:hover {
    color: #0369a1;
    text-decoration: underline;
}

.dkt-reg-back {
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

.dkt-reg-back:hover { color: #0284c7; }
</style>

<div class="dkt-reg-page">
    <div class="dkt-reg-card">

        <!-- LOGO -->
        <div class="dkt-reg-logo">
            <img src="<?php echo HESK_PATH; ?>img/DiskominfoLogo.png" alt="Diskominfo Kabupaten Lebak">
            <div class="dkt-reg-logo-title">Diskominfo Kabupaten Lebak</div>
        </div>

        <h1 class="dkt-reg-heading">Daftar Akun Baru</h1>
        <p class="dkt-reg-sub">Buat akun untuk mengajukan permohonan TIK</p>

        <!-- ALERTS -->
        <div class="dkt-reg-alerts">
            <?php hesk3_show_messages($serviceMessages); ?>
            <?php hesk3_show_messages($messages); ?>
        </div>

        <!-- FORM -->
        <form action="register.php" method="post" name="form1" id="formNeedValidation" novalidate>

            <div class="dkt-reg-field">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" maxlength="255"
                       class="dkt-reg-input <?php if (in_array('name', $validationFailures)) {echo 'isError';} ?>"
                       value="<?php echo isset($model['name']) ? stripslashes(hesk_input($model['name'])) : ''; ?>"
                       placeholder="Masukkan nama lengkap Anda" required>
            </div>

            <div class="dkt-reg-field">
                <label for="email">Alamat Email</label>
                <input type="email" id="email" name="email" maxlength="255"
                       class="dkt-reg-input <?php if (in_array('email', $validationFailures)) {echo 'isError';} ?>"
                       value="<?php echo isset($model['email']) ? stripslashes(hesk_input($model['email'])) : ''; ?>"
                       placeholder="nama@lebakkab.go.id" required>
            </div>

            <div class="dkt-reg-field">
                <label for="password">Kata Sandi</label>
                <input type="password" id="password" name="password"
                       class="dkt-reg-input <?php if (in_array('password', $validationFailures)) {echo 'isError';} ?>"
                       placeholder="Minimal 6 karakter" required>
            </div>

            <div class="dkt-reg-field">
                <label for="confirm-password">Konfirmasi Kata Sandi</label>
                <input type="password" id="confirm-password" name="confirm-password"
                       class="dkt-reg-input <?php if (in_array('password', $validationFailures)) {echo 'isError';} ?>"
                       placeholder="Ulangi kata sandi" required>
            </div>

            <?php
            if ($hesk_settings['question_use'] || ($hesk_settings['secimg_use'] && $hesk_settings['recaptcha_use'] !== 1)):
            ?>
                <div class="captcha-block" style="margin-top: 15px;">
                    <?php if ($hesk_settings['question_use']): ?>
                        <div class="dkt-reg-field">
                            <label for="question"><?php echo $hesk_settings['question_ask']; ?></label>
                            <input type="text" class="dkt-reg-input <?php echo in_array('question',$validationFailures) ? 'isError' : ''; ?>"
                                   id="question" name="question" size="20">
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <button type="submit" class="dkt-reg-btn" id="recaptcha-submit">
                Daftar Sekarang
            </button>

            <div class="dkt-reg-divider">atau</div>

            <div class="dkt-reg-login-row">
                Sudah punya akun? <a href="login.php">Masuk ke akun</a>
            </div>

        </form>

        <a href="index.php" class="dkt-reg-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
            Kembali ke Beranda
        </a>

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
function recaptcha_submitForm() {
    document.getElementById("formNeedValidation").submit();
}
</script>

<?php
require_once(TEMPLATE_PATH . 'customer/inc/footer.inc.php');
?>
