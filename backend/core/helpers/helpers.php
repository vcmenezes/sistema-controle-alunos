<?php

use Core\Request;
use Core\Response;
use Core\Route;
use Core\Router;

function request(): Request
{
    return Request::getInstance();
}

function response(): Response
{
    return Response::getInstance();
}

function router(): Router
{
    return Route::getRouter();
}
