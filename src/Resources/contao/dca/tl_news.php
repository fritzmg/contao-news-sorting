<?php

declare(strict_types=1);

$GLOBALS['TL_DCA']['tl_news']['fields']['sorting'] = [
    'sorting' => true,
    'sql'     => "int(10) unsigned NOT NULL default 0",
];
