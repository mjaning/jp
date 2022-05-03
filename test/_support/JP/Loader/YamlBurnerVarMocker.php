<?php

namespace JP\Loader;

trait YamlBurnerVarMocker {

    private static function mockYamlVarsForWindows(): string {
        return <<<YAML
black-VAIO:
  domain: mundialshare.com
  home: http://local.mundialshare.com/core/auth/signup/
  rootserver: C:\\Users\\black\\xdomains
  rootdomain: %rootserver%/%domain%

  .application:
    apps: %rootdomain%/apps
    config: %rootdomain%/config
    api: %rootdomain%/api
    lib: %rootdomain%/lib
    class: %lib%/class
    web: /.
    css: %web%/css
    js: %web%/js
    img: %web%/img

  .dependency:
    shared: %rootserver%/shared
    assets: %shared%/assets
    phpmailer: %shared%/phpmailer
    vendors: %shared%/vendors
    jp: %vendors%/jp
    jquery: %vendors%/jquery
    spyc: %vendors%/spyc
YAML;
    }

    private function mockFlattenVarsForWindows(): array {
        return [
            'domain' => 'jplatz.com.br',
            'home' => 'http://local.mundialshare.com/core/auth/signup/',
            'rootserver' => '/home/master/sandy/xdomains',
            'rootdomain' => '/home/master/sandy/xdomains/jplatz.com.br',
            'apps' => '/home/master/sandy/xdomains/jplatz.com.br/apps',
            'config' => '/home/master/sandy/xdomains/jplatz.com.br/config',
            'api' => '/home/master/sandy/xdomains/jplatz.com.br/api',
            'lib' => '/home/master/sandy/xdomains/jplatz.com.br/lib',
            'class' => '/home/master/sandy/xdomains/jplatz.com.br/lib/class',
            'web' => '/.',
            'css' => '/./css',
            'js' => '/./js',
            'img' => '/./img',
            'shared' => '/home/master/sandy/xdomains/shared',
            'assets' => '/home/master/sandy/xdomains/shared/assets',
            'phpmailer' => '/home/master/sandy/xdomains/shared/phpmailer',
            'vendors' => '/home/master/sandy/xdomains/shared/vendors',
            'jp' => '/home/master/sandy/xdomains/shared/vendors/jp',
            'jquery' => '/home/master/sandy/xdomains/shared/vendors/jquery',
            'spyc' => '/home/master/sandy/xdomains/shared/vendors/spyc',
        ];
    }

    private function mockYamlVarsForLinux(): string {
        return <<<YAML
br304.hostgator.com.br:
  domain: mundialshare.com
  homepage: /core/auth/signup/

  root: /home/jplat726
  project: %root%/ms

  # web area
  web: /web
  css: %web%/css
  js: %web%/js
  img: %web%/img

  .application:
    bundles: %project%/bundles
    config: %project%/config
    api: %project%/api
    lib: %project%/lib
    class: %lib%/class

  .dependency:
    shared: %root%/shared
    assets: %shared%/assets
    mailer: %assets%/phpmailer
    vendors: %shared%/vendors
    jp: %vendors%/jp
    jquery: %vendors%/jquery
    spyc: %vendors%/spyc

  .config-files:
    pathsyml: %config%/paths.yml
    bundlesyml: %config%/bundles.yml
    databasesyml: %config%/databases.yml
YAML;

    }

    private static function mockFlattenVarsForLinux(): array {
        return [
            'domain' => 'mundialcard.com',
            'homepage' => '/core/auth/signup/',
            'root' => '/home/jplat726',
            'project' => '/home/jplat726/ms',
            'web' => '/web',
            'css' => '/web/css',
            'js' => '/web/js',
            'img' => '/web/img',
            'bundles' => '/home/jplat726/ms/bundles',
            'config' => '/home/jplat726/ms/config',
            'api' => '/home/jplat726/ms/api',
            'lib' => '/home/jplat726/ms/lib',
            'class'=> '/home/jplat726/ms/lib/class',
            'shared'=> '/home/jplat726/shared',
            'assets'=> '/home/jplat726/shared/assets',
            'mailer'=> '/home/jplat726/shared/assets/phpmailer',
            'vendors'=> '/home/jplat726/shared/vendors',
            'jp'=> '/home/jplat726/shared/vendors/jp',
            'jquery'=> '/home/jplat726/shared/vendors/jquery',
            'spyc'=> '/home/jplat726/shared/vendors/spyc',
            'pathsyml'=> '/home/jplat726/ms/config/paths.yml',
            'bundlesyml'=> '/home/jplat726/ms/config/bundles.yml',
            'databasesyml'=> '/home/jplat726/ms/config/databases.yml',
        ];
    }

}