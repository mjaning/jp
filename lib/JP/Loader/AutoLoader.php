<?php

namespace JP\Loader;

class AutoLoader {

    private const MATCH_ROOT_FOLDERS_METHOD = 'matchRootFolders';
    private const MATCH_FILE_EXTENSIONS_METHOD = 'matchFileExtensions';
    private const REQUIRE_PHYSICAL_FILE_METHOD = 'requirePhysicalFile';

    private const MATCH_METHODS_ALLOWED = [
        self::MATCH_ROOT_FOLDERS_METHOD,
        self::MATCH_FILE_EXTENSIONS_METHOD,
        self::REQUIRE_PHYSICAL_FILE_METHOD,
    ];

    private const DIRECTORY_SYMBOL = '..';

    private static array $REGISTERED_FILE_EXTENSIONS = [];
    private static array $REGISTERED_ROOT_FOLDERS = [];

    private function __construct() {}

    public static function register(): void {
        spl_autoload_register('self::autoLoader');
    }

    public static function autoLoader(string $loadingNamespace): bool {
        $loadingPath = str_replace('\\', DIRECTORY_SEPARATOR, $loadingNamespace);

        switch (true) {
            case str_starts_with($loadingPath, self::DIRECTORY_SYMBOL):
                return false;
            case str_starts_with($loadingPath, 'Test'):
                array_shift($loadingPath);
                break;
            default:
                break;
        }

        return self::matchAndRequireLoadingPath($loadingPath);
    }

    public static function configFileExtensions(array $fileExtensions): void {
        foreach ($fileExtensions as $fileExtension) {
            self::addFileExtension($fileExtension);
        }
    }

    public static function configRootFolders(array $rootFolders): void {
        foreach ($rootFolders as $rootFolder) {
            self::addRootFolder($rootFolder);
        }
    }

    private static function matchAndRequireLoadingPath(string $loadingPath): bool {
        $match = false;

        $match = $match ?: self::matchRootFolders($loadingPath, self::MATCH_FILE_EXTENSIONS_METHOD);
        $match = $match ?: self::matchRootFolders($loadingPath);
        $match = $match ?: self::matchFileExtensions($loadingPath);
        $match = $match ?: self::requirePhysicalFile($loadingPath);

        return $match;
    }

    private static function matchRootFolders(string $loadingPath, ?string $cascadeMatch = null): bool {
        $matchMethod = in_array($cascadeMatch, self::MATCH_METHODS_ALLOWED)
            ? $cascadeMatch
            : self::REQUIRE_PHYSICAL_FILE_METHOD;

        $match = false;
        foreach (self::$REGISTERED_ROOT_FOLDERS as $rootFolder) {
            $match = $match ?: self::{$matchMethod}($rootFolder . $loadingPath);
        }

        return $match;
    }

    private static function matchFileExtensions(string $loadingPath, ?string $cascadeMethod = null): bool {
        $matchMethod = in_array($cascadeMethod, self::MATCH_METHODS_ALLOWED)
            ? $cascadeMethod
            : self::REQUIRE_PHYSICAL_FILE_METHOD;

        $match = false;
        foreach (self::$REGISTERED_FILE_EXTENSIONS as $fileExtension) {
            $match = $match ?: self::{$matchMethod}($loadingPath . $fileExtension);
        }

        return $match;
    }

    private static function requirePhysicalFile(string $matchFilePath): bool {
        if (file_exists($matchFilePath)) {
            require_once($matchFilePath);
            return true;
        }

        return false;
    }

    private static function addRootFolder(string $rootFolder): void {
        !in_array($rootFolder, self::$REGISTERED_ROOT_FOLDERS, true)
            ? self::$REGISTERED_ROOT_FOLDERS[] = $rootFolder
            : null;
    }

    private static function addFileExtension(string $fileExtension): void {
        !in_array($fileExtension, self::$REGISTERED_FILE_EXTENSIONS, true)
            ? self::$REGISTERED_FILE_EXTENSIONS[] = $fileExtension
            : null;
    }

}
