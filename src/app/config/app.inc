<?php

return [
    'test' => 123,
    // put your application model configuration in this file

    // you can override the configuration in local environments by creating
    // app.local.inc in the same directory and excluding it from VCS.
    'db' => [
        'dns' => 'sqlite:' . dirname(__DIR__, 3) . '/database/data.sqlite3'
    ]
];
