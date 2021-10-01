<?php

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\NewsBundle\ContaoNewsBundle;

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


/**
 * Check for news bundle
 */
if (class_exists(ContaoCoreBundle::class) && !class_exists(ContaoNewsBundle::class)) {
	return;
}


/**
 * Extend tl_module palettes
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['newslist'] = str_replace(',news_featured', ',news_featured,news_order', $GLOBALS['TL_DCA']['tl_module']['palettes']['newslist']);


/**
 * Add options_callback to news_order field
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['news_order']['options_callback'] = ['NewsSorting', 'getSortingOptions'];
$GLOBALS['TL_DCA']['tl_module']['fields']['news_order']['reference'] = &$GLOBALS['TL_LANG']['tl_module'];
