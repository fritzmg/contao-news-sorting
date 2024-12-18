<?php

declare(strict_types=1);

/*
 * This file is part of ContaoNewsSorting.
 *
 * (c) INSPIRED MINDS
 *
 * @license LGPL-3.0-or-later
 */

namespace InspiredMinds\ContaoNewsSorting\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Doctrine\DBAL\Connection;

class NewsSortingMigration extends AbstractMigration
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function shouldRun(): bool
    {
        $schemaManager = $this->connection->createSchemaManager();

        if (!$schemaManager->tablesExist(['tl_news'])) {
            return false;
        }

        $columns = $schemaManager->listTableColumns('tl_news');

        return isset($columns['news_order'], $columns['news_sorting']);
    }

    public function run(): MigrationResult
    {
        $this->connection->executeStatement("UPDATE tl_module SET news_order = REPLACE(news_sorting, 'sort_', 'order_') WHERE type = 'newslist'");
        $this->connection->executeStatement('ALTER TABLE tl_module DROP COLUMN news_sorting');

        return $this->createResult(true);
    }
}
