<?php

namespace App;


use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Silex\ServiceProviderInterface;

class WordControllerProvider implements ControllerProviderInterface, ServiceProviderInterface
{
    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app)
    {
        // TODO: Implement boot() method.
    }


    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     */
    public function register(Application $app)
    {

    }


    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/{id}', "App\\Controller\\WordController::show")->bind('show.id');
        $controllers->post('/', "App\\Controller\\WordController::create");
        $controllers->get('/', "App\\Controller\\WordController::search");
        $controllers->put('/{id}', "App\\Controller\\WordController::update");
        $controllers->delete('/{id}', "App\\Controller\\WordController::delete");

        return $controllers;
    }

}