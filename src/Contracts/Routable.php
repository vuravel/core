<?php

namespace Vuravel\Core\Contracts;

interface Routable
{

    public function parameter($parameter);
    
    public function parameters();

    public function setParameters($parameters);

    public function setParametersFromRoute();

    public function getParametersFromRoute($r = null);
}
