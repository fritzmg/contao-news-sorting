<?php

declare(strict_types=1);

namespace NewsSortingBundle\EventListener;

abstract class AbstractListener
{
    /**
     * News list sort features not in the core
     * @var array
     */
    protected static $moduleSortOptions = [
        'order_random_date_desc',
        'order_custom_date_asc',
        'order_custom_date_desc',
    ];
}
