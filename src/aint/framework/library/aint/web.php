<?php

namespace aint\web;

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
 * Error thrown when an http request cannot be routed
 */
class not_found_error extends \exception {}
