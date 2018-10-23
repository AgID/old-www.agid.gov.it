<?php

namespace Drupal\agid_blocks;

/**
 * Class AgidBlocks.
 *
 * @package Drupal\agid_blocks
 */
final class AgidBlocks {

  /**
   * Field name in PagoPA Content Type that holds the fiscal code.
   */
  const FIELD_PAGOPA_FISCAL_CODE = 'field_fiscal_code';

  /**
   * Field name in PagoPA Content Type that refers to PagoPA Type Taxonomy.
   */
  const FIELD_PAGOPA_TYPE = 'field_pagopa_type';

  /**
   * View to show PagoPA Enti Creditori informations related to a fiscal code.
   */
  const VIEW_PAGOPA_INFO = 'pagopa_enti_creditori_codice_fiscale';

  /**
   * Display mode to use for the PagoPA Enti Creditori view.
   */
  const VIEW_PAGOPA_INFO_DISPLAY = 'block_1';

  /**
   * The machine name of the pagopa type taxonomy.
   */
  const TAXONOMY_PAGOPA_TYPE = "pagopa_type";


  const VIEW_RELATED_CONTENT_ALLOWED = [
    'page' => 'field_arguments',
    'news' => 'field_arguments'
  ];

  const VIEW_RELATED_FAQ = 'related_frequently_asked_question';

  const VIEW_RELATED_FAQ_DISPLAY = 'block_1';

  const VIEW_RELATED_NEWS = 'related_news';

  const VIEW_RELATED_NEWS_DISPLAY = 'block_1';

}
