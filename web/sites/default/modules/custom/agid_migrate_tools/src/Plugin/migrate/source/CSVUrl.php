<?php

namespace Drupal\agid_migrate_tools\Plugin\migrate\source;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\migrate\MigrateException;
use Drupal\migrate_source_csv\Plugin\migrate\source\CSV;
use Drupal\migrate\Plugin\MigrationInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\File\FileSystemInterface;

/**
 * Source for CSV from Url.
 *
 * This plugin retrieves CSV data from the specified URL
 * and then uses the "csv" plugin to process it.
 *
 * @MigrateSource(
 *   id = "csv_url"
 * )
 */
class CSVUrl extends CSV implements ContainerFactoryPluginInterface {

  /**
   * The URL where we can find the CSV file.
   *
   * @var string
   */
  protected $url = NULL;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration, FileSystemInterface $fileSystemManager) {

    // URL is required.
    if (empty($configuration['url'])) {
      throw new MigrateException('You must declare the "url" to the source CSV file in your source settings.');
    }

    $this->url = $configuration['url'];

    // Retrieve data from URL.
    $data = file_get_contents($this->url);
    if ($data === FALSE) {
      throw new MigrateException("Unable to retrieve data from url '{$this->url}'.");
    }

    $pathinfo = pathinfo($this->url);
    $filename = $pathinfo['basename'];

    // Save data to temporary dir.
    $file = file_save_data($data, "temporary://{$filename}");
    if ($file === FALSE) {
      throw new MigrateException("Unable to save file to 'temporary://{$filename}'.");
    }

    $path = $fileSystemManager->realpath($file->getFileUri());
    if ($path === FALSE) {
      throw new MigrateException("Unable to retrieve absolute path for file '" . $file->getFileUri() . "'.");
    }

    $configuration['path'] = $path;

    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration = NULL) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $migration,
      $container->get('file_system')
    );
  }

}
