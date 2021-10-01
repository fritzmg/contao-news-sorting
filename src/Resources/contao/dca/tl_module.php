<?php

use Composer\Semver\Semver;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\CoreBundle\DataContainer\PaletteManipulator;
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
$contaoVersion = NewsSorting::getContaoVersion();

if (class_exists(ContaoCoreBundle::class)) {
    if (Semver::satisfies($contaoVersion, '<4.5')) {
    	PaletteManipulator::create()
    		->addField('news_order', 'config_legend', PaletteManipulator::POSITION_APPEND)
			->applyToPalette('newslist', 'tl_module')
		;
	}
} else {
	$GLOBALS['TL_DCA']['tl_module']['palettes']['newslist'] = str_replace(',news_featured', ',news_featured,news_order', $GLOBALS['TL_DCA']['tl_module']['palettes']['newslist']);
}

if (Semver::satisfies($contaoVersion, '<4.8')) {
	$GLOBALS['TL_DCA']['tl_module']['fields']['news_featured']['options'][] = 'featured_first';
}


/**
 * Add options_callback to news_order field
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['news_order']['options_callback'] = ['NewsSorting', 'getSortingOptions'];
$GLOBALS['TL_DCA']['tl_module']['fields']['news_order']['reference'] = &$GLOBALS['TL_LANG']['tl_module'];
