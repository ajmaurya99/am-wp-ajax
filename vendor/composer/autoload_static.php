<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit467981bacb75c58cab6790a55ad99823
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'AmWPAjax\\Frontend\\' => 18,
            'AmWPAjax\\Admin\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'AmWPAjax\\Frontend\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Frontend',
        ),
        'AmWPAjax\\Admin\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Admin',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit467981bacb75c58cab6790a55ad99823::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit467981bacb75c58cab6790a55ad99823::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
