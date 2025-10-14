<?php
defined('MOODLE_INTERNAL') || die();

$observers = [
    [
        'eventname'   => '\core\event\user_created',
        'callback'    => '\local_oauth2_autolink\observer::user_created_handler',
        'includefile' => '/local/oauth2_autolink/classes/observer.php',
        'priority'    => 9999,
        'internal'    => false,
    ],
];
