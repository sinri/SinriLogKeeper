<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit54de8628b527b5cb232430d7e5c788f5
{
    public static $files = array (
        '7b11c4dc42b3b3023073cb14e519683c' => __DIR__ . '/..' . '/ralouphie/getallheaders/src/getallheaders.php',
        'b2f2dcffedaa3b9c1f23d941413c0d99' => __DIR__ . '/..' . '/sinri/enoch/autoload.php',
    );

    public static $prefixLengthsPsr4 = array (
        'c' => 
        array (
            'cebe\\markdown\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'cebe\\markdown\\' => 
        array (
            0 => __DIR__ . '/..' . '/cebe/markdown',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit54de8628b527b5cb232430d7e5c788f5::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit54de8628b527b5cb232430d7e5c788f5::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
