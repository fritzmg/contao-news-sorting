<?php

declare(strict_types=1);

/*
 * This file is part of ContaoNewsSorting.
 *
 * (c) INSPIRED MINDS
 *
 * @license LGPL-3.0-or-later
 */

$GLOBALS['TL_DCA']['tl_news']['fields']['sorting'] = [
    'sorting' => true,
    'sql' => 'int(10) unsigned NOT NULL default 0',
];
