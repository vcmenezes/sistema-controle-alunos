<?php

use Core\Request;

// TODO não ficar retornando nova instancia toda vez, usar singleton
function request(): Request
{
    return new Request;
}
