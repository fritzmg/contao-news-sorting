<?php

use Composer\Semver\Semver;

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2017 Leo Feyer
 *
 * @package   news_sorting
 * @author    Fritz Michael Gschwantner <https://github.com/fritzmg>
 * @license   LGPL-3.0-or-later
 * @copyright Fritz Michael Gschwantner 2019
 */


$GLOBALS['TL_LANG']['tl_module']['order_date_asc']         = 'Datum (aufsteigend)';
$GLOBALS['TL_LANG']['tl_module']['order_date_desc']        = 'Datum (absteigend)';
$GLOBALS['TL_LANG']['tl_module']['order_headline_asc']     = 'Titel (aufsteigend)';
$GLOBALS['TL_LANG']['tl_module']['order_headline_desc']    = 'Titel (absteigend)';
$GLOBALS['TL_LANG']['tl_module']['order_random']           = 'Zufällig';
$GLOBALS['TL_LANG']['tl_module']['order_random_date_desc'] = 'Zufällig (Datum absteigend)';
$GLOBALS['TL_LANG']['tl_module']['order_featured_desc']    = 'Hervorgehoben (Datum absteigend)';

if (Semver::satisfies(NewsSorting::getContaoVersion(), '<4.8')) {
    $GLOBALS['TL_LANG']['tl_module']['featured_first']     = 'Zeige hervorgehobene Nachrichten zuerst';
}
