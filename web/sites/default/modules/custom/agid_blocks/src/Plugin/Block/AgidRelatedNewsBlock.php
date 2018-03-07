<?php

namespace Drupal\agid_blocks\Plugin\Block;

use Drupal\agid_blocks\AgidBlocks;
use Drupal\agid_blocks\AgidRelatedContentBlock;
use Drupal\views\Entity\View;
use Drupal\views\ViewExecutable;

/**
 * Provides a 'AgidRelatedNewsBlock' block.
 *
 * @Block(
 *  id = "agid_related_news_block",
 *  admin_label = @Translation("AGID - Related News"),
 * )
 */
class AgidRelatedNewsBlock extends AgidRelatedContentBlock {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];

    /** @var View $view */
    $view = $this->entityTypeManager->getStorage('view')->load(AgidBlocks::VIEW_RELATED_NEWS);

    /** @var ViewExecutable $viewExecutable */
    $viewExecutable = $this->viewExecutableFactory->get($view);

    $viewExecutable->executeDisplay(AgidBlocks::VIEW_RELATED_NEWS_DISPLAY,
      [$this->taxonomy_ids, $this->node->id()]);

    if (count($viewExecutable->result) > 0) {
      $build['agid_related_news_block'] = $viewExecutable->buildRenderable(AgidBlocks::VIEW_RELATED_NEWS_DISPLAY, 
        [$this->taxonomy_ids, $this->node->id()]);
    }

    return $build;
  }

}
