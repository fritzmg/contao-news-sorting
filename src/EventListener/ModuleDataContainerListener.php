<?php

declare(strict_types=1);

namespace NewsSortingBundle\EventListener;

use Contao\CoreBundle\ServiceAnnotation\Callback;
use Contao\DataContainer;

final class ModuleDataContainerListener extends AbstractListener
{
    /**
     * News list sort options of the contao/news-bundle
     * @var array
     */
    protected static $coreSortOptions = ['order_date_desc', 'order_date_asc', 'order_headline_asc', 'order_headline_desc', 'order_random'];

    /**
     * DCA callback for sorting options
     *
     * @param DataContainer $dc
     *
     * @return array
     *
     * @Callback(table="tl_module", target="fields.news_order.options")
     */
    public function getSortingOptions(DataContainer $dc): array
    {
        if ($dc->activeRecord && 'newsmenu' === $dc->activeRecord->type) {
            return ['order_date_asc', 'order_date_desc'];
        }

        return array_unique(array_merge(self::$coreSortOptions, self::$moduleSortOptions));
    }
}
