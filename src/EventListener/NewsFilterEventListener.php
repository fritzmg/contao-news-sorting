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
use InspiredMinds\ContaoNewsFilterEvent\Event\NewsFilterEvent;
use InspiredMinds\ContaoNewsFilterEvent\EventListener\NewsListHookListener;

class NewsFilterEventListener extends AbstractListener
{
    public function __construct(private readonly NewsListHookListener $newsFilterEventHookListener)
    {
    }

    public function onNewsFilterEvent(NewsFilterEvent $event): void
    {
        $module = $event->getModule();

        if (!\in_array($module->news_order, self::$moduleSortOptions, true)) {
            return;
        }

        switch ($module->news_order) {
            case 'order_random_date_desc':
                $event->addOption('order', 'RAND()', true);
                break;

            case 'order_custom_date_asc':
                $event->addOption('order', 'tl_news.sorting, tl_news.date ASC', true);
                break;

            case 'order_custom_date_desc':
                $event->addOption('order', 'tl_news.sorting, tl_news.date DESC', true);
                break;
        }
    }

    /**
     * Overrides the newsListFetchItems Hook from ContaoNewsFilterEvent, so that we can sort the records afterwards.
     *
     * @return false|Collection<array-key, NewsModel>|NewsModel|null
     *
     * @Hook("newsListFetchItems", priority=1500)
     */
    public function onNewsListFetchItems(array $archives, bool|null $featured, int $limit, int $offset, Module $module)
    {
        $result = $this->newsFilterEventHookListener->onNewsListFetchItems($archives, $featured, $limit, $offset, $module);

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
