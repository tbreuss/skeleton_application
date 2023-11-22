<?php
/**
 * HTTP-related functions
 */
namespace aint\http;

class request {
    public function __construct(
        public string $scheme,
        public string $body,
        public string $path,
        public array $params,
        public string $method,
        public array $headers,
    ) {}
}

class response {
    public function __construct(
        public int $status,
        public string $body,
        public array $headers,
    ) {}
}

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
 * Prepares data for HTTP response based on the parameters passed
 */
function build_response(string $body = '', int $code = 200, array $headers = []): response {
    return new response($code, $body, $headers);
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
function redirect(response $response, string $location, int $status = 302): response {
    $response->headers[] = 'Location: ' . $location;
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

/**
 * Outputs response data
 */
function send_response(response $response): void {
    header('HTTP/1.1 ' . $response->status);
    array_walk($response->headers, fn($header) => header($header));
    echo $response->body;
}

/**
 * Changes HTTP status in response data
 */
function response_status(response $response, int $status): response {
    $response->status = $status;
    return $response;
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
