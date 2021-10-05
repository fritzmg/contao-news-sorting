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
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class NewsDataContainerListener
{
    /** @var SessionInterface */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /** @Callback(table="tl_news", target="config.onload") */
    public function onLoad(): void
    {
        $sorting = $this->session->getBag('contao_backend')->get('sorting')['tl_news'] ?? null;

        // Only set sorting as the first field if custom sorting is chosen.
        if ('sorting' === $sorting) {
            $GLOBALS['TL_DCA']['tl_news']['list']['sorting']['fields'] = ['sorting', 'date'];
        }
    }
}
