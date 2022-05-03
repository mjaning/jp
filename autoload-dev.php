<?php

include_once __DIR__.'/vendor/autoload.php';

$classLoader = new \Composer\Autoload\ClassLoader();
$classLoader->addPsr4('', __DIR__, true);
$classLoader->register();

function autoload_dev($loading_namespace): bool {
    $namespace_pieces = explode('\\', $loading_namespace);
    $first = current($namespace_pieces);

    if ($first === '..') {
        return false;
    }

    ($first === 'Test')
        ? array_shift($namespace_pieces)
        : null;

    foreach(['./lib/','./test/_support/','./test/lib/'] as $prefix) {
        $filename = $prefix . implode(DIRECTORY_SEPARATOR, $namespace_pieces) . '.php';
        if (file_exists($filename)) {
            require_once($filename);
            return true;
        }
    }

    return false;
}

spl_autoload_register('autoload_dev');
