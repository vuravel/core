<?php

namespace Vuravel\Core\Http\Requests;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;

class SessionAuthorizationRequest extends FormRequest
{
    /**
     * The object specs saved in the session.
     *
     * @var SessionableObject
     */
    protected $sessionObject;

    /**
     * The request's Object class.
     *
     * @var Vuravel\Form\Form|Vuravel\Catalog\Catalog
     */
    protected $object;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(!($this->sessionObject = Arr::get(session('bootedElements'), $this->header('X-Vuravel-Id'))))
            return false;

        $objectClass = $this->sessionObject['vuravelClass'];
        $this->object = with(new $objectClass(true))
                            ->rebootFromSession($this->sessionObject)
                            ->startReboot();

        if(!($authorization = method_exists($this->object, 'authorize') ? 
                                $this->object->authorize() : 
                                $this->authorizeFromParentUri()))
            return false;

        $this->object->finishReboot();
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    protected function authorizeFromParentUri()
    {
        if(in_array($this->vlUri(),[  //if the element was booted in those methods, it's already authorized
            'vuravel/form/db/update',
            'vuravel/catalog/browse'
        ]))
            return true;

        $request = Request::create( $this->vlUri(), $this->vlMethods(), $this->vlParameters() );

        return Route::dispatch($request)->status() == 403 ? false : true;
    }

    /**
     * Get the initially loaded object of the request.
     *
     * @return array
     */
    public function vlObject()
    {
        return $this->object;
    }

    /**
     * Get the session object.
     *
     * @return array
     */
    public function sessionObject()
    {
        return $this->sessionObject;
    }

    /**
     * Get the booted object's uri.
     *
     * @return string
     */
    public function vlUri()
    {
        return $this->sessionObject['uri'];
    }

    /**
     * Get the booted object's methods.
     *
     * @return array
     */
    public function vlMethods()
    {
        return $this->sessionObject['methods'];
    }

    /**
     * Get the booted object's route parameters.
     *
     * @return array
     */
    public function vlParameters()
    {
        return array_merge(
            $this->sessionObject['parameters'], 
            in_array($this->vlMethods(), ['POST', 'PUT', 'DELETE']) ? 
                ['_token' => app('session')->token()] : 
                []
        );
    }

}
