<?php

namespace Drupal\agid_blocks\Plugin\Block;

use Drupal\agid_blocks\AgidBlocks;
use Drupal\agid_blocks\AgidRelatedContentBlock;
use Drupal\views\Entity\View;
use Drupal\views\ViewExecutable;

/**
 * Provides a 'AgidRelatedFAQBlock' block.
 *
 * @Block(
 *  id = "agid_related_faq_block",
 *  admin_label = @Translation("AGID - Related FAQ"),
 * )
 */
class AgidRelatedFAQBlock extends AgidRelatedContentBlock {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];

    /** @var View $view */
    $view = $this->entityTypeManager->getStorage('view')->load(AgidBlocks::VIEW_RELATED_FAQ);

    /** @var ViewExecutable $viewExecutable */
    $viewExecutable = $this->viewExecutableFactory->get($view);

    $viewExecutable->executeDisplay(AgidBlocks::VIEW_RELATED_FAQ_DISPLAY, [$this->taxonomy_ids]);

    if (count($viewExecutable->result) > 0) {
      $build['agid_related_faq_block'] = $viewExecutable->buildRenderable(AgidBlocks::VIEW_RELATED_FAQ_DISPLAY, [$this->taxonomy_ids]);
    }

    return $build;
  }

}
