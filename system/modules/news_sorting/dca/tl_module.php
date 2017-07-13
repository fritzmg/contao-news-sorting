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
 * Extend tl_module palettes
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['newslist'] = str_replace(',skipFirst', ',skipFirst,news_sorting', $GLOBALS['TL_DCA']['tl_module']['palettes']['newslist']);

/**
 * Add new fields to tl_module
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['news_sorting'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['news_sorting'],
	'default'                 => 'sort_date_desc',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array('sort_date_desc', 'sort_date_asc', 'sort_headline_asc', 'sort_headline_desc', 'sort_random'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(32) NOT NULL default ''"
);
