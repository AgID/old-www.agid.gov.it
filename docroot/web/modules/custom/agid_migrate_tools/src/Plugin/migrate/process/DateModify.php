<?php

namespace Drupal\agid_migrate_tools\Plugin\migrate\process;

use Drupal\migrate\MigrateException;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Modifies date/datetime from one format by a date modifier.
 *
 * Available configuration keys
 * - format: The source format string as accepted by
 * @link http://php.net/manual/datetime.createfromformat.php \DateTime::createFromFormat. @endlink
 * - modifier: The date modifier string as accepted by
 * @link http://php.net/manual/en/datetime.modify.php \DateTime::modify. @endlink.
 *
 * Examples:
 *
 * Example usage for date only fields (DATETIME_DATE_STORAGE_FORMAT):
 * @code
 * process:
 *   field_date:
 *     plugin: date_modify
 *     format: 'm/d/Y'
 *     modifier: '+1 day'
 *     source: event_date
 * @endcode
 *
 * If the source value was '01/05/1955' the modified value would be '02/05/1955.
 *
 * Example usage for datetime fields (DATETIME_DATETIME_STORAGE_FORMAT):
 * @code
 * process:
 *   field_time:
 *     plugin: date_modify
 *     format: 'm/d/Y H:i:s'
 *     modifier: '-2 hours'
 *     source: event_time
 * @endcode
 *
 * If the source value was '01/05/1955 10:43:22' the modified value would be
 * '01/05/1955 08:43:22'.
 *
 * Example usage with timestamp input:
 * @code
 * process:
 *   field_time:
 *     plugin: date_modify
 *     format: 'm/d/Y H:i:s'
 *     is_timestamp: true # OPTIONAL
 *     source: date_timestamp
 * @endcode
 *
 * If the source value is a valid timestamp the output value would be a date
 * in the format: m/d/Y H:i:s', in example:
 * "1491995708" -> "2017-04-12T13:15:08"
 *
 * @see \DateTime::createFromFormat()
 * @see \DateTime::modify()
 * @see \Drupal\migrate\Plugin\MigrateProcessInterface
 *
 * @MigrateProcessPlugin(
 *   id = "date_modify"
 * )
 */
class DateModify extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (empty($value)) {
      return '';
    }

    // Validate the configuration.
    if (empty($this->configuration['format'])) {
      throw new MigrateException('Date modify plugin is missing format configuration.');
    }
    if (empty($this->configuration['modifier'])) {
      throw new MigrateException('Date modify plugin is missing modifier configuration.');
    }
    $format = $this->configuration['format'];
    $modifier = $this->configuration['modifier'];

    // Convert a timestamp value in a date string using the input format.
    if (!empty($this->configuration['is_timestamp']) && $this->configuration['is_timestamp'] === TRUE) {
      $value = date($format, $value);
    }

    // Attempts to modify the supplied date using the defined input format.
    // DateTime::createFromFormat can throw exceptions, so we need to
    // explicitly check for problems.
    try {
      $modified = \DateTime::createFromFormat($format, $value)
        ->modify($modifier)
        ->format($format);
    }
    catch (\InvalidArgumentException $e) {
      throw new MigrateException(sprintf('Date modify plugin could not modify "%s" using the modifier "%s". Error: %s', $value, $modifier, $e->getMessage()), $e->getCode(), $e);
    }
    catch (\UnexpectedValueException $e) {
      throw new MigrateException(sprintf('Date modify plugin could not modify "%s" using the modifier "%s". Error: %s', $value, $modifier, $e->getMessage()), $e->getCode(), $e);
    }

    return $modified;
  }

}
