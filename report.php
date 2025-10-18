<?php
require_once('../../config.php');
require_login();
require_capability('moodle/site:config', context_system::instance());

$PAGE->set_url(new moodle_url('/local/oauth2_autolink/report.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title('OAuth2 Link');
$PAGE->set_heading('Logs OAuth2 Link');

echo $OUTPUT->header();
echo $OUTPUT->heading('Logs do plugin OAuth2 Auto Link');

$logfile = __DIR__ . '/logs/oauth2_autolink.log';
$perpage = 100;

// filtros
$fromdate = optional_param('fromdate', '', PARAM_RAW);
$todate = optional_param('todate', '', PARAM_RAW);

// Formulário de filtro
echo html_writer::start_tag('form', ['method' => 'get', 'class' => 'mform']);
echo html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'sesskey', 'value' => sesskey()]);

echo html_writer::start_div('filters', ['style' => 'margin-bottom:10px;']);
echo 'Data inicial: ';
echo html_writer::empty_tag('input', ['type' => 'date', 'name' => 'fromdate', 'value' => $fromdate]);
echo '&nbsp;&nbsp;Data final: ';
echo html_writer::empty_tag('input', ['type' => 'date', 'name' => 'todate', 'value' => $todate]);
echo '&nbsp;&nbsp;';
echo html_writer::empty_tag('input', ['type' => 'submit', 'value' => 'Filtrar', 'class' => 'btn btn-primary']);
echo html_writer::end_div();
echo html_writer::end_tag('form');

if (!file_exists($logfile)) {
    echo $OUTPUT->notification('Nenhum log encontrado.', 'info');
    echo $OUTPUT->footer();
    exit;
}

$lines = file($logfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$lines = array_reverse($lines); // Mais recentes primeiro

// Filtro data
if (!empty($fromdate) || !empty($todate)) {
    $fromts = !empty($fromdate) ? strtotime($fromdate . ' 00:00:00') : 0;
    $tots = !empty($todate) ? strtotime($todate . ' 23:59:59') : PHP_INT_MAX;
    $lines = array_filter($lines, function ($line) use ($fromts, $tots) {
        if (preg_match('/\[(\d{4}-\d{2}-\d{2}) (\d{2}:\d{2}:\d{2})\]/', $line, $m)) {
            $timestamp = strtotime($m[1] . ' ' . $m[2]);
            return $timestamp >= $fromts && $timestamp <= $tots;
        }
        return false;
    });
}

// paginacao
$total = count($lines);
$page = optional_param('page', 0, PARAM_INT);
$start = $page * $perpage;
$lines = array_slice($lines, $start, $perpage);

if ($total > $perpage) {
    echo $OUTPUT->paging_bar($total, $page, $perpage, $PAGE->url);
}

// Logs
if (empty($lines)) {
    echo $OUTPUT->notification('Nenhum log encontrado para o filtro selecionado.', 'info');
} else {
    echo html_writer::start_tag('pre', [
        'style' => 'background:#111;color:#0f0;padding:10px;border-radius:8px;max-height:600px;overflow:auto;'
    ]);
    foreach ($lines as $line) {
        echo s($line) . "\n";
    }
    echo html_writer::end_tag('pre');
}

if ($total > $perpage) {
    echo $OUTPUT->paging_bar($total, $page, $perpage, $PAGE->url);
}

echo '<style>
    /* Aplica apenas aos campos de data do Moodle */
    input[type="date"],
    .form-control[type="date"],
    .form-inline .form-control[type="date"] {
        border-radius: 8px !important; /* bordas mais suaves */
        padding: 6px 10px !important;
        border: 1px solid #ccc;
        transition: all 0.2s ease-in-out;
    }

    /* Efeito de foco (opcional) */
    input[type="date"]:focus {
        border-color: #2271b1; /* cor padrão do Moodle */
        box-shadow: 0 0 3px rgba(34, 113, 177, 0.5);
        outline: none;
    }
</style>';

echo $OUTPUT->footer();
