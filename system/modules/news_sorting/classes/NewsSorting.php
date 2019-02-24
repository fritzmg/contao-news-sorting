<?php

use Contao\Model\Collection;
use Contao\Module;

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
     * @param boolean $featured
     * @param integer $limit
     * @param integer $offset
     * @param \ModuleNewsList $module
     *
     * @return Model\Collection|NewsModel|null|boolean
     */
    public function newsListFetchItems($newsArchives, $featured, $limit, $offset, Module $module)
    {
        if (!$module->news_sorting || $module->news_sorting == 'sort_date_desc')
        {
            return false;
        }
        
        // Determine sorting
        $t = \NewsModel::getTable();
        $arrOptions = array();
        switch ($module->news_sorting)
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
            case 'sort_random_date_desc':
                $arrOptions['order'] = "RAND()";
                break;

            case 'sort_featured_desc':
                $arrOptions['order'] = "$t.featured DESC, $t.date DESC";
                break;
                
            default:
                $arrOptions['order'] = "$t.date DESC";
        }

        $collection = \NewsModel::findPublishedByPids($newsArchives, $featured, $limit, $offset, $arrOptions);

        if (null !== $collection && 'sort_random_date_desc' === $module->news_sorting)
        {
            $models = $collection->getModels();

            usort($models, function($a, $b)
            {
                return $b->date - $a->date;
            });

            $collection = new Collection($models, 'tl_news');
        }

        return $collection;
    }
}
