<?php

use Contao\DataContainer;
use Contao\Model\Collection;
use Contao\Module;

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
     * News list sort options of the contao/news-bundle (>=4.8)
     * @var array
     */
    protected static $coreSortOptions48 = ['order_featured_desc'];

    /**
     * News list sort features not in the core
     */
    protected static $moduleSortOptions = ['order_random_date_desc'];

    /**
     * The Contao 4 version, if available
     * @var string|null 
     */
    protected $contao4Version = null;

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

        return array_unique(array_merge(self::$coreSortOptions45, self::$moduleSortOptions, self::$coreSortOptions48));
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
        $arrOptions = array();
        switch ($module->news_order)
        {
            case 'order_date_asc':
                $arrOptions['order'] = "$t.date ASC";
                break;

            case 'order_headline_asc':
                $arrOptions['order'] = "$t.headline ASC";
                break;

            case 'order_headline_desc':
                $arrOptions['order'] = "$t.headline DESC";
                break;

            case 'order_random':
            case 'order_random_date_desc':
                $arrOptions['order'] = "RAND()";
                break;

            case 'order_featured_desc':
                $arrOptions['order'] = "$t.featured DESC, $t.date DESC";
                break;
                
            default:
                $arrOptions['order'] = "$t.date DESC";
        }

        $collection = \NewsModel::findPublishedByPids($newsArchives, $featured, $limit, $offset, $arrOptions);

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
        if (!$module->news_order || 'order_date_desc' === $module->news_order) {
            return false;
        }

        // not Contao 4: use hook
        if (null === $this->contao4Version) {
            return true;
        }

        // only use hook in Contao >=4.5, if the core options are not used
        if (\Composer\Semver\Semver::satisfies($this->contao4Version, '>=4.5') && \in_array($module->news_order, self::$coreSortOptions45)) {
            return false;
        }

        // do not use the hook, if the new 'order_featured_desc' option is used in Contao >=4.8
        if (\Composer\Semver\Semver::satisfies($this->contao4Version, '>=4.8') && \in_array($module->news_order, self::$coreSortOptions48)) {
            return false;
        }

        // otherwise use the hook
        return true;
    }
}
