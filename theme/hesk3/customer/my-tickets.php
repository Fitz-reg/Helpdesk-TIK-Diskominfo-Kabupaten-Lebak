<?php
global $hesk_settings, $hesklang;

/**
 * @var array $customerUserContext
 * @var array $tickets
 * @var array $ticketCounts
 * @var array $admins
 * @var string $searchCriteria
 * @var string $searchType
 * @var string $status
 * @var array $ordering
 * @var array $paging
 */

if (!defined('IN_SCRIPT')) { die(); }
define('EXTRA_PAGE_CLASSES','page-my-tickets');
define('ALERTS',1);
define('MY_TICKETS_SEARCH',1);
define('PAGER',1);
define('RENDER_COMMON_ELEMENTS',1);
define('OUTPUT_SEARCH_JAVASCRIPT',1);

$totalCount = $ticketCounts['open'] + $ticketCounts['closed'];
$totalNumberOfPages = intval($totalCount / $paging['pageSize']);
if ($totalCount % $paging['pageSize'] !== 0) {
    $totalNumberOfPages++;
}

foreach ($hesk_settings['customer_ticket_list'] as $id => $field) {
    if ( ! array_key_exists($field, $hesk_settings['possible_customer_ticket_list']) || ($field === 'id' && ! $hesk_settings['sequential'])) {
        unset($hesk_settings['customer_ticket_list'][$id]);
    }
}

$link_sequential_id = in_array('id', $hesk_settings['customer_ticket_list']) &&
    ! array_intersect(array('trackid', 'subject'), $hesk_settings['customer_ticket_list']);

$show_view_ticket_column = ! array_intersect(array('id', 'trackid', 'subject'), $hesk_settings['customer_ticket_list']);

global $BREADCRUMBS;
$BREADCRUMBS = array(
    array('url' => $hesk_settings['site_url'], 'title' => $hesk_settings['site_title']),
    array('url' => $hesk_settings['hesk_url'], 'title' => $hesk_settings['hesk_title']),
    array('title' => 'Tiket Saya')
);

require_once(TEMPLATE_PATH . 'customer/inc/header.inc.php');
?>

<style>
/* === DKT MY TICKETS REDESIGN === */
.dkt-tix-hero {
    background: linear-gradient(135deg, var(--dkt-navy-dark) 0%, var(--dkt-navy) 60%, var(--dkt-navy-light) 100%) !important;
    padding: 45px 24px 85px 24px !important;
    color: #ffffff !important;
    border-bottom: 3px solid var(--dkt-blue-primary) !important;
}

.dkt-tix-hero-inner {
    max-width: 1140px !important;
    margin: 0 auto !important;
    display: flex !important;
    justify-content: space-between !important;
    align-items: flex-end !important;
    flex-wrap: wrap !important;
    gap: 20px !important;
}

.dkt-tix-hero-title {
    font-size: 2.1rem !important;
    font-weight: 800 !important;
    color: #ffffff !important;
    margin: 0 0 6px 0 !important;
}

.dkt-tix-hero-sub {
    font-size: 0.95rem !important;
    color: rgba(255, 255, 255, 0.85) !important;
    margin: 0 !important;
}

.dkt-tix-stats {
    display: flex !important;
    gap: 12px !important;
}

.dkt-tix-stat-pill {
    background: rgba(255, 255, 255, 0.12) !important;
    border: 1px solid rgba(255, 255, 255, 0.22) !important;
    padding: 8px 18px !important;
    border-radius: 30px !important;
    font-size: 0.86rem !important;
    font-weight: 600 !important;
    color: #ffffff !important;
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
}

.dkt-tix-stat-pill .num {
    background: #0284c7 !important;
    color: #ffffff !important;
    padding: 2px 9px !important;
    border-radius: 20px !important;
    font-size: 0.8rem !important;
}

.dkt-tix-wrapper {
    max-width: 1140px !important;
    margin: -55px auto 60px auto !important;
    padding: 0 24px !important;
    position: relative !important;
    z-index: 10 !important;
    box-sizing: border-box !important;
}

/* Filter Card */
.dkt-tix-filter-card {
    background: #ffffff !important;
    border-radius: 16px !important;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08) !important;
    padding: 20px 24px !important;
    border: 1px solid #e2e8f0 !important;
    margin-bottom: 24px !important;
}

.dkt-tix-filter-form {
    display: flex !important;
    gap: 12px !important;
    align-items: center !important;
    flex-wrap: wrap !important;
}

.dkt-tix-search-input-wrap {
    flex: 1 !important;
    min-width: 240px !important;
    position: relative !important;
}

.dkt-tix-search-input-wrap svg {
    position: absolute !important;
    left: 14px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    stroke: #94a3b8 !important;
}

.dkt-tix-search-input {
    width: 100% !important;
    height: 44px !important;
    padding: 0 16px 0 42px !important;
    border: 1.5px solid #e2e8f0 !important;
    border-radius: 10px !important;
    font-size: 0.92rem !important;
    color: #0f172a !important;
    background: #f8fafc !important;
    outline: none !important;
    box-sizing: border-box !important;
    transition: border-color 0.2s !important;
}

.dkt-tix-search-input:focus {
    border-color: #0284c7 !important;
    background: #ffffff !important;
}

.dkt-tix-select {
    height: 44px !important;
    padding: 0 14px !important;
    border: 1.5px solid #e2e8f0 !important;
    border-radius: 10px !important;
    font-size: 0.9rem !important;
    color: #0f172a !important;
    background: #f8fafc !important;
    outline: none !important;
    cursor: pointer !important;
}

.dkt-tix-btn {
    height: 44px !important;
    padding: 0 24px !important;
    background: #0f172a !important;
    color: #ffffff !important;
    border: none !important;
    border-radius: 10px !important;
    font-size: 0.92rem !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 8px !important;
    transition: background 0.2s !important;
}

.dkt-tix-btn:hover {
    background: #1e293b !important;
}

/* Table Design */
.dkt-tix-table-card {
    background: #ffffff !important;
    border-radius: 16px !important;
    border: 1px solid #e2e8f0 !important;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08) !important;
    overflow: hidden !important;
}

.dkt-tix-table {
    width: 100% !important;
    border-collapse: collapse !important;
    text-align: left !important;
}

.dkt-tix-table th {
    background: #0f172a !important;
    color: #ffffff !important;
    font-size: 0.82rem !important;
    font-weight: 700 !important;
    letter-spacing: 0.04em !important;
    text-transform: uppercase !important;
    padding: 16px 20px !important;
    border: none !important;
}

.dkt-tix-table th a {
    color: #ffffff !important;
    text-decoration: none !important;
}

.dkt-tix-table td {
    padding: 16px 20px !important;
    font-size: 0.92rem !important;
    color: #334155 !important;
    border-bottom: 1px solid #f1f5f9 !important;
    vertical-align: middle !important;
}

.dkt-tix-table tr:last-child td {
    border-bottom: none !important;
}

.dkt-tix-table tr:hover td {
    background: #f8fafc !important;
}

/* Track ID & Subject links */
.dkt-track-link {
    font-weight: 700 !important;
    color: #0284c7 !important;
    text-decoration: none !important;
    font-family: monospace, inherit !important;
    letter-spacing: 0.03em !important;
}

.dkt-track-link:hover {
    color: #0369a1 !important;
    text-decoration: underline !important;
}

.dkt-subject-link {
    font-weight: 600 !important;
    color: #0f172a !important;
    text-decoration: none !important;
}

.dkt-subject-link:hover {
    color: #0284c7 !important;
}

/* Status Badges */
.dkt-badge-status {
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    padding: 5px 12px !important;
    border-radius: 20px !important;
    font-size: 0.8rem !important;
    font-weight: 700 !important;
    text-transform: capitalize !important;
}

.dkt-badge-status.replied { background: #e0f2fe !important; color: #0284c7 !important; }
.dkt-badge-status.new,
.dkt-badge-status.open { background: #dcfce7 !important; color: #15803d !important; }
.dkt-badge-status.waiting { background: #fef3c7 !important; color: #b45309 !important; }
.dkt-badge-status.resolved,
.dkt-badge-status.closed { background: #f1f5f9 !important; color: #475569 !important; }

/* Priority Badges */
.dkt-badge-priority {
    display: inline-flex !important;
    align-items: center !important;
    padding: 4px 10px !important;
    border-radius: 6px !important;
    font-size: 0.78rem !important;
    font-weight: 600 !important;
}

.dkt-badge-priority.critical { background: #fee2e2 !important; color: #dc2626 !important; }
.dkt-badge-priority.high { background: #ffedd5 !important; color: #c2410c !important; }
.dkt-badge-priority.medium { background: #dcfce7 !important; color: #15803d !important; }
.dkt-badge-priority.low { background: #e0f2fe !important; color: #0369a1 !important; }

/* Pager */
.dkt-pager-wrap {
    padding: 18px 24px !important;
    background: #fafafa !important;
    border-top: 1px solid #e2e8f0 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    font-size: 0.88rem !important;
    color: #64748b !important;
}
</style>

<!-- HERO HEADER SECTION -->
<section class="dkt-tix-hero">
    <div class="dkt-tix-hero-inner">
        <div>
            <div style="font-size: 0.88rem; color: rgba(255,255,255,0.8); margin-bottom: 8px;">
                <a href="index.php" style="color: rgba(255,255,255,0.9); text-decoration: none;">Beranda</a>
                <span>/</span>
                <span>Tiket Saya</span>
            </div>
            <h1 class="dkt-tix-hero-title">Tiket Permohonan Saya</h1>
            <p class="dkt-tix-hero-sub">Daftar lengkap tiket bantuan & permohonan TIK yang Anda ajukan</p>
        </div>
        <div class="dkt-tix-stats">
            <div class="dkt-tix-stat-pill">
                <span>Total Tiket:</span>
                <span class="num"><?php echo $totalCount; ?></span>
            </div>
            <div class="dkt-tix-stat-pill">
                <span>Proses (Open):</span>
                <span class="num" style="background: #22c55e;"><?php echo $ticketCounts['open']; ?></span>
            </div>
        </div>
    </div>
</section>

<!-- MAIN CONTENT WRAPPER -->
<div class="dkt-tix-wrapper">

    <!-- SEARCH & FILTER BAR -->
    <div class="dkt-tix-filter-card">
        <form action="my_tickets.php" method="get" class="dkt-tix-filter-form">
            <div class="dkt-tix-search-input-wrap">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" name="search" class="dkt-tix-search-input" value="<?php echo htmlspecialchars($searchCriteria); ?>" placeholder="Cari nomor tiket atau kata kunci permohonan...">
            </div>
            <select name="search-by" class="dkt-tix-select">
                <option value="subject" <?php echo $searchType === 'subject' ? 'selected' : ''; ?>>Cari Subjek</option>
                <option value="trackid" <?php echo $searchType === 'trackid' ? 'selected' : ''; ?>>Cari Tracking ID</option>
                <option value="message" <?php echo $searchType === 'message' ? 'selected' : ''; ?>>Cari Isi Pesan</option>
            </select>
            <button type="submit" class="dkt-tix-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                Cari Tiket
            </button>
        </form>
    </div>

    <!-- TABLE CARD -->
    <div class="dkt-tix-table-card">
        <table class="dkt-tix-table">
            <thead>
                <tr>
                    <?php if (in_array('id', $hesk_settings['customer_ticket_list']) && $hesk_settings['sequential']): ?>
                    <th>ID</th>
                    <?php endif; ?>
                    <?php if (in_array('trackid', $hesk_settings['customer_ticket_list'])): ?>
                    <th>Tracking ID</th>
                    <?php endif; ?>
                    <?php if (in_array('dt', $hesk_settings['customer_ticket_list']) || in_array('lastchange', $hesk_settings['customer_ticket_list'])): ?>
                    <th>Tanggal Update</th>
                    <?php endif; ?>
                    <?php if (in_array('category', $hesk_settings['customer_ticket_list'])): ?>
                    <th>Kategori</th>
                    <?php endif; ?>
                    <?php if (in_array('subject', $hesk_settings['customer_ticket_list'])): ?>
                    <th>Subjek Permohonan</th>
                    <?php endif; ?>
                    <th>Status</th>
                    <th>Prioritas</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($tickets) === 0): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px 20px; color: #64748b;">
                        Tidak ada tiket yang ditemukan.
                    </td>
                </tr>
                <?php endif; ?>

                <?php foreach ($tickets as $ticket): ?>
                <tr>
                    <?php if (in_array('id', $hesk_settings['customer_ticket_list']) && $hesk_settings['sequential']): ?>
                    <td>#<?php echo intval($ticket['id']); ?></td>
                    <?php endif; ?>

                    <?php if (in_array('trackid', $hesk_settings['customer_ticket_list'])): ?>
                    <td>
                        <a href="ticket.php?track=<?php echo rawurlencode($ticket['trackid']); ?>" class="dkt-track-link">
                            <?php echo htmlspecialchars($ticket['trackid']); ?>
                        </a>
                    </td>
                    <?php endif; ?>

                    <?php if (in_array('dt', $hesk_settings['customer_ticket_list']) || in_array('lastchange', $hesk_settings['customer_ticket_list'])): ?>
                    <td style="color: #64748b; font-size: 0.88rem;">
                        <?php echo htmlspecialchars($ticket['lastchange'] ?? $ticket['dt']); ?>
                    </td>
                    <?php endif; ?>

                    <?php if (in_array('category', $hesk_settings['customer_ticket_list'])): ?>
                    <td>
                        <?php 
                        $cat_name = isset($hesk_settings['categories'][$ticket['category']]) ? $hesk_settings['categories'][$ticket['category']] : 'Layanan TIK';
                        echo htmlspecialchars($cat_name);
                        ?>
                    </td>
                    <?php endif; ?>

                    <?php if (in_array('subject', $hesk_settings['customer_ticket_list'])): ?>
                    <td>
                        <a href="ticket.php?track=<?php echo rawurlencode($ticket['trackid']); ?>" class="dkt-subject-link">
                            <?php echo htmlspecialchars($ticket['subject']); ?>
                        </a>
                    </td>
                    <?php endif; ?>

                    <!-- STATUS BADGE -->
                    <td>
                        <?php 
                        $status_str = strtolower(strip_tags($ticket['status']));
                        $status_cls = 'open';
                        if (str_contains($status_str, 'replied') || str_contains($status_str, 'dibalas')) { $status_cls = 'replied'; }
                        elseif (str_contains($status_str, 'wait') || str_contains($status_str, 'menunggu')) { $status_cls = 'waiting'; }
                        elseif (str_contains($status_str, 'resolv') || str_contains($status_str, 'selesai') || str_contains($status_str, 'closed')) { $status_cls = 'resolved'; }
                        ?>
                        <span class="dkt-badge-status <?php echo $status_cls; ?>">
                            <span style="width:6px; height:6px; border-radius:50%; background:currentColor;"></span>
                            <?php echo htmlspecialchars(strip_tags($ticket['status'])); ?>
                        </span>
                    </td>

                    <!-- PRIORITY BADGE -->
                    <td>
                        <?php 
                        $p_name = $hesk_settings['priorities'][$ticket['priority']]['name'] ?? 'Medium';
                        $p_cls = strtolower($p_name);
                        ?>
                        <span class="dkt-badge-priority <?php echo $p_cls; ?>">
                            <?php echo htmlspecialchars($p_name); ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- PAGER -->
        <div class="dkt-pager-wrap">
            <div>
                Menampilkan <b><?php echo count($tickets); ?></b> dari <b><?php echo $totalCount; ?></b> tiket
            </div>
            <div>
                <?php output_pager($totalNumberOfPages, $paging['pageNumber'], "my_tickets.php?search-by={$searchType}&search={$searchCriteria}"); ?>
            </div>
        </div>
    </div>

</div>

<?php
require_once(TEMPLATE_PATH . 'customer/inc/footer.inc.php');
?>
