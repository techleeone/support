<?php

namespace TechOne\Support;

use RuntimeException;

abstract class Facade
{
    protected static $resolvedInstance = [];

    protected static function getFacadeAccessor()
    {
        throw new RuntimeException('Facade does not implement getFacadeAccessor method.');
    }

    public static function getFacadeRoot()
    {
        return static::resolveFacadeInstance(static::getFacadeAccessor());
    }

    protected static function resolveFacadeInstance($name)
    {
        if (is_object($name)) {
            return $name;
        }

        if (isset(static::$resolvedInstance[$name])) {
            return static::$resolvedInstance[$name];
        }

        throw new RuntimeException('Facade does not found.');
    }

    public static function clearResolvedInstance($name)
    {
        unset(static::$resolvedInstance[$name]);
    }

    public static function clearResolvedInstances()
    {
        static::$resolvedInstance = [];
    }

    public static function __callStatic($method, $args)
    {
        $instance = static::getFacadeRoot();

        if (!$instance) {
            throw new RuntimeException('A facade root has not been set.');
        }

        return $instance->$method(...$args);
    }
}
