<?php 
namespace Vuravel\Core\Traits;

trait IsRoutable {

	protected $parameters = [];

    /**
     * Gets the route's parameter or the one persisted in the session.
     *
     * @param  mixed  $parameter
     * @return mixed
     */
    public function parameter($parameter)
    {
        return request($parameter) ?: ($this->parameters[$parameter] ?? null);
    }

    /**
     * Gets the route's parameters or the ones persisted in the session.
     *
     * @return mixed
     */
    public function parameters()
    {
        return $this->parameters;
    }

    /**
     * Sets the route's parameters
     *
     * @return mixed
     */
    public function setParameters($parameters)
    {
        return $this->parameters = $parameters;
    }

	public function setParametersFromRoute()
	{
		$this->parameters = $this->getParametersFromRoute();
        return $this;
	}

    public function getParametersFromRoute($r = null)
    {
        $r = $r ?: request();
        $route = $r->route();

        if($route){
            $parameterNames = $route->parameterNames();
            return collect($route->parameters())->filter(function($param, $key) use ($parameterNames){
                return in_array($key, $parameterNames);
            })->all();
        }else{
            return [];
        }
    }

}


