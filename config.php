<?php

return [
    'log' => [
        'enabled' => true,
        'path' => __DIR__ . '/logs',
        'filename' => 'rich-exceptions.log',
        'level' => 'debug',
        'max_files' => 30,
        'permission' => 0644,
        'use_locking' => true,
        'locking_timeout' => 5,
        'bubble' => true,
        'formatter' => [
            'format' => "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n",
            'date_format' => 'Y-m-d H:i:s',
            'allowInlineLineBreaks' => true,
            'ignoreEmptyContextAndExtra' => true,
        ],
    ],
];
