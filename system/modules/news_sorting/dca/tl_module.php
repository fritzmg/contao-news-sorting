<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2017 Leo Feyer
 *
 * @package   news_sorting
 * @author    Fritz Michael Gschwantner <https://github.com/fritzmg>
 * @license   LGPL-3.0+
 * @copyright Fritz Michael Gschwantner 2017
 */


/**
 * Check for news bundle
 */
if (class_exists(\Contao\CoreBundle\ContaoCoreBundle::class) && !class_exists(\Contao\NewsBundle\ContaoNewsBundle::class)) {
	return;
}


/**
 * Extend tl_module palettes
 */
if (class_exists(\Contao\CoreBundle\ContaoCoreBundle::class)) {
	try {
	    $version = \Jean85\PrettyVersions::getVersion('contao/core-bundle')->getShortVersion();
	} catch (\OutOfBoundsException $e) {
	    $version = \Jean85\PrettyVersions::getVersion('contao/contao')->getShortVersion();
	}
    if (\Composer\Semver\Semver::satisfies($version, '<4.5')) {
    	\Contao\CoreBundle\DataContainer\PaletteManipulator::create()
    		->addField('news_order', 'config_legend', \Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_APPEND)
    		->applyToPalette('newslist', 'tl_module');
    }
} else {
	$GLOBALS['TL_DCA']['tl_module']['palettes']['newslist'] = str_replace(',news_featured', ',news_featured,news_order', $GLOBALS['TL_DCA']['tl_module']['palettes']['newslist']);
}


/**
 * Add options_callback to news_order field
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['news_order']['options_callback'] = ['NewsSorting', 'getSortingOptions'];
$GLOBALS['TL_DCA']['tl_module']['fields']['news_order']['reference'] = &$GLOBALS['TL_LANG']['tl_module'];
