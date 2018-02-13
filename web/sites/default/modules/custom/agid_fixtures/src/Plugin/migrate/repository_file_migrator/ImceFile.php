<?php

namespace Drupal\agid_fixtures\Plugin\migrate\repository_file_migrator;

use Drupal\agid_fixtures\Plugin\RepositoryFileMigratorBase;

/**
 * Class ImceFile.
 *
 * @RepositoryFileMigrator(
 *   id = "imce_file_migrator",
 *   name = @Translation("File Repository Migrator - IMCE File"),
 *   module = "imce",
 *   entityType = "file",
 *   bundles = NULL
 * )
 */
class ImceFile extends RepositoryFileMigratorBase {}
