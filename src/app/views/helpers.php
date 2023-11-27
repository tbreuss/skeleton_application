<?php
namespace app\views\helpers;

use aint\web\view;

function album_form(string $action, array $album = []): string {
    $default_album_data = [
        'title' => '',
        'artist' => '',
    ];
    $album = array_merge($default_album_data, $album);
    return view\render_template('albums/_form', ['album' => $album, 'action' => $action]);
}
