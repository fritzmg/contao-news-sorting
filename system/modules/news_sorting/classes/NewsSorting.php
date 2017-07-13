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


class NewsSorting
{
	/**
	 * newsListFetchItems hook
	 *
	 * @param array   $newsArchives
	 * @param boolean $blnFeatured
	 * @param integer $limit
	 * @param integer $offset
	 * @param \ModuleNewsList $objModule
	 *
	 * @return Model\Collection|NewsModel|null|boolean
	 */
	public function newsListFetchItems($newsArchives, $blnFeatured, $limit, $offset, $objModule)
	{
		if (!$objModule->news_sorting || $objModule->news_sorting == 'sort_date_desc')
		{
			return false;
		}
		
		// Determine sorting
		$t = \NewsModel::getTable();
		$arrOptions = array();
		switch ($objModule->news_sorting)
		{
			case 'sort_date_asc':
				$arrOptions['order'] = "$t.date ASC";
				break;

			case 'sort_headline_asc':
				$arrOptions['order'] = "$t.headline ASC";
				break;

			case 'sort_headline_desc':
				$arrOptions['order'] = "$t.headline DESC";
				break;

			case 'sort_random':
				$arrOptions['order'] = "RAND()";
				break;

			default:
				$arrOptions['order'] = "$t.date DESC";
		}

		return \NewsModel::findPublishedByPids($newsArchives, $blnFeatured, $limit, $offset, $arrOptions);
	}
}
