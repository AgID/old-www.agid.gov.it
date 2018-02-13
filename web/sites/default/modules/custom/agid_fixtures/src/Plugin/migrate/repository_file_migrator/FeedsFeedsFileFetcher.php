<?php

namespace Drupal\agid_fixtures\Plugin\migrate\repository_file_migrator;

use Drupal\agid_fixtures\Plugin\RepositoryFileMigratorBase;

/**
 * Class FeedsFeedsFileFetcher.
 *
 * @RepositoryFileMigrator(
 *   id = "feeds_feeds_file_fetcher_migrator",
 *   name = @Translation("File Repository Migrator - Feeds FeedsFileFetcher"),
 *   module = "feeds",
 *   entityType = "FeedsFileFetcher",
 *   bundles = NULL
 * )
 */
class FeedsFeedsFileFetcher extends RepositoryFileMigratorBase {}
