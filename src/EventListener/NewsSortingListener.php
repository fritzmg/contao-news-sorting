<?php

declare(strict_types=1);

/*
 * This file is part of the NewsSorting Bundle.
 *
 * (c) inspiredminds
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\NewsSortingBundle\EventListener;

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

    /**
     * Checks whether the hook should be used.
     */
    private function useHook(Module $module): bool
    {
        // don't use hook for default sorting
        if ((!$module->news_order || 'order_date_desc' === $module->news_order) && 'featured_first' !== $module->news_featured) {
            return false;
        }

        // only use hook , if the module options are used
        return \in_array($module->news_order, self::$moduleSortOptions, true);
    }
}
