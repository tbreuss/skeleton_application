<?php

namespace app\actions\errors;

use app\view;
use aint\mvc\dispatching;
use aint\http;
use exception;

/**
 * Error handler, this function is called if something happens
 * during the dispatch process
 */
function error_action(http\request $request, array $params, exception $error): http\response
{
    if ($error instanceof dispatching\not_found_error) {
        $status = http\response_status_not_found;
        $message = 'Page ' . $request->path . ' is not found';
    } else {
        $status = http\response_status_internal_server_error;
        $message = 'System error';
        error_log(get_class($error) . ' ' . $error->getMessage());
    }

    return http\response_status(
        view\render('errors/error', ['message' => $message]),
        $status
    );
}
