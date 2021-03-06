<?php

namespace Vendor;

/**
 * Class Psr4Autoloader
 * @package Vendor
 */
class Psr4Autoloader
{
    /**
     * @var array
     */
    protected array $prefixes = [];

    public function register()
    {
        spl_autoload_register([$this, 'loadClass']);
    }

    /**
     * @param $prefix
     * @param $base_dir
     * @param false $prepend
     */
    public function addNamespace($prefix, $base_dir, $prepend = false)
    {
        $prefix = trim($prefix, '\\') . '\\';
        $base_dir = rtrim($base_dir, DIRECTORY_SEPARATOR) . '/';
        if (isset($this->prefixes[$prefix]) === false) {
            $this->prefixes[$prefix] = [];
        }
        if ($prepend) {
            array_unshift($this->prefixes[$prefix], $base_dir);
        } else {
            array_push($this->prefixes[$prefix], $base_dir);
        }
    }

    /**
     * @param $class
     * @return false|string
     */
    public function loadClass($class)
    {
        $prefix = $class;
        while (false !== $pos = strrpos($prefix, '\\')) {
            $prefix = substr($class, 0, $pos + 1);
            $relative_class = substr($class, $pos + 1);
            $mapped_file = $this->loadMappedFile($prefix, $relative_class);
            if ($mapped_file) {
                return $mapped_file;
            }
            $prefix = rtrim($prefix, '\\');
        }
        return false;
    }

    /**
     * @param $prefix
     * @param $relative_class
     * @return false|string
     */
    protected function loadMappedFile($prefix, $relative_class)
    {
        if (isset($this->prefixes[$prefix]) === false) {
            return false;
        }
        foreach ($this->prefixes[$prefix] as $base_dir) {
            $file = $base_dir
                . str_replace('\\', '/', $relative_class)
                . '.php';
            if ($this->requireFile($file)) {
                return $file;
            }
        }
        return false;
    }

    /**
     * @param $file
     * @return bool
     */
    protected function requireFile($file): bool
    {
        if (file_exists($file)) {
            require $file;
            return true;
        }
        return false;
    }
}