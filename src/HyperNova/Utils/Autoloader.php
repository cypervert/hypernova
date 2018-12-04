<?php
/**
* Created by Cypervert 18.06.2017 13:24
*/

namespace HyperNova\Utils;

/**
* Class Autoloader
*
* One of core class to supply autoload facilities to the engine
*
* @package Core
*/
class Autoloader
{
    const P_FOLDER = 'P_FOLDER';
    const P_PREFIX = 'P_PREFIX';
    
    /**
    * @var string[][] $folders - [[P_FOLDER => (str)$absoluteFolder, P_PREFIX => (str)$prefixToIgnore]]
    */
    protected $folders = [];
    
    protected $autoloaderRegistered = false;
    
    public function enableAutoload()
    {
        if(!$this->autoloaderRegistered) {
            spl_autoload_register(array($this, 'autoloader'));
            $this->autoloaderRegistered = true;
        }
    }
    
    /**
    * @param string $class - Fully-qualified path with namespaces
    */
    public function autoloader($class)
    {        
        foreach($this->folders as $data) {
            $theClassFile = $class;
            
            if($data[static::P_PREFIX] && strrpos($class, $data[static::P_PREFIX]) !== false) {
                $theClassFile = substr($class, strlen($data[static::P_PREFIX]));
            }
            
            $classFullFileName = str_replace('\\', '/', $data[static::P_FOLDER] . $theClassFile) . DOT_PHP_EX;
            if(file_exists($classFullFileName) && is_file($classFullFileName)) {
                require_once($classFullFileName);
            }
        }
    }
    
    /**
    * @param string $absoluteClassRoot - absolute path to root class folder
    * @param string $classPrefix - PHP class prefix to ignore. Can be whole namespace or part of it
    */
    public function register($absoluteClassRoot, array $options = [])
    {    
        $classPrefix = isset($options['prefix']) ? $options['prefix'] : '';
        $rootDir = isset($options['rootDir']) ? $options['rootDir'] : '';
        
        $absoluteClassRoot = str_replace('\\', '/', $rootDir . $absoluteClassRoot);
        
        if(!($absoluteClassRoot = realpath($absoluteClassRoot))) {
            // TODO - throw new \Exception("There is some error when installing autoloader for '{$absoluteClassRoot}' class prefix '{$classPrefix}'");
            return;
        }
        $absoluteClassRoot = str_replace('\\', '/', $absoluteClassRoot) . '/';
        
        if($classPrefix && strrpos($classPrefix, 1) != '\\') {
            $classPrefix .= '\\';
        }
        
        $this->folders[] = [
            static::P_FOLDER => $absoluteClassRoot,
            static::P_PREFIX => $classPrefix,
        ];
        
        $this->enableAutoload();
    }
}
