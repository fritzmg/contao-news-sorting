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

use Contao\Model\Collection;
use Contao\Module;
use Contao\NewsModel;

abstract class AbstractNewsListFetchItemsListener extends AbstractListener
{
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

    protected function applyOrderRandomDateDesc(?Collection $collection, Module $module): ?Collection
    {
        if (null === $collection || 'order_random_date_desc' !== $module->news_sorting) {
            return $collection;
        }

        $models = $collection->getModels();

        usort($models, function ($a, $b) {
            return $b->date - $a->date;
        });

        return new Collection($models, 'tl_news');
    }
}
