<?php
namespace Vuravel\Core;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Vuravel\Core\Http\Middleware\Translatable;
use Vuravel\Form\Form;

class VuravelCoreServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'vuravel-core');

        $this->extendRouteFacade();

        /** @var Router $router */
        $router = $this->app['router'];
        $router->pushMiddlewareToGroup('web', Translatable::class);
    }

    private function extendRouteFacade()
    {
        Route::macro('vuravel', function($uri, $objectClass){

            $partial = is_subclass_of($objectClass, Form::class, true) ? 'form' : 'catalog';

            $object = function () use($objectClass){
                return with(new $objectClass(true))->bootFromRequest()->pushToSession();
            };

            if($extends = end($this->groupStack)['extends'] ?? false){

                $route = $this->view($uri, "vuravel-core::view", [
                    'partial' => $partial,
                    'object' => $object,
                    'extends' => $extends
                ]);
                $route->action['extends'] = $extends; //for smart turbolinks
                return $route;
                
            }else{
                
                return $this->post($uri, function() use($object, $partial) {
                    return $object();
                });
                
            }
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
