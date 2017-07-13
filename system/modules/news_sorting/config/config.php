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
 * Register the hooks
 */
$GLOBALS['TL_HOOKS']['newsListFetchItems'][] = array('NewsSorting','newsListFetchItems');
