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

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\Model\Collection;
use Contao\Module;
use Contao\NewsModel;

class NewsSortingListener extends AbstractNewsListFetchItemsListener
{
    /**
     * newsListFetchItems hook.
     *
     * @param array           $newsArchives
     * @param bool            $featured
     * @param int             $limit
     * @param int             $offset
     * @param \ModuleNewsList $module
     *
     * @return Model\Collection|NewsModel|bool|null
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

        if (null !== $collection && 'order_random_date_desc' === $module->news_sorting) {
            $models = $collection->getModels();

            usort($models, function ($a, $b) {
                return $b->date - $a->date;
            });

            $collection = new Collection($models, 'tl_news');
        }

        return $collection;
    }
}
