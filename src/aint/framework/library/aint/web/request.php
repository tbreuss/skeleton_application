<?php

namespace aint\web\request;

use aint\web\request;

/**
 * Http Request method types
 */
const request_method_post = 'POST',
      request_method_get = 'GET',
      request_method_put = 'PUT',
      request_method_delete = 'DELETE';

/**
 * Retrieves data about the current HTTP request using PHP's global arrays
 */
function build_request_from_globals(): request {
    $path = $_SERVER['REQUEST_URI'];
    if (($separator_position = strpos($path, '?')) !== false)
        $path = substr($path, 0, $separator_position);
    return new request(
        scheme: empty($_SERVER['HTTPS']) ? 'http' : 'https',
        body: file_get_contents("php://input"),
        path: trim($path, '/'),
        params: array_merge($_GET, $_POST), // todo make these separate, provide functions
        method: $_SERVER['REQUEST_METHOD'],
        headers: get_headers_from_globals(),
    );
}

/**
 * Extracts http headers from current context
 *
 * Uses getallheaders function if available
 */
function get_headers_from_globals(): array {
    if (function_exists('getallheaders'))
        return getallheaders();
    else {
        $headers = [];
        foreach($_SERVER as $header => $value)
            if (strpos($header, 'HTTP_') === 0) {
                $formatted_header_name =
                    str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($header, 5)))));
                $headers[$formatted_header_name] = $value;
            }
        return $headers;
    }
}

/**
 * Whether the request is a POST
 */
function is_post(request $request): bool {
    return $request->method === request_method_post;
}

/**
 * Whether the request is a GET
 */
function is_get(request $request): bool {
    return $request->method === request_method_get;
}

/**
 * Whether the request is a DELETE
 */
function is_delete(request $request): bool {
    return $request->method === request_method_delete;
}

/**
 * Whether the request is a PUT
 */
function is_put(request $request): bool {
    return $request->method === request_method_put;
}

/**
 * Returns value of a cookie by name
 */
function get_cookie_value(request $request, string $name): mixed {
    if (isset($request->headers['Cookie'])) {
        $key_value_pairs = preg_split('#;\s*#', $request->headers['Cookie']);
        foreach ($key_value_pairs as $key_value) {
            list($key, $value) = preg_split('#=\s*#', $key_value, 2);
            if ($key == $name)
                return $value;
        }
    }
    return null;
}
