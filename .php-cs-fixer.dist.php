<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PER-CS' => true,
        '@PHP82Migration' => true,
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        '@PhpCsFixer' => true,
        'align_multiline_comment' => false,
    ])
    ->setFinder($finder)
    ;
