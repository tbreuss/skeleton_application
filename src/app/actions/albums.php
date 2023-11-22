<?php

namespace app\actions\albums;

use aint\http;
use aint\mvc\dispatching\not_found_error;
use app\model\albums as albums_model;
use app\view;

function index_action(): http\response {
    return list_action();
}

function list_action(): http\response {
    return view\render('albums/list', ['albums' => albums_model\list_albums()]);
}

function add_action(http\request $request): http\response {
    if (http\is_post($request)) {
        albums_model\add_album($request->params);
        return http\build_redirect('/albums');
    }
    return view\render('albums/add');
}

function edit_action(http\request $request, array $params): http\response {
    if (http\is_post($request)) {
        albums_model\edit_album($params['id'], $request->params);
        return http\build_redirect('/albums');
    }
    $album = albums_model\get_album($params['id']);
    if ($album === null)
        throw new not_found_error();
    return view\render('albums/edit', ['album' => $album]);
}

function delete_action(http\request $request, array $params): http\response {
    albums_model\delete_album($params['id']);
    return http\build_redirect('/albums');
}
