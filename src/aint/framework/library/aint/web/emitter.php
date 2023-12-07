<?php

namespace aint\web\emitter;

use aint\web\response;

/**
 * Outputs response data
 */
function send_response(response $response): void {
    header('HTTP/1.1 ' . $response->status);
    array_walk($response->headers, fn($header) => header($header));
    echo $response->body;
}
