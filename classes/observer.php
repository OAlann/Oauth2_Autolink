<?php
namespace local_oauth2_autolink;

defined('MOODLE_INTERNAL') || die();

class observer {
    public static function user_created_handler(\core\event\user_created $event) {
        global $DB, $USER;

        $data = $event->get_data();
        $userid = $data['objectid'];

        self::log_message("Evento detectado: user_created para o usuário ID $userid");

        try {
            // Evita duplicação
            if ($DB->record_exists('auth_oauth2_linked_login', ['userid' => $userid])) {
                self::log_message("Usuário $userid já possui registro vinculado. Nenhuma ação necessária.");
                return;
            }

            $record = new \stdClass();
            $record->timecreated = time();
            $record->timemodified = time();
            $record->usermodified = $USER->id ?? 0;
            $record->userid = $userid;
            $record->issuerid = 1; // Ajuste conforme o issuer configurado no seu OAuth2
            $record->username = $DB->get_field('user', 'username', ['id' => $userid]);
            $record->email = $DB->get_field('user', 'email', ['id' => $userid]);
            $record->confirmtoken = md5(uniqid($userid . '_oauth2', true));
            $record->confirmtokenexpires = null; // Pode deixar null, pois o campo aceita NULL

            $DB->insert_record('auth_oauth2_linked_login', $record);

            self::log_message("Registro criado com sucesso para usuário ID $userid.");
        } catch (\Exception $e) {
            self::log_message("Erro ao processar usuário $userid: " . $e->getMessage());
        }
    }

    private static function log_message($message) {
        $logdir = __DIR__ . '/../../oauth2_autolink/logs';
        if (!file_exists($logdir)) {
            mkdir($logdir, 0777, true);
        }
        $logfile = $logdir . '/oauth2_autolink.log';
        $timestamp = date('Y-m-d H:i:s');
        file_put_contents($logfile, "[$timestamp] $message\n", FILE_APPEND);
    }
}
