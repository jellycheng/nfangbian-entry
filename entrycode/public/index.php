<?php
/**
 * nginx root
 */
define("NFANGBIAN_ENTRY_TYPE", 'web');
$entryApp  = require dirname(__DIR__) . '/lib/common.php';
$appRoot   = $entryApp->getProjectcodePath(); //所有项目根目录前缀，.env中配置  如 /data1/www/projectcode
$appName   = $entryApp->getAppName(); //nginx传来的项目名
$cloudName = $entryApp->getCloudName(); //开发者
$appItemDir = $cloudName?$cloudName . DIRECTORY_SEPARATOR . $appName : $appName;//开发者 + 项目名

//env init
$envDir = $entryApp->getConfig('nfangbian_path_config') . $entryApp->environment() . DIRECTORY_SEPARATOR . $appName . DIRECTORY_SEPARATOR;
\nfangbian\entry\EnvLoader::load($envDir, '.env');
if($cloudName) {
    \nfangbian\entry\EnvLoader::load($entryApp->getConfig('nfangbian_path_config') . $entryApp->environment() . DIRECTORY_SEPARATOR . $cloudName . DIRECTORY_SEPARATOR . $appName . DIRECTORY_SEPARATOR, '.env');
}

//other todo  如ip白名单等

$fileIndex = sprintf('%s%s/%s/public/index.php', $appRoot, $entryApp->environment(), $appItemDir);
if (file_exists($fileIndex)) {
    include $fileIndex;
} else {
    $fileIndex2 = sprintf('%s%s/public/index.php', $appRoot, $appItemDir);
    if(file_exists($fileIndex2)) {
        include $fileIndex2;
    } else {
        exit("file not exists: " . $fileIndex . PHP_EOL . $fileIndex2 . PHP_EOL);
    }

}

