<?php

declare(strict_types=1);

/*
 * This file is part of ContaoNewsSorting.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoNewsSorting\EventListener;

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
     * @return Collection|NewsModel|bool|null
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

        return $this->applyOrderRandomDateDesc($collection, $module);
    }
}
