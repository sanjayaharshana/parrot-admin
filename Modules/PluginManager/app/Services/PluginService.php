<?php

namespace Modules\PluginManager\Services;


class PluginService {

    public function getNavbarItems()
    {
        $getPageName = [];

        $getAllPlugins = $this->getAllPlugins();

        foreach ($getAllPlugins as $pageItem)
        {
            dd($pageItem->pageName);
        }

    }

    public function getAllPlugins()
    {
        $plugins = [];
        $pluginPath = base_path('Modules/PluginManager/app/Plugins');
        $directories = glob($pluginPath . '/*', GLOB_ONLYDIR);

        foreach ($directories as $directory) {
            $pluginName = basename($directory);
            $mainFile = $directory . '/Main.php';

            if (file_exists($mainFile)) {
                $className = "Modules\\PluginManager\\Plugins\\$pluginName\\Main";
                if (class_exists($className)) {
                    $plugins[] = new $className();
                }
            }
        }
        return $plugins;
    }



}
