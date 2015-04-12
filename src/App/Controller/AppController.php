<?php

namespace App\Controller;


use Hateoas\HateoasBuilder;
use App\Campaign;

class AppController
{
    public function index()
    {
        // This is where the Hypermedia control comes in
        $hateoas = HateoasBuilder::create()->build();

        $campaign = new Campaign();
        $campaign->setId(22);
        $campaign->setDate(time());
        $campaign->setTitle("Sample Campaign");

        $json = $hateoas->serialize($campaign, 'json');

        return $json;

    }
}