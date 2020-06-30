<?php

$header = <<<HEADER
This file is part of The Prisoner's Dilemma Project, by Theodore R. Smith.

Copyright © 2020 Theodore R. Smith <theodore@phpexperts.pro>.
  GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690
  https://github.com/hopeseekr/PrisonersDilemma

This file is licensed under the MIT License.
HEADER;

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony'       => true,
        'elseif'         => false,
        'yoda_style'     => false,
        'list_syntax'    => ['syntax'  => 'short'],
        'concat_space'   => ['spacing' => 'one'],
        'binary_operator_spaces' => array(
            'align_double_arrow' => true,
        ),
        'phpdoc_no_alias_tag'          => false,
        'declare_strict_types'         => true,
        'no_superfluous_elseif'        => true,
        'blank_line_after_opening_tag' => false,
        'header_comment' => [
            'header'       => $header,
            'location'     => 'after_declare_strict',
            'comment_type' => 'PHPDoc',
        ]
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('bootstrap')
            ->exclude('vendor')
            ->in(__DIR__)
    );
