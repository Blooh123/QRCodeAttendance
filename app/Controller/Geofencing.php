<?php

namespace Controller;

class  Geofencing extends \Controller
{
    public function index(): void
    {
        $this->loadView('map');
    }
}

$geofencing = new Geofencing();
$geofencing->index();

