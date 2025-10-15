<?php
require_once('../../config.php');
require_login();
require_capability('moodle/site:config', context_system::instance()); // Apenas admins

$PAGE->set_url(new moodle_url('/local/oauth2_autolink/report.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title('Logs OAuth2 Link');
$PAGE->set_heading('Logs OAuth2 Link');

echo $OUTPUT->header();
echo $OUTPUT->heading('Logs do plugin OAuth2 Auto Link');

// Caminho do arquivo de log
$logfile = __DIR__ . '/logs/oauth2_autolink.log';

if (file_exists($logfile)) {
    $lines = file($logfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $lines = array_reverse($lines); // Mostrar o mais recente primeiro

    echo html_writer::start_tag('div', ['class' => 'oauth2-log-view']);
    echo html_writer::start_tag('pre', ['style' => 'background:#111;color:#0f0;padding:10px;border-radius:8px;max-height:600px;overflow:auto;']);
    foreach ($lines as $line) {
        echo s($line) . "\n";
    }
    echo html_writer::end_tag('pre');
    echo html_writer::end_tag('div');
} else {
    echo $OUTPUT->notification('Nenhum log encontrado ainda.', 'info');
}

echo $OUTPUT->footer();
