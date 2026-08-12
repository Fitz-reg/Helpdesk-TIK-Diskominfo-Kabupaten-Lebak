<?php
// This guard is used to ensure that users can't hit this outside of actual HESK code
if (!defined('IN_SCRIPT')) {
    die();
}

function hesk3_show_messages($messages) {
    $style_to_class = array(
        '0' => 'white',
        '1' => 'green',
        '2' => 'blue', // Info has no CSS class
        '3' => 'orange',
        '4' => 'red'
    );
    $style_to_ada_role = array(
        '0' => 'log',
        '1' => 'status',
        '2' => 'status',
        '3' => 'alert',
        '4' => 'alert'
    );
    foreach ($messages as $message):
        $title = $message['title'];
        if ($title === 'Success' || $title === 'Sukses') {
            $title = 'Pemberitahuan';
        }
    ?>
    <div class="main__content notice-flash" style="position: relative; z-index: 120; margin: 12px 0;">
        <div role="<?php echo $style_to_ada_role[$message['style']]; ?>" class="notification <?php echo $style_to_class[$message['style']]; ?> browser-default" style="display: flex; align-items: center; justify-content: space-between; gap: 14px; border-radius: 12px; padding: 14px 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); margin: 0 auto; max-width: 1040px;">
            <div style="flex: 1;">
                <p style="margin: 0 0 2px 0; font-size: 0.95rem; font-weight: 700;"><b><?php echo htmlspecialchars($title); ?></b></p>
                <div style="font-size: 0.9rem; line-height: 1.4;"><?php echo $message['message']; ?></div>
            </div>
            <button type="button" onclick="this.closest('.notice-flash').style.display='none';" style="background: rgba(0,0,0,0.06); border: none; border-radius: 50%; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; color: inherit; flex-shrink: 0; transition: background 0.2s;" title="Tutup Notifikasi" aria-label="Tutup Notifikasi">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
    </div>
<?php
    endforeach;
}