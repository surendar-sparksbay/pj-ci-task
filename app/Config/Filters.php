<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
    public array $aliases = [
        'csrf'     => \CodeIgniter\Filters\CSRF::class,
        'toolbar'  => \CodeIgniter\Filters\DebugToolbar::class,
        'role'     => \App\Filters\RoleFilter::class,  // Register the RoleFilter here
    ];

    public array $globals = [
        'before' => [],
        'after'  => [
            'toolbar',
        ],
    ];

    public array $methods = [];

    public array $filters = [
        'role' => ['before' => ['admin/*', 'client/*']],
    ];
}
