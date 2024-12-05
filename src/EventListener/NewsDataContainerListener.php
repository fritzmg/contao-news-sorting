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

use Contao\CoreBundle\ServiceAnnotation\Callback;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface;

/**
 * @Callback(table="tl_news", target="config.onload")
 */
class NewsDataContainerListener
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function __invoke(): void
    {
        $session = $this->requestStack->getCurrentRequest()->getSession();
        $sessionBag = $session->getBag('contao_backend');
        \assert($sessionBag instanceof AttributeBagInterface);
        $sorting = $sessionBag->get('sorting')['tl_news'] ?? null;

        // Only set sorting as the first field if custom sorting is chosen.
        if ('sorting' === $sorting) {
            $GLOBALS['TL_DCA']['tl_news']['list']['sorting']['fields'] = ['sorting', 'date'];
        }
    }
}
