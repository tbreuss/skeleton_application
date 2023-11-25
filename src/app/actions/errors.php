<?php

namespace app\actions\errors;

use aint\web\not_found_error;
use aint\web\request;
use aint\web\response;
use aint\web\view;
use exception;

/**
 * Error handler, this function is called if something happens
 * during the dispatch process
 */
function error_action(request $request, array $params, exception $error): response
{
    if ($error instanceof not_found_error) {
        $status = response\response_status_not_found;
        $message = 'Page ' . $request->path . ' is not found';
    } else {
        $status = response\response_status_internal_server_error;
        $message = 'System error';
        error_log(get_class($error) . ' ' . $error->getMessage());
    }

    return response\response_status(
        view\render('errors/error', ['message' => $message]),
        $status
    );
}
