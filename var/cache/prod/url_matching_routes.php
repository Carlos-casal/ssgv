<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/servicios/listado' => [[['_route' => 'app_services_list', '_controller' => 'App\\Controller\\ComingSoonController::servicesList'], null, null, null, false, false, null]],
        '/servicios/informes' => [[['_route' => 'app_services_reports', '_controller' => 'App\\Controller\\ComingSoonController::servicesReports'], null, null, null, false, false, null]],
        '/servicios/cuadrantes' => [[['_route' => 'app_services_schedules', '_controller' => 'App\\Controller\\ComingSoonController::servicesSchedules'], null, null, null, false, false, null]],
        '/comunicados' => [[['_route' => 'app_communications', '_controller' => 'App\\Controller\\ComingSoonController::communications'], null, null, null, false, false, null]],
        '/vehiculos' => [[['_route' => 'app_vehicles', '_controller' => 'App\\Controller\\ComingSoonController::vehicles'], null, null, null, false, false, null]],
        '/gesdoc' => [[['_route' => 'app_gesdoc', '_controller' => 'App\\Controller\\ComingSoonController::gesdoc'], null, null, null, false, false, null]],
        '/central' => [[['_route' => 'app_central', '_controller' => 'App\\Controller\\ComingSoonController::central'], null, null, null, false, false, null]],
        '/estadisticas' => [[['_route' => 'app_statistics', '_controller' => 'App\\Controller\\ComingSoonController::statistics'], null, null, null, false, false, null]],
        '/' => [[['_route' => 'app_dashboard', '_controller' => 'App\\Controller\\DashboardController::index'], null, null, null, false, false, null]],
        '/login' => [[['_route' => 'app_login', '_controller' => 'App\\Controller\\SecurityController::login'], null, null, null, false, false, null]],
        '/logout' => [[['_route' => 'app_logout', '_controller' => 'App\\Controller\\SecurityController::logout'], null, null, null, false, false, null]],
        '/personal/listado' => [[['_route' => 'app_volunteer_list', '_controller' => 'App\\Controller\\VolunteerController::list'], null, null, null, false, false, null]],
        '/personal/nuevo' => [[['_route' => 'app_volunteer_new', '_controller' => 'App\\Controller\\VolunteerController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/personal/inscripcion' => [[['_route' => 'app_volunteer_registration', '_controller' => 'App\\Controller\\VolunteerController::registration'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/personal/exportar-csv' => [[['_route' => 'app_volunteer_export_csv', '_controller' => 'App\\Controller\\VolunteerController::exportCsv'], null, null, null, false, false, null]],
        '/personal/informes' => [[['_route' => 'app_volunteer_reports', '_controller' => 'App\\Controller\\VolunteerController::reports'], null, null, null, false, false, null]],
        '/formulario-inscripcion-voluntarios' => [[['_route' => 'app_volunteer_registration_form', '_controller' => 'App\\Controller\\VolunteerRegistrationController::showRegistrationForm'], null, null, null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/personal/editar/([^/]++)(*:32)'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        32 => [
            [['_route' => 'app_volunteer_edit', '_controller' => 'App\\Controller\\VolunteerController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
