<?php
/* Production */
define('DB_NAME', getenv('DB_NAME'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');

ini_set('display_errors', 0);
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', false);
define('DISALLOW_FILE_MODS', true); // this disables all file modifications including updates and update notifications

$batcache = [
  'seconds'=>0,
  'max_age'=>30*60, // 30 minutes
  'group'=>'batcache_'. ( defined(WP_CACHE_KEY_SALT) ? WP_CACHE_KEY_SALT : '' ),
  'debug'=>false
];
