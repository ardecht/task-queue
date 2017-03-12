<?php

$finder = PhpCsFixer\Finder::create()
//    ->exclude('tests') // Maybe dont need exclude tests?
    ->in(__DIR__);

return PhpCsFixer\Config::create()
    ->setUsingCache(false)
    ->setRules(array(
        '@PSR2' => true,
        'full_opening_tag' => true,
    ))
    ->setFinder($finder);