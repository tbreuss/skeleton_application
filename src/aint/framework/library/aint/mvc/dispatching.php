<?php
/**
 * Dispatching mechanisms:
 *    processing http request,
 *    routing it to a specific handler
 *    sending the http response
 */
namespace aint\mvc\dispatching;

use aint\http;
use aint\mvc\routing;

/**
 * Error thrown when an http request cannot be routed
 */
class not_found_error extends \exception {};

/**
 * Dispatches a custom request, returns response
 *
 * @throws not_found_error
 */
function dispatch_request(
    http\request $request,
    array $routers,
    string $actions_namespace,
    callable $error_handler,
    array $request_callbacks = [],
    array $route_callbacks = [],
    array $response_callbacks = []
): http\response {
    foreach ($request_callbacks as $callback) {
        $request = $callback($request);
    }
    // routing the request
    $route = null;
    foreach ($routers as $router) {
        $route = $router($request);
        if ($route !== null)
            break;
    }
    foreach ($route_callbacks as $callback) {
        $route = $callback($request, $route);
    }
    // dispatching to response
    try {
        if (empty($route)
            || (!is_callable($action = $route[routing\route_action]) // @phpstan-ignore-line
                && !is_callable($action = $actions_namespace . '\\' . $action))) // @phpstan-ignore-line
            throw new not_found_error();
        $response = $action($request, $route[routing\route_params]);
    } catch (\exception $error) {
        $params = empty($route) ? [] : $route[routing\route_params];
        $response = $error_handler($request, $params, $error);
    }
    foreach ($response_callbacks as $callback) {
        $response = $callback($request, $route, $response);
    }
    return $response;
}

/**
 * Dispatches global http request and sends the response
 */
function dispatch_http(array $routers, string $actions_namespace, callable $error_handler,
                       array $request_callbacks = [], array $route_callbacks = [], array $response_callbacks = []): void {
    $request = http\build_request_from_globals();
    $response = dispatch_request($request, $routers, $actions_namespace, $error_handler,
                                 $request_callbacks, $route_callbacks, $response_callbacks);
    http\send_response($response);
}

/**
 * Dispatches global http request with default route_segment router and sends the response
 */
function dispatch_http_default_router(string $actions_namespace, callable $error_handler): void {
    dispatch_http(['\aint\mvc\routing\route_segment'],
        $actions_namespace,
        $error_handler);
}
