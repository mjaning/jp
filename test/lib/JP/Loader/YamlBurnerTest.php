<?php

namespace Test\JP\Loader;

use JP\Loader\YamlBurner;
use JP\Loader\YamlBurnerVarMocker;
use PHPUnit\Framework\TestCase;

class YamlBurnerTest extends TestCase {

    use YamlBurnerVarMocker;

    public function yieldYamlVarsDataProvider(): \Generator {
        yield 'Test Load Vars - Given Linux Yaml Vars - Should match flatten vars' => [
            $mockerMethodForYamlVars = 'mockYamlVarsForLinux',
            $mockerMethodForFlattenVars = 'mockFlattenVarsForLinux',
            $withReplacingVars = [
                'domain' => 'mundialcard.com',
                'rootserver' => '/home/master/sandy/xdomains',
            ],
        ];
        yield 'Test Load Vars - Given Windows Yaml Vars - Should match flatten vars' => [
            $mockerMethodForYamlVars = 'mockYamlVarsForWindows',
            $mockerMethodForFlattenVars = 'mockFlattenVarsForWindows',
            $withReplacingVars = [
                'domain' => 'jplatz.com.br',
                'rootserver' => '/home/master/sandy/xdomains',
            ],
        ];
    }

    /** @dataProvider yieldYamlVarsDataProvider */
    public function testLoadVars_GivenYamlVars_ShouldMatchAccordingly(
        string $mockerMethodForYamlVars,
        string $mockerMethodForFlattenVars,
        array $withReplacingVars
    ): void {
        $givenYamlVars = \Spyc::YAMLLoad(self::{$mockerMethodForYamlVars}());
        $expectedFlattenVars = self::{$mockerMethodForFlattenVars}();

        YamlBurner::burn($givenYamlVars, $withReplacingVars);
        self::assertEquals($expectedFlattenVars, YamlBurner::getFlatten(), 'Should match Flatten Vars as expected');
    }

}
