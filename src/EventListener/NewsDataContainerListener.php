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

use Contao\CoreBundle\ServiceAnnotation\Callback;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @Callback(table="tl_news", target="config.onload")
 */
class NewsDataContainerListener
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function __invoke(): void
    {
        $session = $this->requestStack->getCurrentRequest()->getSession();
        $sorting = $session->getBag('contao_backend')->get('sorting')['tl_news'] ?? null;

        // Only set sorting as the first field if custom sorting is chosen.
        if ('sorting' === $sorting) {
            $GLOBALS['TL_DCA']['tl_news']['list']['sorting']['fields'] = ['sorting', 'date'];
        }
    }
}
