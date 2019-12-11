<?php
require_once __DIR__ . '/../vendor/autoload.php';

spl_autoload_register(function ($class)
{
    if (strpos($class, 'Calgamo\\Module\\CalgamoService\\Test\\') === 0) {
        $name = substr($class, strlen('Calgamo\\Module\\CalgamoService\\Test\\'));
        $name = array_filter(explode('\\',$name));
        $file = dirname(__DIR__) . '/src/' . implode('/',$name) . '.php';
        /** @noinspection PhpIncludeInspection */
        require_once $file;
    }
});
