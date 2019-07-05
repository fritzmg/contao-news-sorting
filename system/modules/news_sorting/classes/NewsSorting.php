<?php

use Codefog\NewsCategoriesBundle\CodefogNewsCategoriesBundle;
use Composer\Semver\Semver;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\DataContainer;
use Contao\Model\Collection;
use Contao\Module;
use Contao\StringUtil;
use Contao\System;
use Jean85\PrettyVersions;

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2017 Leo Feyer
 *
 * @package   news_sorting
 * @author    Fritz Michael Gschwantner <https://github.com/fritzmg>
 * @license   LGPL-3.0+
 * @copyright Fritz Michael Gschwantner 2017
 */


class NewsSorting
{
    /**
     * News list sort options of the contao/news-bundle (>=4.5)
     * @var array
     */
    protected static $coreSortOptions45 = ['order_date_desc', 'order_date_asc', 'order_headline_asc', 'order_headline_desc', 'order_random'];

    /**
     * News list sort features not in the core
     * @var array
     */
    protected static $moduleSortOptions = ['order_random_date_desc'];

    /**
     * The Contao 4 version, if available
     * @var string|null 
     */
    protected $contao4Version = null;

    /**
     * Whether the news_categories bundle is available
     * @var bool
     */
    protected $hasNewsCategories = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        if (class_exists(\Contao\CoreBundle\ContaoCoreBundle::class)) {
            try {
                $this->contao4Version = \Jean85\PrettyVersions::getVersion('contao/core-bundle')->getShortVersion();
            } catch (\OutOfBoundsException $e) {
                $this->contao4Version = \Jean85\PrettyVersions::getVersion('contao/contao')->getShortVersion();
            }

            $bundles = System::getContainer()->getParameter('kernel.bundles');
            $this->hasNewsCategories = in_array(CodefogNewsCategoriesBundle::class, $bundles);
        }
    }

    /**
     * DCA callback for sorting options
     *
     * @param DataContainer $dc
     *
     * @return array
     */
    public function getSortingOptions(DataContainer $dc)
    {
        if ($dc->activeRecord && 'newsmenu' === $dc->activeRecord->type) {
            if ($this->contao4Version === null || \Composer\Semver\Semver::satisfies($this->contao4Version, '<4.5')) {
                return ['ascending', 'descending'];    
            }

            return ['order_date_asc', 'order_date_desc'];
        }

        return array_unique(array_merge(self::$coreSortOptions45, self::$moduleSortOptions));
    }

    /**
     * newsListFetchItems hook
     *
     * @param array   $newsArchives
     * @param boolean $featured
     * @param integer $limit
     * @param integer $offset
     * @param \ModuleNewsList $module
     *
     * @return Model\Collection|NewsModel|null|boolean
     */
    public function newsListFetchItems($newsArchives, $featured, $limit, $offset, Module $module)
    {
        if (!$this->useHook($module)) {
            return false;
        }
        
        // Determine sorting
        $t = \NewsModel::getTable();
        $order = '';

        if ('featured_first' === $module->news_featured) {
			$order .= "$t.featured DESC, ";
		}
        
        switch ($module->news_order)
        {
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
                $order .= "RAND()";
                break;
                
            default:
                $order .= "$t.date DESC";
        }

        $collection = \NewsModel::findPublishedByPids($newsArchives, $featured, $limit, $offset, ['order' => $order]);

        if (null !== $collection && 'order_random_date_desc' === $module->news_sorting)
        {
            $models = $collection->getModels();

            usort($models, function($a, $b)
            {
                return $b->date - $a->date;
            });

            $collection = new Collection($models, 'tl_news');
        }

        return $collection;
    }

    /**
     * Checks whether the hook should be used.
     *
     * @param Module $module
     *
     * @return bool
     */
    private function useHook(Module $module)
    {
        // don't use hook for default sorting
        if ((!$module->news_order || 'order_date_desc' === $module->news_order) && 'featured_first' !== $module->news_featured) {
            return false;
        }

        // not Contao 4: always use hook
        if (null === $this->contao4Version) {
            return true;
        }

        // don't use hook when news_categories filtering is enabled
        if ($this->hasNewsCategories) {
            if ($module->news_filterCategoriesCumulative || $module->news_filterCategories || $module->news_relatedCategories) {
                return false;
            }

            $defaultFilter = StringUtil::deserialize($module->news_filterDefault, true);

            if (!empty($defaultFilter)) {
                return false;
            }
        }

        // use the hook, if the new 'featured_first' option is used in Contao <4.8
        if (Semver::satisfies($this->contao4Version, '<4.8') && 'featured_first' === $module->news_featured) {
            return true;
        }

        // only use hook in Contao >=4.5, if the core options are not used
        if (Semver::satisfies($this->contao4Version, '>=4.5') && \in_array($module->news_order, self::$coreSortOptions45)) {
            return false;
        }

        // otherwise use the hook
        return true;
    }

    /**
     * Helper function to get the current Contao version.
     */
    public static function getContaoVersion(): string
    {
        if (class_exists(ContaoCoreBundle::class)) {
            try {
                return PrettyVersions::getVersion('contao/core-bundle')->getShortVersion();
            } catch (\OutOfBoundsException $e) {
                return PrettyVersions::getVersion('contao/contao')->getShortVersion();
            }           
        } else {
            return VERSION.'.'.BUILD;
        }
    }
}
