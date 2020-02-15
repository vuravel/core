<?php

namespace Vuravel\Core;

use Vuravel\Core\File;

class FileClass extends File
{
    public $className;
    public $classNamespace;
    public $fullClassName;

    public $reflectionClass;

    /**
     * Construct a FileClass
     *
     * @return Vuravel\Core\FileClass
     */
	public function __construct($path, $class = null)
    {
    	parent::__construct($path);

        $this->className = $class ? : preg_replace('/\\.[^.\\s]{3,4}$/', '', $this->filename);
        $this->classNamespace = $this->parseNamespace();
        $this->fullClassName = $this->classNamespace.'\\'.$this->className;
    }

    /**
     * Gets the desired static property value from the Class
     * @param  string $property [static property variable name]
     * @return array             [static property value]
     */
    public function getStaticProperty($property)
    {
        $fullClassName = $this->getFullClassName();
        return $fullClassName::$$property ?? null;
    }

    /**
     * Gets the desired static property value from the Class
     * @param  string $property [static property variable name]
     * @return array             [static property value]
     */
    public function getStaticMethod($method)
    {
        $fullClassName = $this->getFullClassName();
        return $fullClassName::$method() ?? null;
    }


    public function getReflectionClass()
    {
        return new \ReflectionClass($this->classNamespace ? $this->fullClassName : $this->className );
    }


    public function parseNamespace() {
        $ns = null;
        if ($handle = fopen($this->path, "r")) {
            while (($line = fgets($handle)) !== false) {
                if (strpos($line, 'namespace') === 0) {
                    $parts = explode(' ', $line);
                    $ns = rtrim(trim($parts[1]), ';');
                    break;
                }
            }
            fclose($handle);
        }
        return $ns;
    }

    public function getFullClassName()
    {
        return $this->fullClassName;
    }

}