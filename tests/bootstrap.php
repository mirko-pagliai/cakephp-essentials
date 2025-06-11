<?php
declare(strict_types=1);

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Log\Engine\FileLog;
use Cake\Log\Log;

require dirname(__DIR__) . '/vendor/autoload.php';

date_default_timezone_set('UTC');
mb_internal_encoding('UTF-8');
ini_set('intl.default_locale', 'en_US');

define('ROOT', dirname(__DIR__) . DS);
const CORE_PATH = ROOT . 'vendor' . DS . 'cakephp' . DS . 'cakephp' . DS;
const APP = ROOT . 'tests' . DS . 'test_app' . DS;
const APP_DIR = 'test_app' . DS;
const WWW_ROOT = APP . 'webroot' . DS;
define('TMP', sys_get_temp_dir() . DS . 'cakephp-essentials' . DS);
const LOGS = TMP . 'logs' . DS;

// phpcs:disable
@mkdir(TMP, 0777, true);
@mkdir(LOGS, 0777, true);
// phpcs:enable

putenv('APP_DEFAULT_LOCALE=en_US');

require_once CORE_PATH . 'config' . DS . 'bootstrap.php';

Configure::write('debug', true);
Configure::write('App', [
    'namespace' => 'App',
    'encoding' => 'UTF-8',
    'base' => false,
    'baseUrl' => false,
    'dir' => APP_DIR,
    'webroot' => 'webroot',
    'wwwRoot' => WWW_ROOT,
    'fullBaseUrl' => 'http://localhost',
    'imageBaseUrl' => 'img/',
    'jsBaseUrl' => 'js/',
    'cssBaseUrl' => 'css/',
]);

Cache::setConfig([
    '_cake_translations_' => [
        'engine' => 'File',
        'prefix' => 'cake_core_',
        'serialize' => true,
    ],
]);

Log::setConfig('error', [
    'engine' => FileLog::class,
    'levels' => ['warning', 'error', 'critical', 'alert', 'emergency'],
    'file' => 'error',
    'path' => LOGS,
]);
