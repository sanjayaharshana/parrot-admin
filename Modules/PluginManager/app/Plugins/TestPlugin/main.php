<?php

namespace Modules\PluginManager\Plugins\TestPlugin;


class Main{

    public $pageName = 'Test Plugin';
    public $slug = 'test-plugin';
    protected $baseView = 'TestPlugin\\views';

    public function pageView()
    {
        return $this->baseView.'\\index';
    }
}
