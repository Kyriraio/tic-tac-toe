<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit6c192867d870fbb821e61baa19a41df8
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit6c192867d870fbb821e61baa19a41df8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit6c192867d870fbb821e61baa19a41df8::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit6c192867d870fbb821e61baa19a41df8::$classMap;

        }, null, ClassLoader::class);
    }
}
