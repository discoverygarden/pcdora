<?php

/**
 * @file
 * Script to delete media and associated file.
 *
 * Usage: drush php:script delete_media_file_dry_run.php "fid1,fid2,fid3"
 */

use Drupal\file\Entity\File;

$utils = \Drupal::service('islandora.utils');

// Get command line arguments
// $extra is used by drush scr command
// For drush php:script, use $_SERVER['argv'].
$input = $extra[0] ?? $_SERVER['argv'][1] ?? '';

if (empty($input)) {
  echo "Error: No input parameters provided.\n";
  echo "Usage: drush php:script delete_media_file_dry_run.php \"fid1,fid2,fid3\"\n";
  exit(1);
}

// Parse comma-separated string into an array.
$fids = array_map('trim', explode(',', $input));

// Remove empty values.
$fids = array_filter($fids);

if (empty($fids)) {
  echo "Error: No valid file IDs provided.\n";
  exit(1);
}

echo "Dry run -- processing " . count($fids) . " file(s)...\n\n";

foreach ($fids as $fid) {
  echo "--- Processing file: {$fid} ---\n";
  $file = File::load($fid);

  if (!$file) {
    echo "Warning: Could not load file {$fid}\n";
    continue;
  }

  if (count($utils->getReferencingMedia($fid)) > 1) {
    echo "WARNING: File {$fid} has more than one media referenceing it, please investigate further. Skipping...\n";
    continue;
  }
  foreach ($utils->getReferencingMedia($fid) as $media) {
    if ($media) {
      echo "Dry run - prod run will delete media ID: " . $media->id() . "\n";
    }
  }
  echo "Dry run - prod run will delete file ID: " . $file->id() . "\n";
}

echo "\n";

echo "Processing complete.\n";
