<?php

namespace Drupal\agid_migrate_tools\Plugin\migrate\repository_file_migrator;

use Drupal\agid_migrate_tools\Plugin\RepositoryFileMigratorBase;

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
