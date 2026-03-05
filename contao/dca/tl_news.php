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
    'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
];
