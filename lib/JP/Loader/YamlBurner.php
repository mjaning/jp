<?php

namespace JP\Loader;

class YamlBurner {

    private const MACRO_REGEX_PATTERN = '/%(.*?)%/';

	private static array $BURNED_VARS;

	private function __construct() {}

    public static function burn(array $loadedVars, array $replacingVars = []): array {
        self::$BURNED_VARS = $loadedVars;

        self::replacingVars(self::$BURNED_VARS, $replacingVars);
        self::cascadingMacroVars(self::$BURNED_VARS);

        return self::$BURNED_VARS;
    }

    public static function getBurned(): array {
        return self::$BURNED_VARS;
    }

    public static function getFlatten(): array {
        return self::flatten_array(self::$BURNED_VARS);
    }

    private static function replacingVars(array &$target, array $replacingVars): void {
        if (empty($replacingVars)) {
            return;
        }
        array_walk($target, static function (&$value, $key) use ($replacingVars) {
            (is_object($value) || is_array($value))
                ? self::replacingVars($value, $replacingVars)
                : (array_key_exists($key, $replacingVars) ? $value = $replacingVars[$key] : null);
        });
    }

    private static function cascadingMacroVars(array &$target): void {
        array_walk($target, static function (&$value) {
            (is_object($value) || is_array($value))
                ? self::cascadingMacroVars($value)
                : $value = self::macroReplace($value);
        });
    }

    private static function macroReplace(string $content): string {
        $burnedVars = self::flatten_array(self::$BURNED_VARS);

        [
            'macros' => $macros,
            'keys' => $keys
        ] = self::grabMacroMatches($content);

		foreach($macros as $i => $macro) {
            $key = $keys[$i];
            (array_key_exists($key, $burnedVars))
                ? $content = str_replace($macro, $burnedVars[$key], $content)
                : null;
		}

		return $content;
	}

    private static function grabMacroMatches(string $content): array {
        preg_match_all(self::MACRO_REGEX_PATTERN, $content, $matches);
        return [
            'macros' => $matches[0],
            'keys' => $matches[1],
        ];
    }

    private static function flatten_array(array $target): array {
        return iterator_to_array(
            new \RecursiveIteratorIterator(new \RecursiveArrayIterator($target))
        );
    }

}
