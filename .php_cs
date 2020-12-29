<?php

$config = require 'vendor/broadway/coding-standard/.php_cs.dist';

$config->setFinder(
    \PhpCsFixer\Finder::create()
        ->in([
            __DIR__ . '/src',
            __DIR__ . '/test',
        ])
);

$config->setRules([
    'php_unit_method_casing' => ['case' => 'snake_case']
]);

return $config;
