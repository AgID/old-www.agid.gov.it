<?php

namespace Drupal\agid_search;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\search_api\Plugin\search_api\datasource\ContentEntity;

/**
 * Class IndexEntities.
 *
 * Used for index entities in batch.
 *
 * @package Drupal\agid_search
 */
class IndexEntities {

  /**
   * Index entities.
   *
   * @param $entity_type
   * @param $ids
   * @param $index_directly
   * @param $context
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   */
  public static function indexEntities($entity_type, $ids, $index_directly, &$context) {

    if (!isset($context['sandbox']['progress'])) {
      $context['sandbox']['progress'] = 0;
      $context['sandbox']['current_entity'] = 0;
      $context['sandbox']['max'] = count($ids);
    }

    $limit = 5;
    $ids_to_process = array_slice($ids, $context['sandbox']['progress'], $limit);
    $entity_storage = \Drupal::entityTypeManager()
      ->getStorage($entity_type);
    $entities = $entity_storage->loadMultiple($ids_to_process);

    if (empty($entities)) {
      $context['sandbox']['max'] = 0;
    }

    foreach ($entities as $entity) {

      // Update our progress information.
      $context['sandbox']['progress']++;
      $context['sandbox']['current_entity'] = $entity->label();
      $context['message'] = t('Now processing %current_entity', ['%current_entity' => $entity->label()]);

      // Index.
      $context['results'][] = self::index($entity, $index_directly);
    }

    // Inform the batch engine that we are not finished,
    // and provide an estimation of the completion level we reached.
    if ($context['sandbox']['progress'] != $context['sandbox']['max']) {
      $context['finished'] = $context['sandbox']['progress'] / $context['sandbox']['max'];
    }
  }

  /**
   * Callback finished for index.
   *
   * @param $success
   * @param $results
   * @param $operations
   */
  public static function indexEntitiesFinishedCallback($success, $results, $operations) {
    // The 'success' parameter means no fatal PHP errors were detected. All
    // other error management should be handled using 'results'.
    if ($success) {
      $message = \Drupal::translation()->formatPlural(
        count($results),
        'One entity processed.', '@count entity processed.'
      );
    }
    else {
      $message = t('Finished with an error.');
    }
    \Drupal::messenger()->addMessage($message);
  }

  /**
   * Updates the corresponding tracking table entries for each index that tracks
   * this entity.
   *
   * Also takes care of new or deleted translations.
   *
   * By setting the $entity->search_api_skip_tracking property to a true-like
   * value before this hook is invoked, you can prevent this behavior and make the
   * Search API ignore this update.
   *
   * Note that this function implements tracking only on behalf of the "Content
   * Entity" datasource defined in this module, not for entity-based datasources
   * in general. Datasources defined by other modules still have to implement
   * their own mechanism for tracking new/updated/deleted entities.
   *
   * @see \Drupal\search_api\Plugin\search_api\datasource\ContentEntity
   * @see search_api_entity_update()
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   * @param bool $index_directly
   *
   * @return bool
   */
  public static function index(EntityInterface $entity, $index_directly = FALSE) {
    // Check if the entity is a content entity.
    if (!($entity instanceof ContentEntityInterface) || $entity->search_api_skip_tracking) {
      return FALSE;
    }

    $indexes = ContentEntity::getIndexesForEntity($entity);
    if (!$indexes) {
      return FALSE;
    }

    // Compute the item IDs for all languages of the entity.
    $item_ids = [];
    $entity_id = $entity->id();
    foreach (array_keys($entity->getTranslationLanguages()) as $langcode) {
      $item_ids[] = $entity_id . ':' . $langcode;
    }
    $datasource_id = 'entity:' . $entity->getEntityTypeId();
    foreach ($indexes as $index) {

      // Force index directly.
      $index->setOption('index_directly', $index_directly);

      try {
        $filtered_item_ids = ContentEntity::filterValidItemIds($index, $datasource_id, $item_ids);
        $index->trackItemsInserted($datasource_id, $filtered_item_ids);
      }
      catch (\Exception $exception) {
        return FALSE;
      }
    }
    return TRUE;
  }

}
