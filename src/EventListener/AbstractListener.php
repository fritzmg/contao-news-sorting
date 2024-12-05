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

abstract class AbstractListener
{
    /**
     * News list sort features not in the core.
     */
    protected static array $moduleSortOptions = [
        'order_random_date_desc',
        'order_custom_date_asc',
        'order_custom_date_desc',
    ];
}
