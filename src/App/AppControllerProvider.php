<?php

namespace App;


use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Silex\ServiceProviderInterface;

class AppControllerProvider implements ControllerProviderInterface, ServiceProviderInterface
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

        $controllers->get('/', "Fun\\Controller\\CampaignController::index");
        $controllers->post('/', "Fun\\Controller\\CampaignController::store");
        $controllers->get('/{id}', "Fun\\Controller\\CampaignController::show");
        $controllers->post('/{id}', "Fun\\Controller\\CampaignController::edit");

        return $controllers;
    }

}