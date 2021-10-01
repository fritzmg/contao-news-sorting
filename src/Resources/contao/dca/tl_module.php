<?php

declare(strict_types=1);

/*
 * This file is part of the NewsSorting Bundle.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

/*
 * Add options_callback to news_order field
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['news_order']['reference'] = &$GLOBALS['TL_LANG']['tl_module'];
