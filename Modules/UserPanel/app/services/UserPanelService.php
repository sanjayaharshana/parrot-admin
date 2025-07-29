<?php

namespace Modules\UserPanel\Services;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use ReflectionClass;


class UserPanelService {


    public static function checkControllerString($input) {
        // Extract parts - check for any module controller with index method
        $hasModule = strpos($input, 'Modules\\UserPanel') !== false;
        $hasAtIndex = str_ends_with($input, '@index');

        // Return true if both conditions met
        return $hasModule && $hasAtIndex;
    }

    public static function getControllerIcon($controllerClass) {
        try {
            $reflection = new ReflectionClass($controllerClass);

            // Check if the class has the icon property
            if ($reflection->hasProperty('icon')) {
                $property = $reflection->getProperty('icon');
                $property->setAccessible(true);

                // Get the default value of the property
                $defaultValue = $property->getDefaultValue();
                return $defaultValue ?: '';
            }

            // If no icon property, check parent classes recursively
            $parentClass = $reflection->getParentClass();
            while ($parentClass) {
                if ($parentClass->hasProperty('icon')) {
                    $property = $parentClass->getProperty('icon');
                    $property->setAccessible(true);
                    $defaultValue = $property->getDefaultValue();
                    return $defaultValue ?: '';
                }
                $parentClass = $parentClass->getParentClass();
            }

        } catch (\ReflectionException $e) {
            // If reflection fails, return empty string
            return '';
        }

        return '';
    }

    public static function getNavMenuItem()
    {
        $allRoutes = Route::getRoutes();
        $webRoutes = [];

        foreach ($allRoutes as $route) {
            // Check if the route has 'web' middleware (web.php routes usually do)
            if (in_array('web', $route->gatherMiddleware())) {
                if(self::checkControllerString($route->getActionName())) {
                    // Extract controller class from action name
                    $actionName = $route->getActionName();
                    $controllerClass = explode('@', $actionName)[0];

                    // Get icon from controller
                    $icon = self::getControllerIcon($controllerClass);

                    $webRoutes[] = [
                        'uri' => $route->uri(),
                        'name' => $route->getName(),
                        'icon' => $icon
                    ];
                }
            }
        }

        // For debugging - you can temporarily uncomment this line to see what's being returned
        // dd($webRoutes);

        return $webRoutes;
    }

    // Test method to verify icon extraction
    public static function testIconExtraction()
    {
        $testControllers = [
            'Modules\UserPanel\Http\Controllers\UserPanelController',
            'Modules\PluginManager\Http\Controllers\PluginManagerController'
        ];

        $results = [];
        foreach ($testControllers as $controller) {
            $results[$controller] = self::getControllerIcon($controller);
        }

        return $results;
    }

}
