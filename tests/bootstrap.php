<?php
declare(strict_types=1);

use Cake\Core\Configure;

require dirname(__DIR__) . '/vendor/autoload.php';

date_default_timezone_set('UTC');
mb_internal_encoding('UTF-8');
ini_set('intl.default_locale', 'en_US');

define('ROOT', dirname(__DIR__) . DS);
const CORE_PATH = ROOT . 'vendor' . DS . 'cakephp' . DS . 'cakephp' . DS;
const APP = ROOT . 'tests' . DS . 'test_app' . DS;
const APP_DIR = 'test_app' . DS;
const WWW_ROOT = APP . 'webroot' . DS;

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
