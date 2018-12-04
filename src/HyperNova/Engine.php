<?php

namespace HyperNova;

use HyperNova\Utils\Autoloader;

class Engine
{
    const SN_ROOT_PHYSICAL = 1;

    static $instance = null;

    protected $store;

    public function __construct()
    {
        $this->store = [];
    }

    public function acquireEnvironmentKnowledges($env)
    {
        if (version_compare($env['php_vers'], '5.6') < 0) {
            throw new \Exception('FATAL ERROR: HyperNova requires PHP version >= 5.6');
        }
        
        $this->store[self::SN_ROOT_PHYSICAL] = str_replace('\\', '/', realpath(dirname($env['dir']))) . '/';

        return $this;
    }

    public function autoload($path, array $options = [])
    {
        $autoloader = new Autoloader();

        $autoloader->register($path, array_merge([
            'rootDir' => $this->get(self::SN_ROOT_PHYSICAL)
        ], $options));

        $autoloader->enableAutoload();
    }

    public static function engine()
    {
        if (!self::$instance) {
            self::$instance = new Engine();
        }

        return self::$instance;
    }

    public function get($name)
    {
        if (isset($this->store[$name]))
        {
            return $this->store[$name];
        }

        return;
    }

    public function requireOnce($filePath)
    {
        if (!is_array($filePath)) {
            $filePath = [$filePath];
        }

        foreach ($filePath as $file) {
            require_once($this->get(self::SN_ROOT_PHYSICAL) . $file);
        }
    }

    public static function constant($name)
    {
        return self::engine()->get($name);
    }
}
