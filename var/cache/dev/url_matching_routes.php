<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/_wdt/styles' => [[['_route' => '_wdt_stylesheet', '_controller' => 'web_profiler.controller.profiler::toolbarStylesheetAction'], null, null, null, false, false, null]],
        '/_profiler' => [[['_route' => '_profiler_home', '_controller' => 'web_profiler.controller.profiler::homeAction'], null, null, null, true, false, null]],
        '/_profiler/search' => [[['_route' => '_profiler_search', '_controller' => 'web_profiler.controller.profiler::searchAction'], null, null, null, false, false, null]],
        '/_profiler/search_bar' => [[['_route' => '_profiler_search_bar', '_controller' => 'web_profiler.controller.profiler::searchBarAction'], null, null, null, false, false, null]],
        '/_profiler/phpinfo' => [[['_route' => '_profiler_phpinfo', '_controller' => 'web_profiler.controller.profiler::phpinfoAction'], null, null, null, false, false, null]],
        '/_profiler/xdebug' => [[['_route' => '_profiler_xdebug', '_controller' => 'web_profiler.controller.profiler::xdebugAction'], null, null, null, false, false, null]],
        '/_profiler/open' => [[['_route' => '_profiler_open_file', '_controller' => 'web_profiler.controller.profiler::openAction'], null, null, null, false, false, null]],
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
        '/servicios' => [[['_route' => 'app_services_list', '_controller' => 'App\\Controller\\ServiceController::listServices'], null, ['GET' => 0], null, false, false, null]],
        '/nuevo_servicio' => [[['_route' => 'app_service_new', '_controller' => 'App\\Controller\\ServiceController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/voluntarios' => [[['_route' => 'app_volunteer_list', '_controller' => 'App\\Controller\\VolunteerController::list'], null, null, null, false, false, null]],
        '/nuevo_voluntario' => [[['_route' => 'app_volunteer_new', '_controller' => 'App\\Controller\\VolunteerController::new'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/nueva_inscripcion' => [[['_route' => 'app_volunteer_registration', '_controller' => 'App\\Controller\\VolunteerController::registration'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/exportar-csv' => [[['_route' => 'app_volunteer_export_csv', '_controller' => 'App\\Controller\\VolunteerController::exportCsv'], null, null, null, false, false, null]],
        '/informes_voluntarios' => [[['_route' => 'app_volunteer_reports', '_controller' => 'App\\Controller\\VolunteerController::reports'], null, null, null, false, false, null]],
        '/formulario-inscripcion-voluntarios' => [[['_route' => 'app_volunteer_registration_form', '_controller' => 'App\\Controller\\VolunteerRegistrationController::showRegistrationForm'], null, null, null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_(?'
                    .'|error/(\\d+)(?:\\.([^/]++))?(*:38)'
                    .'|wdt/([^/]++)(*:57)'
                    .'|profiler/(?'
                        .'|font/([^/\\.]++)\\.woff2(*:98)'
                        .'|([^/]++)(?'
                            .'|/(?'
                                .'|search/results(*:134)'
                                .'|router(*:148)'
                                .'|exception(?'
                                    .'|(*:168)'
                                    .'|\\.css(*:181)'
                                .')'
                            .')'
                            .'|(*:191)'
                        .')'
                    .')'
                .')'
                .'|/servicios\\-([^/]++)(*:222)'
                .'|/editar(?'
                    .'|\\-([^/]++)(*:250)'
                    .'|_voluntario\\-([^/]++)(*:279)'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        38 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        57 => [[['_route' => '_wdt', '_controller' => 'web_profiler.controller.profiler::toolbarAction'], ['token'], null, null, false, true, null]],
        98 => [[['_route' => '_profiler_font', '_controller' => 'web_profiler.controller.profiler::fontAction'], ['fontName'], null, null, false, false, null]],
        134 => [[['_route' => '_profiler_search_results', '_controller' => 'web_profiler.controller.profiler::searchResultsAction'], ['token'], null, null, false, false, null]],
        148 => [[['_route' => '_profiler_router', '_controller' => 'web_profiler.controller.router::panelAction'], ['token'], null, null, false, false, null]],
        168 => [[['_route' => '_profiler_exception', '_controller' => 'web_profiler.controller.exception_panel::body'], ['token'], null, null, false, false, null]],
        181 => [[['_route' => '_profiler_exception_css', '_controller' => 'web_profiler.controller.exception_panel::stylesheet'], ['token'], null, null, false, false, null]],
        191 => [[['_route' => '_profiler', '_controller' => 'web_profiler.controller.profiler::panelAction'], ['token'], null, null, false, true, null]],
        222 => [[['_route' => 'app_service_show', '_controller' => 'App\\Controller\\ServiceController::show'], ['slug'], ['GET' => 0], null, false, true, null]],
        250 => [[['_route' => 'app_service_edit', '_controller' => 'App\\Controller\\ServiceController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, true, null]],
        279 => [
            [['_route' => 'app_volunteer_edit', '_controller' => 'App\\Controller\\VolunteerController::edit'], ['id'], ['GET' => 0, 'POST' => 1], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
