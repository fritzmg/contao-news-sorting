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

use Codefog\NewsCategoriesBundle\Criteria\NewsCriteria;
use Codefog\NewsCategoriesBundle\Criteria\NewsCriteriaBuilder;
use Codefog\NewsCategoriesBundle\Exception\CategoryNotFoundException;
use Contao\CoreBundle\Exception\PageNotFoundException;
use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\Model\Collection;
use Contao\ModuleNewsList;

final class NewsCategoriesNewsSortingListener extends AbstractNewsListFetchItemsListener
{
    /**
     * @var NewsCriteriaBuilder|null
     */
    private $searchBuilder;

    /**
     * InsertTagsListener constructor.
     */
    public function __construct(?NewsCriteriaBuilder $searchBuilder)
    {
        $this->searchBuilder = $searchBuilder;
    }

    /**
     * On news list fetch items.
     *
     * @param bool|null $featured
     * @param int       $limit
     * @param int       $offset
     *
     * @return Collection|null
     *
     * @Hook("newsListFetchItems", priority=128)
     */
    public function onNewsListFetchItems(array $archives, $featured, $limit, $offset, ModuleNewsList $module)
    {
        if (null === $this->searchBuilder) {
            return null;
        }

        if (null === ($criteria = $this->getCriteria($archives, $featured, $module))) {
            return null;
        }

        $criteria->setLimit($limit);
        $criteria->setOffset($offset);

        $options = $criteria->getOptions();
        $options['order'] = $this->getOrder($module);

        return $criteria->getNewsModelAdapter()->findBy(
            $criteria->getColumns(),
            $criteria->getValues(),
            $options
        );
    }

    /**
     * Get the criteria.
     *
     * @param bool|null $featured
     *
     * @throws PageNotFoundException
     *
     * @return NewsCriteria|null
     */
    private function getCriteria(array $archives, $featured, ModuleNewsList $module)
    {
        try {
            $criteria = $this->searchBuilder->getCriteriaForListModule($archives, $featured, $module);
        } catch (CategoryNotFoundException $e) {
            throw new PageNotFoundException($e->getMessage());
        }

        return $criteria;
    }
}
