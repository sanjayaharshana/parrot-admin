<?php

namespace Modules\UserPanel\Services;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use ReflectionClass;


class UserPanelService {


    public static function checkControllerString($input) {
        // Only consider index routes for sidebar
        $hasAtIndex = str_ends_with($input, '@index');
        if (!$hasAtIndex) {
            return false;
        }

        // Extract controller class from action
        $controllerClass = explode('@', $input)[0];

        // If controller explicitly opts out/in via showInSidebar, respect it
        try {
            $reflection = new ReflectionClass($controllerClass);
            if ($reflection->hasProperty('showInSidebar')) {
                $property = $reflection->getProperty('showInSidebar');
                $property->setAccessible(true);
                $defaultValue = $property->getDefaultValue();
                if ($defaultValue === false) {
                    return false;
                }
                if ($defaultValue === true) {
                    return true;
                }
            }
        } catch (\ReflectionException $e) {
            // If reflection fails, fall through to subclass checks
        }

        // Allow any controller that extends the UserPanel base Page/Resource controllers (cross-module)
        if (class_exists($controllerClass)) {
            if (is_subclass_of($controllerClass, \Modules\UserPanel\Http\Base\ResourceController::class) ||
                is_subclass_of($controllerClass, \Modules\UserPanel\Http\Base\PageController::class)) {
                return true;
            }
        }

        // Default: do not include in sidebar
        return false;
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
        $flatItems = [];
        $groups = [];

        foreach ($allRoutes as $route) {
            // Only consider web routes
            if (!in_array('web', $route->gatherMiddleware())) {
                continue;
            }
            if (!self::checkControllerString($route->getActionName())) {
                continue;
            }

            $actionName = $route->getActionName();
            $controllerClass = explode('@', $actionName)[0];
            $icon = self::getControllerIcon($controllerClass);
            $parent = self::getControllerParent($controllerClass);

            $item = [
                'uri' => $route->uri(),
                'name' => $route->getName(),
                'icon' => $icon,
            ];

            if ($parent) {
                if (!isset($groups[$parent])) {
                    $groups[$parent] = [
                        'label' => $parent,
                        'children' => [],
                        'icon' => 'fa fa-folder',
                    ];
                }
                $groups[$parent]['children'][] = $item;
            } else {
                $flatItems[] = $item;
            }
        }

        // Try to merge matching top-level item into its group as parent link
        $displayName = function (?string $routeName): string {
            if (!$routeName) return '';
            $label = str_replace(['userpanel.', 'pluginmanager.'], ['', ''], $routeName);
            $base = explode('.', $label)[0] ?? $label;
            return ucfirst(str_replace('-', ' ', $base));
        };

        $menu = [];
        foreach ($groups as $groupLabel => $group) {
            $matchIndex = null;
            foreach ($flatItems as $idx => $fi) {
                if ($displayName($fi['name']) === $groupLabel) {
                    $matchIndex = $idx;
                    break;
                }
            }
            if ($matchIndex !== null) {
                $parentLink = $flatItems[$matchIndex];
                array_splice($flatItems, $matchIndex, 1);
                array_unshift($group['children'], $parentLink + ['is_parent_link' => true]);
            }
            $menu[] = $group;
        }

        // Append remaining top-level items
        foreach ($flatItems as $fi) {
            $menu[] = $fi;
        }

        return $menu;
    }

    protected static function getControllerParent(string $controllerClass): ?string
    {
        try {
            $reflection = new ReflectionClass($controllerClass);
            if ($reflection->hasProperty('parentMenu')) {
                $property = $reflection->getProperty('parentMenu');
                $property->setAccessible(true);
                $value = $property->getDefaultValue();
                if (is_string($value) && trim($value) !== '') {
                    return trim($value);
                }
            }
        } catch (\ReflectionException $e) {
            // ignore
        }
        return null;
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
