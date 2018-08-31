<?php

/**
 * @file
 * Contains functions only needed for drush integration.
 *
 * Based on zen.drush.inc with modifications.
 */

/**
 * Implements hook_drush_command().
 */
function italiagov_drush_command() {
  $items = [];

  $items['italiagov-sub-theme'] = [
    'description' => 'Create an ItaliaGov sub-theme using command line. You must enable italiagov theme.',
    'aliases' => ['igst'],
    'arguments' => [
      'name' => 'The human readable name of sub-theme.',
      'machine_name' => '[optional] A machine-readable name of theme, only [a-z, 0-9].',
    ],
    'options' => [
      'name'         => 'The hyman readable name of your sub theme.',
      'machine-name' => '[a-z, 0-9, _] A machine-readable name for your theme.',
      'path'         => 'The path where your theme will be created. Defaults to: themes/custom',
      'description'  => 'Your sub-theme description.',
    ],
    'examples' => [
      'drush igst "My sub-theme"' => 'Create a sub-theme called "My sub-theme" with the default options (mysub_theme as machine name).',
      'drush igst "Theme human readable name" machinename  --description="My sub-theme description"' => 'Create a sub-theme specifying human readable name, machine name and theme description.',
      'drush igst "Theme human readable name" --path=themes --description="This is a theme."' => 'Create a sub-theme in the specified directory with a custom description.',
    ],
  ];

  return $items;
}

/**
 * Create an ItaliaGov sub-theme.
 */
function drush_italiagov_sub_theme($name = NULL, $machine_name = NULL, $description = NULL) {
  if (empty($name)) {
    drush_set_error('ITALIAGOV_SUBTHEME_NAME_NOT_SPECIFIED', dt("To create an ItaliaGov sub-theme, please provide at least a human readable name for it.\nUSAGE:\tdrush igst [name] [machine_name !OPTIONAL] [path !OPTIONAL] [description !OPTIONAL]\n"));
    return;
  }
  // Filter everything but letters, numbers, underscores, and hyphens.
  $machine_name = !empty($machine_name)
    ? preg_replace('/[^a-z0-9_-]+/', '', strtolower($machine_name))
    : preg_replace('/[^a-z0-9_-]+/', '', strtolower($name));
  // Eliminate hyphens.
  $machine_name = str_replace('-', '_', $machine_name);

  // Manage the description of the theme.
  $description = (!empty($description)) ? $description : 'Sub theme of ItaliaGov base theme';
  // Get path of the base theme.
  $base_theme_path = drush_get_context('DRUSH_DRUPAL_ROOT') . '/' . drupal_get_path('theme', 'italiagov');
  // Determine the path to the new sub-theme.
  $sub_theme_path = 'themes/custom';
  if ($path = drush_get_option('path')) {
    $sub_theme_path = drush_trim_path($path);
  }
  $sub_theme_path = drush_normalize_path(drush_get_context('DRUSH_DRUPAL_ROOT') . '/' . $sub_theme_path . '/' . $machine_name);

  drush_print(dt('Copying files from starter kit…'));
  // Allow localize.drupal.org to pick up the string to translate.
  if (FALSE) {
    t('Copying files from starter kit…');
  }

  // Make a copy of the subtheme.
  $copied = drush_copy_dir("$base_theme_path/STARTERKIT/", $sub_theme_path);
  if (empty($copied)) {
    return;
  }

  // Copy the build folder of the ita-web-toolkit.
  drush_print(dt('Copying ita-web-toolkit build files from base theme…'));
  // Allow localize.drupal.org to pick up the string to translate.
  if (FALSE) {
    t('Copying ita-web-toolkit build files from base theme…');
  }

  // Copy the build of the ita-web-toolkit and other files useful to make a
  // custom build of the toolkit itself in your subtheme.
  drush_op('xcopy', "$base_theme_path/build", "$sub_theme_path/build");
  drush_op('xcopy', "$base_theme_path/.bowerrc", "$sub_theme_path/.bowerrc");
  drush_op('xcopy', "$base_theme_path/bower.json", "$sub_theme_path/bower.json");
  drush_op('xcopy', "$base_theme_path/package.json", "$sub_theme_path/package.json");
  drush_op('xcopy', "$base_theme_path/gulpfile.js", "$sub_theme_path/gulpfile.js");

  $string_replacements = [
    'italiagov' => $name,
  ];
  // Do replacement in the JS files from italiagov to subtheme name.
  drush_op('italiagov_file_replace', $sub_theme_path . '/bower.json', $string_replacements);
  drush_op('italiagov_file_replace', $sub_theme_path . '/package.json', $string_replacements);

  // ***************************************************
  // Alter the contents of the .info.yml file.
  // ***************************************************.
  drush_print(dt('Updating the STARTERKIT.info.yml file…'));
  // Allow localize.drupal.org to pick up the string to translate.
  if (FALSE) {
    t('Updating the STARTERKIT.info.yml file…');
  }

  $info_strings = [
    ': ItaliaGov Sub-theme starter kit' => ': ' . $name,
    "hidden: true\n" => '',
  ];
  $info_strings['Ita-web-toolkit D8 starter theme.'] = $description;

  // Do replacement in the STARTERKIT.info.yml file.
  drush_op('italiagov_file_replace', $sub_theme_path . '/STARTERKIT.info.yml', $info_strings);

  // ***************************************************
  // Replace STARTERKIT in file names and contents.
  // ***************************************************.
  drush_print(dt('Replacing "STARTERKIT" in all files…'));
  // Allow localize.drupal.org to pick up the string to translate.
  if (FALSE) {
    t('Replacing "STARTERKIT" in all files…');
  }

  // Iterate through the sub-theme directory finding files to filter.
  $directoryIterator = new \RecursiveDirectoryIterator($sub_theme_path);
  $starterKitFilter = new \RecursiveCallbackFilterIterator($directoryIterator, function ($current, $key, $iterator) {
    // Skip hidden files and directories.
    if ($current->getFilename()[0] === '.') {
      return FALSE;
    }
    // Skip node_modules and the asset-builds folder.
    else {
      if ($current->getFilename() === 'node_modules' || $current->getFilename() === 'builds') {
        return FALSE;
      }
    }
    // Recursively go through all folders.
    if ($current->isDir()) {
      return TRUE;
    }
    else {
      // Only return Twig templates, files with "STARTERKIT" in their name,
      // README.md files and block.block.*.yml config files.
      return strpos($current->getFilename(), '.twig') !== FALSE ||
        strpos($current->getFilename(), 'STARTERKIT') !== FALSE ||
        strpos($current->getFilename(), 'README.md') !== FALSE ||
        strpos($current->getFilename(), 'block.block') !== FALSE;
    }
  });
  $iterator = new \RecursiveIteratorIterator($starterKitFilter);
  $sub_theme_files = [];
  foreach ($iterator as $path => $info) {
    $sub_theme_files[$info->getFilename()] = $path;
  }

  $sub_theme_files['iwt_settings.js'] = $sub_theme_path . '/js/iwt_settings.js';

  foreach ($sub_theme_files as $filename) {
    // Replace all occurrences of 'STARTERKIT' with the machine name of our
    // sub theme.
    drush_op('italiagov_file_replace', $filename, ['STARTERKIT' => $machine_name]);

    // Rename all files with STARTERKIT in their name.
    if (strpos($filename, 'STARTERKIT') !== FALSE) {
      drush_op('rename', $filename, str_replace('STARTERKIT', $machine_name, $filename));
    }
  }

  // Sub-theme created: notify user.
  $message = "\n!name sub-theme was successfully created in !path.";
  $message .= "\nTo install it, go to the \"Appearance\" settings section (admin/appearance).\n";
  drush_print(
    dt($message,
      [
        '!name' => $name,
        '!path' => $sub_theme_path,
      ]
    )
  );
}

/**
 * Replace strings in a file.
 */
function italiagov_file_replace($file_path, $strings, $regexs = []) {
  $file_path = drush_normalize_path($file_path);
  $file_contents = file_get_contents($file_path);

  if ($file_contents !== FALSE) {
    // Find text with strings.
    $find = array_keys($strings);
    $replace = $strings;
    $file_contents = str_replace($find, $replace, $file_contents);

    // Find text with regex.
    foreach ($regexs as $regex) {
      $file_contents = preg_replace($regex['pattern'], $regex['replacement'], $file_contents);
    }

    // Write to file.
    file_put_contents($file_path, $file_contents);
  }
}

/**
 * Implements hook_drush_help().
 */
function italiagov_drush_help($section) {
  switch ($section) {
    case 'drush:italiagov-sub-theme':
      return dt('Instructions to create an ItaliaGov sub-theme.');
  }
}

/**
 * Copy a file, or recursively copy a folder and its contents.
 *
 * @param string $source
 *   Source path.
 * @param string $dest
 *   Destination path.
 * @param int $permissions
 *   New folder creation permissions.
 *
 * @return bool
 *   Returns true on success, false on failure.
 *
 * @author Aidan Lister <aidan@php.net>
 * @version 1.0.1
 * @link http://aidanlister.com/2004/04/recursively-copying-directories-in-php
 */
function xcopy($source, $dest, $permissions = 0755) {
  // Check for symlinks.
  if (is_link($source)) {
    return symlink(readlink($source), $dest);
  }

  // Simple copy for a file.
  if (is_file($source)) {
    return copy($source, $dest);
  }

  // Make destination directory.
  if (!is_dir($dest)) {
    mkdir($dest, $permissions);
  }

  // Loop through the folder.
  $dir = dir($source);
  while (FALSE !== $entry = $dir->read()) {
    // Skip pointers.
    if ($entry == '.' || $entry == '..') {
      continue;
    }

    // Deep copy directories.
    xcopy("$source/$entry", "$dest/$entry", $permissions);
  }

  // Clean up.
  $dir->close();
  return TRUE;
}
