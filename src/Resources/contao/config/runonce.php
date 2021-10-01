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

use Contao\Database;

$db = Database::getInstance();

if ($db->tableExists('tl_module') && $db->fieldExists('news_sorting', 'tl_module')) {
    $db->execute("UPDATE tl_module SET news_order = REPLACE(news_sorting, 'sort_', 'order_') WHERE type = 'newslist'");
    $db->execute("ALTER TABLE tl_module DROP COLUMN news_sorting");
}
