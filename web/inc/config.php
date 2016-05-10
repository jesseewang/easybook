<?php
/**
 * System default setting
 * User: Jessee
 * Date: 2015/11/2
 * Time: 18:14
 */

// Database setting
define("DB_HOST", 'localhost');
define("DB_USER", 'book');
define("DB_PASSWORD", 'mysql');
define("DATABASE", 'easybook');

// Source host setting
define("SOURCE_HOST", 'http://www.book-list.com');

// Log files setting
define("LOG_DIR", '/opt/book/base/logs');
define("DETECTOR_LOG", 'detector_'.date('Y-m-d',time()));
define("WORKER_LOG", 'worker_'.date('Y-m-d',time()));

// Project path
define("BASE_PATH", '/opt/www/book/web/');
define("TEMPLATE_PATH", BASE_PATH.'templates');
define("COMPILE_PATH", BASE_PATH.'templates_c');
define("CACHE_PATH", BASE_PATH.'cache');
define("CONFIG_PATH", BASE_PATH.'configs');
define("EXPORT_PATH", BASE_PATH.'export');

$db = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DATABASE);

$smarty = new Smarty();
$smarty->setTemplateDir(TEMPLATE_PATH);
$smarty->setCompileDir(COMPILE_PATH);
$smarty->setCacheDir(CACHE_PATH);
$smarty->setConfigDir(CONFIG_PATH);
$smarty->debugging = false;
$smarty->caching = false;
$smarty->cache_lifetime = 120;
