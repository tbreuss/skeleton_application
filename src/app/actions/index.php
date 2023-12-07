<?php

namespace app\actions\index;

use aint\web\response;
use aint\web\view;
use aint\system;

function index_action(): response
{
    return view\render('index/index');
}

function system_action(): response
{
    return view\render('index/system', [
        'constants' => system\defined_constants(),
        'classes' => system\defined_classes(),
        'functions' => system\defined_functions(),
    ]);
}
