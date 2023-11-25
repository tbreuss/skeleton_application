<?php

namespace aint\web\response;

use aint\web\response;

const response_status_ok = 200,
      response_status_created = 201,
      response_status_moved_permanently = 301,
      response_status_found = 302,
      response_status_bad_request = 400,
      response_status_unauthorized = 401,
      response_status_not_found = 404,
      response_status_method_not_allowed = 405,
      response_status_internal_server_error = 500;

/**
 * Prepares data for HTTP response based on the parameters passed
 */
function build_response(string $body = '', int $status = response_status_ok, array $headers = []): response {
    return new response($status, $body, $headers);
}

/**
 * Prepares default response data and sets it to be redirected to location specified
 */
function build_redirect(string $location): response {
    return redirect(build_response(), $location);
}

/**
 * Sets the response data passed to be redirected to location specified
 */
function redirect(response $response, string $location, int $status = response_status_found): response {
    $response->headers[] = 'Location: ' . $location;
    $response->status = $status;
    return $response;
}

/**
 * Changes HTTP status in response data
 */
function response_status(response $response, int $status): response {
    $response->status = $status;
    return $response;
}

/**
 * Adds cookie header to the response array passed
 */
function add_cookie_header(
    response $response,
    ?string $name = null,
    ?string $value = null,
    ?int $expires = null,
    ?string $path = null,
    ?string $domain = null,
    bool $secure = false,
    bool $http_only = false,
    ?string $max_age = null,
    ?string $version = null
): response {
    if (strpos($value, '"')!==false)
        $value = '"' . urlencode(str_replace('"', '', $value)) . '"';
    else
        $value = urlencode($value);

    $cookie_string = $name . '=' . $value;
    if ($version !== null)
        $cookie_string .= '; Version=' . $version;
    if ($max_age !== null)
        $cookie_string .= '; Max-Age=' . $max_age;
    if ($expires !== null)
        $cookie_string .= '; Expires=' . date(DATE_COOKIE, $expires);
    if ($domain !== null)
        $cookie_string .= '; Domain=' . $domain;
    if ($path !== null)
        $cookie_string .= '; Path=' . $path;
    if ($secure)
        $cookie_string .= '; Secure';
    if ($http_only)
        $cookie_string .= '; HttpOnly';

    $response->headers[] = 'Set-Cookie: ' . $cookie_string;
    return $response;
}
