<?php

namespace Palzin\CodeIgniter\Config;

use CodeIgniter\Events\Events;

/*
 * --------------------------------------------------------------------
 * Application Events
 * --------------------------------------------------------------------
 * Events allow you to tap into the execution of the program without
 * modifying or extending core files. This file provides a central
 * location to define your events, though they can always be added
 * at run-time, also, if needed.
 *
 * You create code that can execute by subscribing to events with
 * the 'on()' method. This accepts any form of callable, including
 * Closures, that will be executed when the event is triggered.
 *
 * Example:
 *      Events::on('create', [$myInstance, 'myMethod']);
 */

if (config('Palzin')->AutoInspect) {
    Events::on('post_controller_constructor', static function () {
        $router     = service('router');
        $controller = $router->controllerName();
        $method     = $router->methodName();
        $segment    = Services::palzin()->startSegment($controller, $method);
        Services::palzin()->setSegment($segment);
    });

    Events::on('post_system', static function () {
        Services::palzin()->getSegment()->end();
    });
}
