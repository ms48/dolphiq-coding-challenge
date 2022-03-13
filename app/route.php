<?php

/**
 * Simple route for demo purpose. Do not use in Production
 */


$url = removeQueryStringVariables($_SERVER['REQUEST_URI']);
$method = $_SERVER['REQUEST_METHOD'];

//check http method and dispatch relevant function
switch ($method) {
    case 'GET':
        getRoutes($url);
        break;
    case 'POST':
        postRoutes($url);
        break;
    default :
        getRoutes($url);
}

/**
 * GET method related routes
 *
 * @param string $url
 */
function getRoutes(string $url)
{
    switch ($url) {
        case '':
        case '/':
            return (new App\Controllers\RoverController(new \App\Services\RoverCommunicationService()))->index();
        default:
            echo 'Route Not found';
    }
}

/**
 * POST method related routes
 *
 * @param string $url
 */
function postRoutes(string $url)
{
    switch ($url) {
        case '/send':
            return (new App\Controllers\RoverController(new \App\Services\RoverCommunicationService()))->send();
        default:
            echo 'Route Not found';
    }
}

/**
 * Remove query string variable from url
 *
 * @param string $url
 * @return string
 */
function removeQueryStringVariables(string $url): string
{
    return parse_url($url, PHP_URL_PATH);
}