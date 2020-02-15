<?php

namespace Vuravel\Core;

use Vuravel\Core\FileClass;

class Directory
{
	public $path;

    /**
     * Construct a directory
     *
     * @return Vuravel\Core\Directory
     */
	public function __construct($path)
    {
    	$this->path = $path;
    }

    /**
     * Returns a flattened collection of Fileclasses in a path/array of paths
     *
     * @param  array|string $path
     * @return array
     */
    public static function files($path)
    {
    	$path = is_array($path) ? $path : [$path];
    	return collect($path)->flatMap(function($path){
    		return static::getDirectoryFiles($path);
    	})->toArray();
    }

    /**
     * Returns a flattened collection of Fileclasses in a path/array of paths
     *
     * @param  array|string $path
     * @return array
     */
    public static function filesOfClass($path, $class)
    {
        $files = self::files($path);
        return collect($files)->filter(function($file) use($class){
            return is_a($file->fullClassName, $class, true);
        })->all();
    }

    /**
     * Get all files inside a directory recursively
     *
     * @return array
     */
	public static function getDirectoryFiles($path, &$results = array())
	{
		if(!is_dir($path))
			return $results;
	    foreach(scandir($path) as $key => $value){
	        $subPath = realpath($path.DIRECTORY_SEPARATOR.$value);
	        if(!is_dir($subPath)) {
	            $results[] = new FileClass($subPath);
	        } else if($value !== "." && $value !== "..") {
	            static::getDirectoryFiles($subPath, $results);
	        }
	    }
	    return $results;
	}

    /**
     * Retrieves all files in storage/app/public folder recursively
     * Excluding some files
     * @return [type] [description]
     */
    public static function getPublicStorageFilePaths($path = 'public')
    {
        return array_diff(\Storage::allFiles($path), [
            'public/.gitignore'
        ]);
    }

}