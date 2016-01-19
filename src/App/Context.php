<?php

/*
 * Bear Framework
 * http://bearframework.com
 * Copyright (c) 2016 Ivo Petkov
 * Free to use under the MIT license.
 */

namespace App;

class Context
{

    /**
     *
     * @var string 
     */
    public $dir = '';

    /**
     *
     * @var \App\Context\Assets 
     */
    public $assets = null;

    /**
     * 
     * @param string $dir
     * @throws \InvalidArgumentException
     */
    function __construct($dir)
    {
        if (!is_string($dir)) {
            throw new \InvalidArgumentException('');
        }
        $this->dir = $dir;
        $this->assets = new \App\Context\Assets($dir);
    }

    /**
     * 
     * @param string $filename
     * @throws \InvalidArgumentException
     * @return boolean
     */
    function load($filename)
    {
        if (!is_string($filename)) {
            throw new \InvalidArgumentException('');
        }
        $app = &\App::$instance;
        return $app->load($this->dir . $filename);
    }

    /**
     * Registers a class for autoloading
     * @param string $class
     * @param string $filename
     * @throws \InvalidArgumentException
     */
    function registerClass($class, $filename)
    {
        if (!is_string($class)) {
            throw new \InvalidArgumentException('');
        }
        if (!is_string($filename)) {
            throw new \InvalidArgumentException('');
        }
        $app = &\App::$instance;
        $app->classes[$class] = $this->dir . $filename;
    }

}
