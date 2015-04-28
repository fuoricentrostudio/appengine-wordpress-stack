<?php
use \google\appengine\api\modules\ModulesService as ModuleService;

$root_dir = dirname(__DIR__);
$webroot_dir = $root_dir . '/web';

/**
 * Set up our global environment constant
 * Default: development
 */
define('WP_ENV', (isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'],'Google App Engine') !== false) ? 'production' : 'development');

/**
 * Determine HTTP or HTTPS, then set WP_SITEURL and WP_HOME
 */
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443)
{
    $protocol_to_use = 'https://';
} else {
    $protocol_to_use = 'http://';
}

/**
 * URLs
 */
define( 'WP_SITEURL', $protocol_to_use . $_SERVER['HTTP_HOST']);
define( 'WP_HOME', $protocol_to_use . $_SERVER['HTTP_HOST']);

/**
 * Custom Content Directory
 */
define('CONTENT_DIR', '/app');
define('WP_CONTENT_DIR', $webroot_dir . CONTENT_DIR);
define('WP_CONTENT_URL', WP_HOME . CONTENT_DIR);

/**
 * DB settings
 */
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');
$table_prefix = getenv('DB_PREFIX') ?: 'wp_';

/**
 * Authentication Unique Keys and Salts
 */
define('AUTH_KEY', getenv('AUTH_KEY'));
define('SECURE_AUTH_KEY', getenv('SECURE_AUTH_KEY'));
define('LOGGED_IN_KEY', getenv('LOGGED_IN_KEY'));
define('NONCE_KEY', getenv('NONCE_KEY'));
define('AUTH_SALT', getenv('AUTH_SALT'));
define('SECURE_AUTH_SALT', getenv('SECURE_AUTH_SALT'));
define('LOGGED_IN_SALT', getenv('LOGGED_IN_SALT'));
define('NONCE_SALT', getenv('NONCE_SALT'));

/**
 * Custom Settings
 */
define('AUTOMATIC_UPDATER_DISABLED', true);
define('DISABLE_WP_CRON', true);
define('DISALLOW_FILE_EDIT', true);
define('FORCE_SSL_ADMIN', true );

/**
 *  Cache
 */
 define('WP_CACHE', true);
 define('WP_CACHE_KEY_SALT', ModuleService::getCurrentModuleName().'_'.ModuleService::getCurrentVersionName());

/**
 * Load environment config
 */
$env_config = __DIR__ . '/environments/' . WP_ENV . '.php';

if (file_exists($env_config)) {
  require_once $env_config;
} 
 
/**
 * Bootstrap WordPress
 */
if (!defined('ABSPATH')) {
  define('ABSPATH', $webroot_dir . '/wp/');
}
