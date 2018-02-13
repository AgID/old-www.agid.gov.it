<?php

namespace Drupal\agid_fixtures\EventSubscriber;

use Drupal\file_entity\Entity\FileEntity;
use Drupal\migrate\Event\MigrateEvents;
use Drupal\migrate\Event\MigratePostRowSaveEvent;
use Drupal\migrate\Row;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\Core\Entity\EntityTypeManager;

/**
 * Class MigrationSubscriber.
 *
 * @package Drupal\agid_fixtures
 */
class MigrationSubscriber implements EventSubscriberInterface {

  /**
   * File entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $fileStorage;

  /**
   * MigrationSubscriber constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManager $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(EntityTypeManager $entity_type_manager) {
    $this->fileStorage = $entity_type_manager->getStorage('file');
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      MigrateEvents::POST_ROW_SAVE => ['onPostRowSave'],
    ];
  }

  /**
   * On post row save.
   *
   * This method is called whenever the migrate.post_row_save event is
   * dispatched.
   *
   * @param \Drupal\migrate\Event\MigratePostRowSaveEvent $event
   *   The Migrate post row save event.
   */
  public function onPostRowSave(MigratePostRowSaveEvent $event) {
    if ($event->getMigration()->migration_group != 'agid_fixtures_group_files') {
      return;
    }

    $row = $event->getRow();
    $file = $this->fileStorage->load($row->getSourceProperty('fid'));
    $this->setFieldValues($file, $row);
  }

  /**
   * Set File field values.
   *
   * @param \Drupal\file_entity\Entity\FileEntity $file
   *   A File entity instance.
   * @param \Drupal\migrate\Row $row
   *   A migration row object.
   */
  protected function setFieldValues(FileEntity $file, Row $row) {
    $updated = FALSE;
    foreach ($this->getFieldNames() as $field_name) {
      if (!$file->hasField($field_name) || !$row->hasDestinationProperty($field_name)) {
        continue;
      }

      $file->set($field_name, $row->getDestinationProperty($field_name));
      $updated = TRUE;
    }

    if ($updated) {
      $file->save();
    }
  }

  /**
   * Get field names.
   *
   * @return array
   *   An machine names array of file fields to be saved.
   */
  protected function getFieldNames() {
    return [
      'field_title',
      'field_description',
      'field_arguments',
      'field_original_file_source',
      'field_type',
    ];
  }

}
