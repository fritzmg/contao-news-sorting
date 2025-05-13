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

use InspiredMinds\ContaoNewsFilterEvent\Event\NewsFilterEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
class NewsFilterEventListener extends AbstractListener
{
    public function __invoke(NewsFilterEvent $event): void
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
}
