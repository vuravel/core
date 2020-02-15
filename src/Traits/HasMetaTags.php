<?php 
namespace Vuravel\Core\Traits;

trait HasMetaTags {

    /**
     * The element's meta tags array.
     *
     * @var array
     */
    protected $metaTags = [];

    /**
     * Get or retrieve metaTags tag information to the element. Currently supporting 'title', 'description' and 'keywords'.
     *
     * @param  string|array  $data Key string or Key/value associative array.
     * @return mixed
     */
    public function metaTags($data)
    {
        if(is_array($data)){
            $this->metaTags = array_merge ($this->metaTags, $data);
            return $this;
        }else{
            return $this->metaTags[$data] ?? null;
        }
    }

    public function hasMetaTags()
    {
        return count($this->metaTags);
    }

}