<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitf5df816b87c3f86b7d09a3bee6a2783b
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

        spl_autoload_register(array('ComposerAutoloaderInitf5df816b87c3f86b7d09a3bee6a2783b', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitf5df816b87c3f86b7d09a3bee6a2783b', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitf5df816b87c3f86b7d09a3bee6a2783b::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
