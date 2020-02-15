<?php

namespace Vuravel\Core;

class File
{
	public $path;
	public $filename;
	public $content;

    /**
     * Construct a file
     *
     * @return Vuravel\Core\File
     */
	public function __construct($path)
    {
    	$this->path = $path;
    	$this->filename = basename($this->path);
    }

    public function readFileContents()
    {
    	$this->content = file_get_contents($this->path);
    }

    /**
     * Checks if a file from the storage is found/still used in it's corresponding DB table
     * @param  string  $path [the storage path for the file]
     * @return integer       [the number of times the file is found. Shouldn't be more than one]
     */
    public static function isLinkedToDB($path)
    {
        $path = explode("/", $path);
        $found = false;
        if(count($path) == 5){
            $db = $path[1];
            $table = $path[2];
            $column = $path[3];
            $filename = $path[4];
            return \DB::connection($db)->table($table)
                        ->where($column, 'LIKE', '%'.$filename.'%')->count();
        }
    }

    public static function unlinkFromStorage($path)
    {
        return unlink(storage_path('app/'.$path));
    }

}