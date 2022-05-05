<?php

include_once __DIR__ . '/vendor/autoload.php';

$classLoader = new \Composer\Autoload\ClassLoader();
$classLoader->addPsr4('', __DIR__, true);
$classLoader->register();

require_once __DIR__ . '/lib/JP/Loader/AutoLoader.php';
\JP\Loader\AutoLoader::configFileExtensions(['.php']);
\JP\Loader\AutoLoader::configRootFolders(['./lib/', './test/lib/', './test/_support/']);
\JP\Loader\AutoLoader::register();

