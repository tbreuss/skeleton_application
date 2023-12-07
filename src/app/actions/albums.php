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

function add_form_action(): response {
    return view\render('albums/add');
}

#[request\is_post]
function add_action(request $request): response {
    album\add_album($request->params);
    return response\build_redirect('/albums');
}

function edit_form_action(request $request, array $params): response {
    $id = $params['id'];
    $album = album\get_album($id);
    return $album
        ? view\render('albums/edit', ['album' => $album])
        : view\error("Album $id not found.", response\status_not_found);
}

#[request\is_post]
function edit_action(request $request, array $params): response {
    album\update_album($params['id'], $request->params);
    return response\build_redirect('/albums');
}

function delete_action(request $request, array $params): response {
    album\delete_album($params['id']);
    return response\build_redirect('/albums');
}
