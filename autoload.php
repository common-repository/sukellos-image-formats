<?php

\spl_autoload_register(function ($class) {
  static $map = array (
  'Sukellos\\WP_Sukellos_Image_Formats_Loader' => 'sukellos-image-formats.php',
  'Sukellos\\WP_Sukellos_Image_Formats' => 'class-wp-sukellos-image-formats.php',
  'Sukellos\\Admin\\WP_Sukellos_Image_Formats_Admin' => 'admin/class-wp-sukellos-image-formats-admin.php',
  'Sukellos\\Images_Manager' => 'includes/managers/class-images-manager.php',
);

  if (isset($map[$class])) {
    require_once __DIR__ . '/' . $map[$class];
  }
}, true, false);