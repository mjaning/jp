<?php

include_once __DIR__.'/vendor/autoload.php';

$classLoader = new \Composer\Autoload\ClassLoader();
$classLoader->addPsr4('', __DIR__, true);
$classLoader->register();

function autoload_dev($class_name): bool {
    $split = explode('\\', $class_name);
    $first = current($split);
    if ($first === '..') {
        return false;
    }

    $clip = ($first === 'Test')
        ? array_shift($split)
        : null;

    foreach(['./lib/','./test/_support/','./test/lib/'] as $prefix) {
        $filename = $prefix . implode(DIRECTORY_SEPARATOR, $split) . '.php';
        if (file_exists($filename)) {
            require_once($filename);
            return true;
        }
    }

    return false;
}

spl_autoload_register('autoload_dev');
