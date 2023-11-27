<?php

namespace app\actions\albums;

use aint\web\request;
use aint\web\response;
use aint\web\view;
use app\models\album;

function index_action(): response {
    return list_action();
}

function list_action(): response {
    return view\render('albums/list', ['albums' => album\list_albums()]);
}

#[request\is_post]
function add_action(request $request): response {
    if (request\is_post($request)) {
        album\add_album($request->params);
        return response\build_redirect('/albums');
    }
    return view\render('albums/add');
}

#[request\is_post]
function edit_action(request $request, array $params): response {
    $id = $params['id'];
    if (request\is_post($request)) {
        album\update_album($id, $request->params);
        return response\build_redirect('/albums');
    }
    $album = album\get_album($id);
    return $album
        ? view\render('albums/edit', ['album' => $album])
        : view\error("Album $id not found.", response\response_status_not_found);
}

#[request\is_post]
function delete_action(request $request, array $params): response {
    album\delete_album($params['id']);
    return response\build_redirect('/albums');
}
