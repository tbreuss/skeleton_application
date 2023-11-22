<?php
namespace app\view\helpers;

use aint\mvc\routing;
use app;
use app\view;

/**
 * Separator for the <title/> tag content parts
 */
const head_title_separator = ' >> ';

/**
 * Stores static title value and appends any prepents $test
 * using head_title_separator as delimiter.
 *
 * Returns html tag prepared <title>...</title>
 */
function head_title(?string $text = null): string {
    static $title = '';
    if ($text !== null)
        if ($title === '')
            $title = htmlspecialchars($text);
        else
            $title = htmlspecialchars($text . head_title_separator) . $title;
    return '<title>' . $title . '</title>';
}

/**
 * Converts action function name and the parameters list back to URI
 */
function uri(string $route_action, array $route_params = []): string {
    return routing\assemble_segment($route_action, $route_params);
}

/**
 * Translates given piece of text, using model's translator
 */
function translate(string $text): string {
    return app\translate($text);
}

function album_form(string $action, array $album = []): string {
    $default_album_data = [
        'title' => '',
        'artist' => '',
    ];
    $album = array_merge($default_album_data, $album);
    return view\render_template('album_form', ['album' => $album, 'action' => $action]);
}
