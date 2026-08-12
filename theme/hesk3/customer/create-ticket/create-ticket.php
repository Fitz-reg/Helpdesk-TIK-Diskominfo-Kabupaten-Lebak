<?php
global $hesk_settings, $hesklang;
/**
 * @var string $categoryName
 * @var int $categoryId
 * @var array $visibleCustomFieldsBeforeMessage
 * @var array $visibleCustomFieldsAfterMessage
 * @var array $customFieldsBeforeMessage
 * @var array $customFieldsAfterMessage
 * @var bool $customerLoggedIn - `true` if a customer is logged in, `false` otherwise
 * @var array $customerUserContext - User info for a customer if logged in.  `null` if a customer is not logged in.
 */

// This guard is used to ensure that users can't hit this outside of actual HESK code
if (!defined('IN_SCRIPT')) {
    die();
}
define('EXTRA_PAGE_CLASSES','page-create-ticket');

define('ALERTS',1);
define('CUSTOM_FIELDS',1);
define('ATTACHMENTS',1);
define('PRIORITIES',1);

define('LOAD_CSS_DROPZONE',1);
define('RENDER_COMMON_ELEMENTS',1);

define('TMP_TITLE',1); // TODO absolutelyRework

define('LOAD_JS_DATEPICKER',1);
define('LOAD_JS_DROPZONE',1);

define('FOOTER_DONT_CLOSE_HTML',1);

global $BREADCRUMBS;
$BREADCRUMBS = array(
    array('url' => $hesk_settings['site_url'], 'title' => $hesk_settings['site_title']),
    array('url' => "index.php", 'title' => $hesk_settings['hesk_title']),
    array('url' => "index.php?a=add", 'title' => $hesklang['submit_ticket']),
    array('title' => $categoryName)
);

/* Print header */
require_once(TEMPLATE_PATH . 'customer/inc/header.inc.php');
?>

<!-- ==================== TICKET HERO BANNER ==================== -->
<section class="dkt-ticket-hero">
    <div class="dkt-ticket-hero-container">
        <div class="dkt-category-badge">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            Katalog Layanan TIK Diskominfo Lebak
        </div>
        <h1 class="dkt-ticket-hero-title"><?php echo hesk_htmlspecialchars($categoryName); ?></h1>
        <p class="dkt-ticket-hero-sub">Isi formulir pengajuan berikut dengan data yang valid untuk mempercepat penanganan oleh tim teknis Diskominfo Kabupaten Lebak.</p>
    </div>
</section>

<div class="main__content" style="padding-top: 40px; padding-bottom: 60px;">
    <div class="contr">
        <div style="margin-bottom: 24px;">
            <?php hesk3_show_messages($serviceMessages); ?>
            <?php hesk3_show_messages($messages); ?>
        </div>

        <div class="dkt-form-card" style="max-width: 880px; margin: 0 auto;">
                <form class="form form-submit-ticket ticket-create <?php echo count($_SESSION['iserror']) ? 'invalid' : ''; ?>" method="post" action="submit_ticket.php?submit=1" aria-label="<?php echo $hesklang['create_a_ticket']; ?>" name="form1" id="form1" enctype="multipart/form-data" onsubmit="<?php if ($hesk_settings['submitting_wait']): ?>hesk_showLoadingMessage('recaptcha-submit');<?php endif; ?>" <?php echo $hesk_settings['disable_autofill_customer'] ? 'autocomplete="off" aria-autocomplete="none"' : ''; ?>>
                    
                    <!-- SECTION 1: DATA PEMOHON -->
                    <div class="dkt-section-divider">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <span>1. Data Kontak Pemohon / OPD</span>
                    </div>

                    <?php if (!$customerLoggedIn) { ?>
                    <div class="form-group">
                        <label class="label required" for="name">Nama Lengkap Pemohon / Pegawai:</label>
                        <?php
                        $input_css = 'form-control';
                        if (in_array('name', $_SESSION['iserror'])) {
                            $input_css .= ' isError';
                        }
                        ?>
                        <input type="text" id="name" name="name"
                               class="<?php echo $input_css; ?>"
                               maxlength="50"
                               placeholder="Nama lengkap pemohon sesuai SK / ID"
                               value="<?php
                               if (isset($_SESSION['c_name'])) {
                                   echo stripslashes(hesk_input($_SESSION['c_name']));
                               } ?>"
                               <?php echo $hesk_settings['disable_autofill_customer'] ? 'autocomplete="off" aria-autocomplete="none"' : ''; ?>
                               required>
                    </div>
                    <div class="form-group">
                        <label class="label <?php if ($hesk_settings['require_email']) { ?>required<?php } ?>" for="email">Email Resmi / Email Pemohon:</label>
                        <?php
                        $input_css = 'form-control';
                        if (in_array('email', $_SESSION['iserror'])) {
                            $input_css .= ' isError';
                        }
                        if (in_array('email', $_SESSION['isnotice'])) {
                            $input_css .= ' isNotice';
                        }
                        ?>
                        <input type="email"
                               class="<?php echo $input_css; ?>"
                               name="email" id="email" maxlength="1000"
                               placeholder="contoh: nama@lebakkab.go.id atau email aktif"
                               value="<?php
                               if (isset($_SESSION['c_email'])) {
                                   echo stripslashes(hesk_input($_SESSION['c_email']));
                               } ?>"
                               <?php echo $hesk_settings['disable_autofill_customer'] ? 'autocomplete="off" aria-autocomplete="none"' : ''; ?>
                               <?php if($hesk_settings['detect_typos']) { echo ' onblur="HESK_FUNCTIONS.suggestEmail(\'email\', \'email_suggestions\', 0)"'; } ?>
                               <?php if ($hesk_settings['require_email']) { ?>required<?php } ?>>
                        <div id="email_suggestions" class="email-suggestion"></div>
                    </div>
                    <?php
                    if ($hesk_settings['confirm_email']):
                        ?>
                        <?php
                        $input_css = 'form-control';
                        if (in_array('email2', $_SESSION['iserror'])) {
                            $input_css .= ' isError';
                        }
                        if (in_array('email2', $_SESSION['isnotice'])) {
                            $input_css .= ' isNotice';
                        }
                        if ($customerLoggedIn) {
                            $input_css .= ' as-text';
                        }
                        ?>
                        <div class="form-group">
                            <label class="label <?php if ($hesk_settings['require_email']) { ?>required<?php } ?>" for="email2">Konfirmasi Email Pemohon:</label>
                            <input type="<?php echo $hesk_settings['multi_eml'] ? 'text' : 'email'; ?>"
                                   class="<?php echo $input_css; ?>"
                                   name="email2" id="email2" maxlength="1000"
                                   <?php echo $hesk_settings['disable_autofill_customer'] ? 'autocomplete="off" aria-autocomplete="none"' : ''; ?>
                                   <?php if ($customerLoggedIn) { echo 'readonly'; } ?>
                                   value="<?php if (isset($_SESSION['c_email2'])) {echo stripslashes(hesk_input($_SESSION['c_email2']));} ?>"
                                   <?php if ($hesk_settings['require_email']) { ?>required<?php } ?>>
                        </div>
                    <?php endif;
                    }
                    if ($hesk_settings['multi_eml'] && !isset($_SESSION['c_followers'])): ?>
                    <div class="form-group" id="cc-link">
                        <a href="#" onclick="HESK_FUNCTIONS.toggleLayerDisplay('cc-div');HESK_FUNCTIONS.toggleLayerDisplay('cc-link')">
                            + Tembusan Email (CC)
                        </a>
                    </div>
                    <?php endif;
                    if ($hesk_settings['multi_eml']):
                        $display = isset($_SESSION['c_followers']) ? 'block' : 'none';
                    ?>
                    <div class="form-group" id="cc-div" style="display: <?php echo $display; ?>">
                        <label class="label" for="follower_email">Tembusan Email (CC):</label>
                        <?php
                        $input_css = 'form-control';
                        if (in_array('followers', $_SESSION['iserror'])) {
                            $input_css .= ' isError';
                        }
                        if (in_array('followers', $_SESSION['isnotice'])) {
                            $input_css .= ' isNotice';
                        }
                        ?>
                        <input type="text"
                               class="<?php echo $input_css; ?>"
                               <?php echo $hesk_settings['disable_autofill_customer'] ? 'autocomplete="off" aria-autocomplete="none"' : ''; ?>
                               name="follower_email" id="follower_email" maxlength="1000"
                               value="<?php
                               if (isset($_SESSION['c_followers'])) {
                                   echo stripslashes(hesk_input($_SESSION['c_followers']));
                               } ?>" <?php if($hesk_settings['detect_typos']) { echo ' onblur="HESK_FUNCTIONS.suggestEmail(\'follower_email\', \'follower_email_suggestions\', 0)"'; } ?>>
                        <div id="follower_email_suggestions" class="email-suggestion"></div>
                    </div>
                    <?php
                    endif;
                    if ($hesk_settings['cust_urgency']): ?>
                        <section class="param" style="margin-bottom: 20px;">
                            <span class="label required <?php if (in_array('priority',$_SESSION['iserror'])) echo 'isErrorStr'; ?>">Tingkat Urgensi:</span>
                            <div class="dropdown-select center out-close priority select-priority">
                                <select name="priority" aria-label="Tingkat Urgensi">
                                    <?php if ($hesk_settings['select_pri']): ?>
                                        <option value="">Pilih Urgensi</option>
                                    <?php endif; ?>
                                    <?php
                                        echo hesk_get_priority_select('', 0, $_SESSION['c_priority']);
                                    ?>
                                </select>
                            </div>
                        </section>
                    <?php
                    endif;

                    hesk3_output_custom_fields($customFieldsBeforeMessage);
                    ?>

                    <!-- SECTION 2: RINCIAN PERMOHONAN -->
                    <div class="dkt-section-divider">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                        <span>2. Rincian Permohonan / Keterangan Layanan</span>
                    </div>

                    <?php
                    if ($hesk_settings['require_subject'] != -1 || $hesk_settings['require_message'] != -1): ?>
                        <?php if ($hesk_settings['require_subject'] != -1): ?>
                            <div class="form-group">
                                <label class="label <?php if ($hesk_settings['require_subject']) { ?>required<?php } ?>" for="subject">
                                    Judul Pengajuan / Ringkasan Permohonan:
                                </label>
                                <input type="text" id="subject" class="form-control <?php if (in_array('subject',$_SESSION['iserror'])) {echo 'isError';} ?>"
                                       name="subject" maxlength="70"
                                       placeholder="Contoh: Permohonan Akun E-Office / Kendala Koneksi Jaringan FO"
                                       value="<?php if (isset($_SESSION['c_subject'])) {echo stripslashes(hesk_input($_SESSION['c_subject']));} ?>"
                                       <?php echo $hesk_settings['disable_autofill_customer'] ? 'autocomplete="off" aria-autocomplete="none"' : ''; ?>
                                       <?php if ($hesk_settings['require_subject']) { ?>required<?php } ?>>
                            </div>
                            <?php
                        endif;
                        if ($hesk_settings['require_message'] != -1): ?>
                            <div class="form-group">
                                <label class="label <?php if ($hesk_settings['require_message']) { ?>required<?php } ?>" for="message">
                                    Rincian Penjelasan / Keterangan Tambahan:
                                </label>
                                <textarea class="form-control <?php if (in_array('message',$_SESSION['iserror'])) {echo 'isError';} ?>"
                                          id="message" name="message" rows="8" cols="60"
                                          placeholder="Jelaskan secara detail kebutuhan permohonan layanan atau kronologi kendala teknis yang dihadapi..."
                                          <?php if ($hesk_settings['require_message']) { ?>required<?php } ?>><?php if (isset($_SESSION['c_message'])) {echo stripslashes(hesk_input($_SESSION['c_message']));} ?></textarea>
                                <?php if (has_public_kb() && $hesk_settings['kb_recommendanswers'] && ! isset($_REQUEST['do_not_suggest'])): ?>
                                    <div class="kb-suggestions">
                                        <h2>Rekomendasi Panduan Terkait:</h2>
                                        <ul id="kb-suggestion-list" class="type--list">
                                        </ul>
                                        <div id="suggested-article-hidden-inputs" style="display: none">
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php
                        endif;
                    endif;

                    hesk3_output_custom_fields($customFieldsAfterMessage);
                    ?>

                    <!-- SECTION 3: LAMPIRAN BERKAS -->
                    <div class="dkt-section-divider">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path></svg>
                        <span>3. Lampiran Berkas Pendukung (Surat OPD, Foto, SK)</span>
                    </div>

                    <?php
                    if ($hesk_settings['attachments']['use']):
                    ?>
                        <section class="param param--attach" style="margin-bottom: 24px;">
                            <span class="label">Lampirkan File Dokumen / Tangkapan Layar:</span>
                            <div class="attach">
                                <div>
                                    <?php hesk3_output_drag_and_drop_attachment_holder(); ?>
                                </div>
                                <div class="attach-tooltype">
                                    <a class="link" href="file_limits.php" onclick="HESK_FUNCTIONS.openWindow('file_limits.php',250,500);return false;">
                                        Ketentuan Ukuran & Format File
                                    </a>
                                </div>
                            </div>
                        </section>
                        <?php
                    endif;

                    if ($hesk_settings['question_use'] || ($hesk_settings['secimg_use'] && $hesk_settings['recaptcha_use'] !== 1)):
                    ?>
                    <!-- SECTION 4: VERIFIKASI KEAMANAN (CAPTCHA) -->
                    <div class="dkt-section-divider">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        <span>4. Verifikasi Keamanan Captcha</span>
                    </div>

                    <div class="dkt-captcha-card" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 24px; margin-bottom: 28px;">
                        <h3 style="font-size: 1rem; font-weight: 700; color: #0f172a; margin: 0 0 12px 0;">Kode Pengaman (Captcha)</h3>
                        <p style="font-size: 0.88rem; color: #64748b; margin: 0 0 18px 0;">Ketik 5 digit angka yang muncul pada gambar di bawah ini untuk memverifikasi permohonan Anda.</p>

                        <?php if ($hesk_settings['question_use']): ?>
                        <div class="form-group" style="margin-bottom: 16px;">
                            <label class="label required" for="question"><?php echo $hesk_settings['question_ask']; ?></label>
                            <?php
                            $value = isset($_SESSION['c_question']) ? stripslashes(hesk_input($_SESSION['c_question'])) : '';
                            ?>
                            <input type="text" id="question" class="form-control <?php echo in_array('question',$_SESSION['iserror']) ? 'isError' : ''; ?>"
                                   name="question" size="20" value="<?php echo $value; ?>">
                        </div>
                        <?php endif; ?>

                        <?php if ($hesk_settings['secimg_use'] && $hesk_settings['recaptcha_use'] != 1): ?>
                            <div class="form-group" style="margin: 0;">
                                <?php if (isset($_SESSION['img_verified'])): ?>
                                    <div style="color: #15803d; font-weight: 600; font-size: 0.92rem;">
                                        Sesi Anda sudah terverifikasi aman.
                                    </div>
                                <?php elseif ($hesk_settings['recaptcha_use'] == 2): ?>
                                    <div class="g-recaptcha" data-sitekey="<?php echo $hesk_settings['recaptcha_public_key']; ?>"></div>
                                <?php else: ?>
                                    <?php $cls = in_array('mysecnum', $_SESSION['iserror']) ? 'isError' : ''; ?>
                                    <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap; margin-bottom: 14px;">
                                        <img name="secimg" id="secimg" src="print_sec_img.php?<?php echo rand(10000,99999); ?>" width="160" height="46" alt="Kode Captcha" style="border-radius: 8px; border: 1px solid #cbd5e1; vertical-align: middle;">
                                        <button type="button" class="btn-refresh" onclick="document.getElementById('secimg').src='print_sec_img.php?'+ (Math.floor((90000)*Math.random()) + 10000);" style="background: #ffffff; border: 1.5px solid #cbd5e1; border-radius: 8px; width: 44px; height: 44px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; color: #0f172a; transition: border-color 0.2s;" title="Acak Ulang Kode Captcha">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                                        </button>
                                    </div>
                                    <label class="label required" for="mysecnum" style="font-weight: 600; font-size: 0.88rem; color: #334155; margin-bottom: 6px; display: block;">Masukkan Kode Angka Captcha:</label>
                                    <input type="text" id="mysecnum" name="mysecnum" maxlength="5" autocomplete="off" aria-autocomplete="none" class="form-control <?php echo $cls; ?>" placeholder="Ketik 5 digit angka" style="max-width: 220px; height: 44px; font-size: 1rem; font-weight: 700; letter-spacing: 0.15em; text-align: center; border-radius: 8px;" required>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                        <?php
                    endif;

                    if ($hesk_settings['submit_notice']):
                    ?>
                    <div class="alert browser-default" style="margin-bottom: 20px;">
                        <div class="alert__inner">
                            <b class="font-weight-bold">Ketentuan Sebelum Mengirim Tiket:</b>
                            <ul>
                                <li>Pastikan data NIP, Perangkat Daerah (OPD), dan Nomor WhatsApp aktif sudah terisi dengan benar.</li>
                                <li>Lampirkan Surat OPD resmi atau tangkapan layar jika mengajukan permohonan layanan khusus.</li>
                            </ul>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="form-footer" style="margin-top: 30px;">
                        <input type="hidden" name="token" value="<?php hesk_token_echo(); ?>">
                        <input type="hidden" name="category" value="<?php echo $categoryId; ?>">
                        <button type="submit" class="btn btn-full" ripple="ripple" id="recaptcha-submit" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); font-weight: 700; border: none; padding: 14px 28px; font-size: 1rem;">
                            Kirim Tiket Permohonan Layanan TIK
                        </button>
                        <input type="hidden" name="hx" value="3" /><input type="hidden" name="hy" value="">
                    </div>
                    <?php
                    if ($hesk_settings['secimg_use'] && $hesk_settings['recaptcha_use'] == 1 && ! isset($_SESSION['img_verified']))
                    {
                        ?>
                        <div class="g-recaptcha" data-sitekey="<?php echo $hesk_settings['recaptcha_public_key']; ?>" data-bind="recaptcha-submit" data-callback="recaptcha_submitForm"></div>
                        <?php
                    }
                    ?>
                </form>
            </div>
    </div>
</div>
        <div id="loading-overlay" class="loading-overlay">
            <div id="loading-message" class="loading-message">
                <div class="spinner"></div>
                <p><?php echo $hesklang['sending_wait']; ?></p>
            </div>
        </div>
<?php
    function hesk_jsString($str)
    {
        $str  = addslashes($str);
        $str  = str_replace('<br />' , '' , $str);
        $from = array("/\r\n|\n|\r/", '/\<a href="mailto\:([^"]*)"\>([^\<]*)\<\/a\>/i', '/\<a href="([^"]*)" target="_blank"\>([^\<]*)\<\/a\>/i');
        $to   = array("\\r\\n' + \r\n'", "$1", "$1");
        return preg_replace($from,$to,$str);
    } // END hesk_jsString()
?>
<script type="text/javascript">

    document.addEventListener("DOMContentLoaded", function() {

        $('#select_category').selectize();
        hesk_loadNoResultsSelectizePlugin('<?php echo hesk_jsString($hesklang['no_results_found']); ?>');
        <?php

        foreach ($customFieldsBeforeMessage as $customField)
        {
            if ($customField['type'] == 'select')
            {
                if ($customField['value']['is_searchable'] == 1) {
                    echo "$('#{$customField['name']}').addClass('read-write').attr('placeholder', '".$hesklang["search_by_pattern"]."').selectize({
                        delimiter: ',',
                        valueField: 'id',
                        labelField: 'displayName',
                        searchField: ['displayName'],
                        create: false,
                        copyClassesToDropdown: true,
                        plugins: ['no_results'],
                    });";
                } else {
                    echo "$('#{$customField['name']}').selectize();";
                }
            }
        }
        foreach ($customFieldsAfterMessage as $customField)
        {
            if ($customField['type'] == 'select')
            {
                if ($customField['value']['is_searchable'] == 1) {
                    echo "$('#{$customField['name']}').addClass('read-write').attr('placeholder', '".$hesklang["search_by_pattern"]."').selectize({
                        delimiter: ',',
                        valueField: 'id',
                        labelField: 'displayName',
                        searchField: ['displayName'],
                        create: false,
                        copyClassesToDropdown: true,
                        plugins: ['no_results'],
                    });";
                } else {
                    echo "$('#{$customField['name']}').selectize();";
                }
            }
        }
        ?>
    });
</script>

<?php if (has_public_kb() && $hesk_settings['kb_recommendanswers']): ?>
    <script type="text/javascript">
        var noArticlesFoundText = <?php echo json_encode($hesklang['nsfo']); ?>;

        document.addEventListener("DOMContentLoaded", function() {
            HESK_FUNCTIONS.getKbTicketSuggestions($('input[name="subject"]'),
                $('textarea[name="message"]'),
                function(data) {
                    $('.kb-suggestions').show();
                    var $suggestionList = $('#kb-suggestion-list');
                    var $suggestedArticlesHiddenInputsList = $('#suggested-article-hidden-inputs');
                    $suggestionList.html('');
                    $suggestedArticlesHiddenInputsList.html('');
                    var format = '<a href="knowledgebase.php?article={0}" class="suggest-preview" target="_blank">' +
                        '<span class="icon-in-circle" aria-hidden="true">' +
                        '<svg class="icon icon-knowledge">' +
                        '<use xlink:href="./theme/hesk3/customer/img/sprite.svg#icon-knowledge"></use>' +
                        '</svg>' +
                        '</span>' +
                        '<div class="suggest-preview__text">' +
                        '<p class="suggest-preview__title">{1}</p>' +
                        '<p>{2}</p>' +
                        '</div>' +
                        '</a>';
                    var hiddenInputFormat = '<input type="hidden" name="suggested[]" value="{0}">';
                    var results = false;
                    $.each(data, function() {
                        results = true;
                        $suggestionList.append(format.replace('{0}', this.id).replace('{1}', this.subject).replace('{2}', this.contentPreview));
                        $suggestedArticlesHiddenInputsList.append(hiddenInputFormat.replace('{0}', this.hiddenInputValue));
                    });

                    if (!results) {
                        $suggestionList.append('<li class="no-articles-found">' + noArticlesFoundText + '</li>');
                    }
                }
            );
        });
    </script>
<?php endif;

// Any adjustments to datepicker?
if (isset($hesk_settings['datepicker'])):
    ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const myDP = {};
            <?php
            foreach ($hesk_settings['datepicker'] as $selector => $data) {
                echo "
                myDP['{$selector}'] = $('{$selector}').datepicker(".((isset($data['position']) && is_string($data['position'])) ? "{position: '{$data['position']}'}" : "").");
            ";
                if (isset($data['timestamp']) && ($ts = intval($data['timestamp']))) {
                    echo "
                    myDP['{$selector}'].data('datepicker').selectDate(new Date({$ts} * 1000));
                ";
                }
            }
            ?>
        });
    </script>
<?php
endif;
?>
<?php
/* Print Footer */
require_once(TEMPLATE_PATH . 'customer/inc/footer.inc.php');

/*
 * Note: In this case, we have to make sure we load all footer scripts first, as otherwise it breaks some of the custom JS page code
 */
?>
<?php hesk3_output_drag_and_drop_script('c_attachments'); ?>
    </body>
</html>
