<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) { // Apenas para administradores
    $ADMIN->add('reports', new admin_externalpage(
        'local_oauth2_autolink_logs',
        get_string('pluginname', 'local_oauth2_autolink'),
        new moodle_url('/local/oauth2_autolink/report.php')
    ));
}
