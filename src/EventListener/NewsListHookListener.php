<?php

declare(strict_types=1);

/*
 * This file is part of ContaoNewsSorting.
 *
 * (c) INSPIRED MINDS
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoNewsSorting\EventListener;

use Contao\Model\Collection;
use Contao\Module;
use InspiredMinds\ContaoNewsFilterEvent\NewsListHookListenerInterface;

/**
 * Decorates the NewsListHookListener from ContaoNewsFilterEvent, so that we can sort the records afterwards.
 */
class NewsListHookListener implements NewsListHookListenerInterface
{
    public function __construct(private readonly NewsListHookListenerInterface $inner)
    {
    }

    public function onNewsListCountItems(array $archives, bool|null $featured, Module $module)
    {
        return $this->inner->onNewsListCountItems($archives, $featured, $module);
    }

    public function onNewsListFetchItems(array $archives, bool|null $featured, int $limit, int $offset, Module $module)
    {
        $result = $this->inner->onNewsListFetchItems($archives, $featured, $limit, $offset, $module);

        if (!$result instanceof Collection) {
            return $result;
        }

        return $this->applyOrderRandomDateDesc($result, $module);
    }

    private function applyOrderRandomDateDesc(Collection $collection, Module $module): Collection
    {
        if ('order_random_date_desc' !== $module->news_order) {
            return $collection;
        }

        $models = $collection->getModels();

        usort($models, static fn ($a, $b) => $b->date - $a->date);

        return new Collection($models, 'tl_news');
    }
}
