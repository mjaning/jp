<?php

namespace JP\Loader;

class AutoLoader {

    private const MATCHING_WITH_ROOT_FOLDER_METHOD = 'matchingWithRootFolder';
    private const MATCHING_WITH_FILE_EXTENSION_METHOD = 'matchingWithFileExtension';
    private const REQUIRING_PHYSICAL_FILE_METHOD = 'requiringPhysicalFile';

    private const MATCHING_METHODS_ALLOWED = [
        self::MATCHING_WITH_ROOT_FOLDER_METHOD,
        self::MATCHING_WITH_FILE_EXTENSION_METHOD,
        self::REQUIRING_PHYSICAL_FILE_METHOD,
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

        $match = $match ?: self::matchingWithRootFolder($loadingPath, self::MATCHING_WITH_FILE_EXTENSION_METHOD);
        $match = $match ?: self::matchingWithRootFolder($loadingPath);
        $match = $match ?: self::matchingWithFileExtension($loadingPath);
        $match = $match ?: self::requiringPhysicalFile($loadingPath);

        return $match;
    }

    private static function matchingWithRootFolder(string $loadingPath, ?string $cascadingMatch = null): bool {
        $checkingMethod = in_array($cascadingMatch, self::MATCHING_METHODS_ALLOWED)
            ? $cascadingMatch
            : self::REQUIRING_PHYSICAL_FILE_METHOD;

        $match = false;
        foreach (self::$REGISTERED_ROOT_FOLDERS as $rootFolder) {
            $match = $match ?: self::{$checkingMethod}($rootFolder . $loadingPath);
        }

        return $match;
    }

    private static function matchingWithFileExtension(string $loadingPath, ?string $cascadingMatch = null): bool {
        $checkingMethod = in_array($cascadingMatch, self::MATCHING_METHODS_ALLOWED)
            ? $cascadingMatch
            : self::REQUIRING_PHYSICAL_FILE_METHOD;

        $match = false;
        foreach (self::$REGISTERED_FILE_EXTENSIONS as $fileExtension) {
            $match = $match ?: self::{$checkingMethod}($loadingPath . $fileExtension);
        }

        return $match;
    }

    private static function requiringPhysicalFile(string $matchingFilePath): bool {
        if (file_exists($matchingFilePath)) {
            require_once($matchingFilePath);
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
