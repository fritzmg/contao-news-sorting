<?php

declare(strict_types=1);

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
 * Add options_callback to news_order field
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['news_order']['options_callback'] = ['NewsSorting', 'getSortingOptions'];
$GLOBALS['TL_DCA']['tl_module']['fields']['news_order']['reference'] = &$GLOBALS['TL_LANG']['tl_module'];
