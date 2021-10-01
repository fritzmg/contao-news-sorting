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

use Contao\Module;
use Contao\NewsModel;

abstract class AbstractNewsListFetchItemsListener extends AbstractListener
{
    /**
     * Checks whether the hook should be used.
     */
    protected function useHook(Module $module): bool
    {
        // don't use hook for default sorting
        if ((!$module->news_order || 'order_date_desc' === $module->news_order) && 'featured_first' !== $module->news_featured) {
            return false;
        }

        // only use hook , if the module options are used
        return \in_array($module->news_order, self::$moduleSortOptions, true);
    }

    protected function getOrder(Module $module): string
    {
        // Determine sorting
        $t = NewsModel::getTable();
        $order = '';

        if ('featured_first' === $module->news_featured) {
            $order .= "$t.featured DESC, ";
        }

        switch ($module->news_order) {
            case 'order_date_asc':
                $order .= "$t.date ASC";
                break;

            case 'order_headline_asc':
                $order .= "$t.headline ASC";
                break;

            case 'order_headline_desc':
                $order .= "$t.headline DESC";
                break;

            case 'order_random':
            case 'order_random_date_desc':
                $order .= 'RAND()';
                break;

            case 'order_custom_date_asc':
                $order .= "$t.sorting, $t.date ASC";
                break;

            case 'order_custom_date_desc':
                $order .= "$t.sorting, $t.date DESC";
                break;

            default:
                $order .= "$t.date DESC";
        }

        return $order;
    }
}
