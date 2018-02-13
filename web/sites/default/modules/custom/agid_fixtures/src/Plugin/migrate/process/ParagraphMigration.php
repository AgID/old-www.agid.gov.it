<?php

namespace Drupal\agid_fixtures\Plugin\migrate\process;

use Drupal\migrate\Plugin\MigrateIdMapInterface;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;
use Drupal\migrate\Plugin\migrate\process\Migration;

/**
 * Calculates value of a property based on a previous migration of paragraphs.
 *
 * @MigrateProcessPlugin(
 *   id = "paragraph_migration"
 * )
 */
class ParagraphMigration extends Migration {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $migration_ids = $this->configuration['migration'];
    if (!is_array($migration_ids)) {
      $migration_ids = [$migration_ids];
    }
    if (!is_array($value)) {
      $value = [$value];
    }
    $this->skipOnEmpty($value);
    $self = FALSE;
    /** @var \Drupal\migrate\Plugin\MigrationInterface[] $migrations */
    $destination_ids = NULL;
    $source_id_values = [];
    $migrations = $this->migrationPluginManager->createInstances($migration_ids);

    foreach ($migrations as $migration_id => $migration) {

      if ($migration_id == $this->migration->id()) {
        $self = TRUE;
      }
      if (isset($this->configuration['source_ids'][$migration_id])) {
        $configuration = ['source' => $this->configuration['source_ids'][$migration_id]];
        $source_id_values[$migration_id] = $this->processPluginManager
          ->createInstance('get', $configuration, $this->migration)
          ->transform(NULL, $migrate_executable, $row, $destination_property);
      }
      else {
        $source_id_values[$migration_id] = $value;
      }
      // Break out of the loop as soon as a destination ID is found.
      if ($destination_ids = $migration->getIdMap()->lookupDestinationId($source_id_values[$migration_id])) {
        break;
      }
    }

    if (!$destination_ids && !empty($this->configuration['no_stub'])) {
      return NULL;
    }

    if (!$destination_ids && ($self || isset($this->configuration['stub_id']) || count($migrations) == 1)) {
      // If the lookup didn't succeed, figure out which migration will do the
      // stubbing.
      if ($self) {
        $migration = $this->migration;
      }
      elseif (isset($this->configuration['stub_id'])) {
        $migration = $migrations[$this->configuration['stub_id']];
      }
      else {
        $migration = reset($migrations);
      }
      $destination_plugin = $migration->getDestinationPlugin(TRUE);
      // Only keep the process necessary to produce the destination ID.
      $process = $migration->getProcess();

      // We already have the source ID values but need to key them for the Row
      // constructor.
      $source_ids = $migration->getSourcePlugin()->getIds();
      $values = [];
      foreach (array_keys($source_ids) as $index => $source_id) {
        $values[$source_id] = $source_id_values[$migration->id()][$index];
      }

      $stub_row = new Row($values + $migration->getSourceConfiguration(), $source_ids, TRUE);

      // Do a normal migration with the stub row.
      $migrate_executable->processRow($stub_row, $process);
      $destination_ids = [];
      try {
        $destination_ids = $destination_plugin->import($stub_row);
      }
      catch (\Exception $e) {
        $migration->getIdMap()->saveMessage($stub_row->getSourceIdValues(), $e->getMessage());
      }

      if ($destination_ids) {
        $migration->getIdMap()->saveIdMapping($stub_row, $destination_ids, MigrateIdMapInterface::STATUS_NEEDS_UPDATE);
      }
    }

    if ($destination_ids) {
      return [
        'target_id' => $destination_ids[0],
        'target_revision_id' => $destination_ids[1],
      ];
    }
  }

}
