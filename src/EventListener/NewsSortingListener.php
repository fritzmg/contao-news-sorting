<?php

declare(strict_types=1);

/*
 * This file is part of the NewsSorting Bundle.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace NewsSortingBundle\EventListener;

use Codefog\NewsCategoriesBundle\CodefogNewsCategoriesBundle;
use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\Model\Collection;
use Contao\Module;
use Contao\NewsModel;
use Contao\StringUtil;

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

class NewsSortingListener extends AbstractNewsListFetchItemsListener
{
    /**
     * Whether the news_categories bundle is available
     * @var bool
     */
    protected $hasNewsCategories = false;

    /**
     * Constructor
     */
    public function __construct(array $bundles)
    {
        $this->hasNewsCategories = \in_array(CodefogNewsCategoriesBundle::class, $bundles);
    }

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
     *
     * @Hook("newsListFetchItems")
     */
    public function onNewsListFetchItems($newsArchives, $featured, $limit, $offset, Module $module)
    {
        if (!$this->useHook($module)) {
            return false;
        }

        $order = $this->getOrder($module);

        $collection = \NewsModel::findPublishedByPids($newsArchives, $featured, $limit, $offset, ['order' => $order]);

        if (null !== $collection && 'order_random_date_desc' === $module->news_sorting)
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
