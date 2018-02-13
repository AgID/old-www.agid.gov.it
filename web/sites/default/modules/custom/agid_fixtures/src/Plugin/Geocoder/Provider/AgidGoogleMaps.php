<?php

namespace Drupal\agid_fixtures\Plugin\Geocoder\Provider;

use Drupal\geocoder\Plugin\Geocoder\Provider\GoogleMaps;

/**
 * Provides a GoogleMaps geocoder provider plugin.
 *
 * @GeocoderProvider(
 *   id = "agid_googlemaps",
 *   name = "GoogleMaps - AGID",
 *   handler = "\Geocoder\Provider\GoogleMaps",
 *   arguments = {
 *     "locale",
 *     "region",
 *     "useSsl" = TRUE,
 *     "apiKey" = "AIzaSyC1txLe4iL4pC8lSnx7cckT1bvPpB6YVEI"
 *   }
 * )
 */
class AgidGoogleMaps extends GoogleMaps {}
