<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitaffdba4bf5e266c16a11f6805d99b37a
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInitaffdba4bf5e266c16a11f6805d99b37a', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitaffdba4bf5e266c16a11f6805d99b37a', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitaffdba4bf5e266c16a11f6805d99b37a::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
