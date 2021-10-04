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

abstract class AbstractListener
{
    /**
     * News list sort features not in the core.
     *
     * @var array
     */
    protected static $moduleSortOptions = [
        'order_random_date_desc',
        'order_custom_date_asc',
        'order_custom_date_desc',
    ];
}
