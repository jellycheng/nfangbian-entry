<?php
/**
 * 公共入口,适合web,cli
 */

require_once __DIR__ . '/App.php';

$entryApp = \nfangbian\entry\App::getInstance();
$entryApp->init(['nfangbian_path_root'=>'']);
#公共类,方法等加载
$entryApp->loadLibClass('EnvLoader');
//公共env
\nfangbian\entry\EnvLoader::load($entryApp->getConfig('nfangbian_path_config'), '.env');

//other todo

return $entryApp;
