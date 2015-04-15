<?php
/* Development */
define('DB_NAME', getenv('DB_NAME'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DB_HOST', getenv('DB_HOST') ? getenv('DB_HOST') : 'localhost');

define('SAVEQUERIES', true);
define('WP_DEBUG', true);
define('SCRIPT_DEBUG', true);

$batcache = [
  'seconds'=>0,
  'max_age'=>30*60, // 30 minutes
  'group'=>'batcache_'.WP_CACHE_KEY_SALT,
  'debug'=>true
];