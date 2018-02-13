<?php

namespace Drupal\agid_fixtures\Plugin\migrate\repository_file_migrator;

use Drupal\agid_fixtures\Plugin\RepositoryFileMigratorBase;

/**
 * Class WebformSubmission.
 *
 * @RepositoryFileMigrator(
 *   id = "webform_submission_migrator",
 *   name = @Translation("File Repository Migrator - Webform Submission"),
 *   module = "webform",
 *   entityType = "submission",
 *   bundles = NULL
 * )
 */
class WebformSubmission extends RepositoryFileMigratorBase {}
