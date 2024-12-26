<?php
// Define modules in here
use app\api\modules;

$modules = [
    'api' => [
        'class' => app\api\modules\Module::class,
    ],
];

return $modules;