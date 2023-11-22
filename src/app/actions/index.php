<?php

namespace app\actions\index;

use aint\http;
use app\view;

/**
 * Handles index page
 */
function index_action(): http\response
{
    return view\render('index/index');
}
