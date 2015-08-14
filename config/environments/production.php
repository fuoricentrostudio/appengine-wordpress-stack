<?php
/* Production */
define('DB_NAME', getenv('DB_NAME'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');

ini_set('display_errors', 0);
define('SAVEQUERIES', false);
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', false);
define('DISALLOW_FILE_MODS', true); // this disables all file modifications including updates and update notifications

define('WP_POST_REVISIONS', 3);

/**
 *  Cache
 */
 //define('WP_CACHE_KEY_SALT', ModuleService::getCurrentModuleName().'_'.ModuleService::getCurrentVersionName());
define('WP_CACHE', true);
