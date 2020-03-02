<?php 
namespace Vuravel\Core\Traits;

trait PersistsInSession {

    public function setBootableId($overwriteId = '')
    {
        return $this->id(
            $overwriteId ?: ($this->id ?: class_basename($this).uniqid()), 
            $this->id ? true : false
        );
    }

    /**
     * Boot a Routable object AND push it to the session.
     *
     * @return Vuravel\Form\Form
     */
    public function bootToSession()
    {
        $this->setParametersFromRoute();
        return $this->vlBoot()->pushToSession();
    }

    public function rebootFromSession($sessionObject)
    {
        $this->setCommonRebootAttributes($sessionObject);
        return $this;
    }

    protected function setCommonRebootAttributes($sessionObject)
    {
        $this->store($sessionObject['store']);
        $this->setParameters($sessionObject['parameters']);        
    }


    public function commonSessionAttributes()
    {
        return [
            'vuravelClass' => get_class($this),
            'store' => $this->store(),
            'parameters' => $this->parameters(),
            'uri' => optional(request()->route())->uri(), //optional for tests fail (route null)
            'methods' => optional(request()->route())->methods()[0] //optional for tests fail (route null)
        ];
    }

    public function overwriteUri($uri, $methods)
    {
        session()->put($this->vlSessionKey().'.uri', $uri);
        session()->put($this->vlSessionKey().'.methods', $methods);
    }

    /**
     * Saves the necessary parameters to boot in the session.
     *
     * @return self
     */
    public function pushToSession()
    {
        session()->put($this->vlSessionKey(), $this->commonSessionAttributes());

        return $this;
    }

    /**
     * Removes a booted Element from the session.
     *
     * @return self
     */
    public function removeFromSession()
    {
        session()->forget($this->vlSessionKey());

        return $this;
    }

    protected function vlSessionKey()
    {
        return 'bootedElements.'.$this->id;
    }

}