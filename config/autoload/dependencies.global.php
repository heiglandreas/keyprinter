<?php

declare(strict_types=1);

use Org_Heigl\KeyPrinter\ConfigProvider;

return [
    'di' => [
        'cache' => sys_get_temp_dir(),
        'class' => ConfigProvider::class,
    ],
];

