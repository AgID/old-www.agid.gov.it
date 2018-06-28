<?php

namespace Drupal\agid_search\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;
use Drupal\views\Entity\View;

/**
 * Provides a 'SimpleFullTextSearchBlock' block.
 *
 * @Block(
 *  id = "agid_search_simple_fulltext_search",
 *  admin_label = @Translation("Simple FullText Search Block"),
 * )
 */
class SimpleFullTextSearchBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build['agid_search_simple_box_block'] = [
      '#theme' => 'agid_search_simple_full_text_search',
      '#view_uri' => Url::fromRoute('view.search_site.page')->toString(),
      '#cache' => [
        'tags' => View::load('search_site')->getCacheTags(),
      ],
    ];
    return $build;
  }

}
