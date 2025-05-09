<?php

namespace Totocsa\Icseusd\Services;

class GenericConfigLoader
{
    public static function get($configName)
    {
        $appConfigPath = config_path("icseusd/generic/{$configName}.php");
        if (file_exists($appConfigPath)) {
            return require $appConfigPath;
        }

        $packageConfigPath = realpath(__DIR__ . "/../config/icseusd/generic/{$configName}.php");
        if (file_exists($packageConfigPath)) {
            return require $packageConfigPath;
        }

        throw new \Exception("Configuration file '{$configName}' not found in application or package.");
    }
}
