<?php

namespace Test\JP\Loader;

use JP\Loader\YamlBurner;
use JP\Loader\YamlBurnerVarMocker;
use PHPUnit\Framework\TestCase;

class YamlBurnerTest extends TestCase {

    use YamlBurnerVarMocker;

    public function yieldYamlVarsDataProvider(): \Generator {
        yield 'Test Load Vars - Given Linux Yaml Vars - Should match flatten vars' => [
            $mocker_method_for_yaml_vars = 'mockYamlVarsForLinux',
            $mocker_method_for_flatten_vars = 'mockFlattenVarsForLinux',
            $with_replacing_vars = [
                'domain' => 'mundialcard.com',
                'rootserver' => '/home/master/sandy/xdomains',
            ],
        ];
        yield 'Test Load Vars - Given Windows Yaml Vars - Should match flatten vars' => [
            $mocker_method_for_yaml_vars = 'mockYamlVarsForWindows',
            $mocker_method_for_flatten_vars = 'mockFlattenVarsForWindows',
            $with_replacing_vars = [
                'domain' => 'jplatz.com.br',
                'rootserver' => '/home/master/sandy/xdomains',
            ],
        ];
    }

    /** @dataProvider yieldYamlVarsDataProvider */
    public function testLoadVars_GivenYamlVars_ShouldMatchAccordingly(
        string $mocker_method_for_yaml_vars,
        string $mocker_method_for_flatten_vars,
        array $with_replacing_vars
    ): void {
        $given_yaml_vars = \Spyc::YAMLLoad(self::{$mocker_method_for_yaml_vars}());
        $expected_flatten_vars = self::{$mocker_method_for_flatten_vars}();

        YamlBurner::burn($given_yaml_vars, $with_replacing_vars);
        self::assertEquals($expected_flatten_vars, YamlBurner::getFlatten(), 'Should match Flatten Vars as expected');
    }

}
