<?php

$finder = PhpCsFixer\Finder::create()
    ->in(
        [
            __DIR__.'/controller',
            __DIR__.'/model',
        ]
    );

$config = new M6Web\CS\Config\BedrockStreaming();
$config->setFinder($finder);

return $config;