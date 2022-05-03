<?php

namespace JP\Loader;

class YamlBurner {

    private const MACRO_REGEX_PATTERN = '/%(.*?)%/';

	private static array $BURNED_VARS;

	private function __construct() {}

    public static function burn(array $loaded_vars, array $replacing_vars = []): array {
        self::$BURNED_VARS = $loaded_vars;

        self::replacingVars(self::$BURNED_VARS, $replacing_vars);
        self::cascadingMacroVars(self::$BURNED_VARS);

        return self::$BURNED_VARS;
    }

    public static function getBurned(): array {
        return self::$BURNED_VARS;
    }

    public static function getFlatten(): array {
        return self::flatten_array(self::$BURNED_VARS);
    }

    private static function replacingVars(array &$target, array $replacing_vars): void {
        if (empty($replacing_vars)) {
            return;
        }
        array_walk($target, static function (&$value, $key) use ($replacing_vars) {
            (is_object($value) || is_array($value))
                ? self::replacingVars($value, $replacing_vars)
                : (array_key_exists($key, $replacing_vars) ? $value = $replacing_vars[$key] : null);
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
        $burned_vars = self::flatten_array(self::$BURNED_VARS);

        [
            'macros' => $macros,
            'keys' => $keys
        ] = self::grabMacroMatches($content);

		foreach($macros as $i => $macro) {
            $key = $keys[$i];
            (array_key_exists($key, $burned_vars))
                ? $content = str_replace($macro, $burned_vars[$key], $content)
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
