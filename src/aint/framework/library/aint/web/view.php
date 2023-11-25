<?php

namespace aint\web\view;

use aint\templating;
use aint\web\response;

/**
 * Custom templates parameters
 * path and file extension
 */
const templates_path = 'app/templates/',
      template_ext = '.phtml';

/**
 * Layout template, for 2-step rendering strategy
 */
const layout_template = 'layout';

/**
 * Variable to hold inner template rendering result
 * within the layout
 */
const layout_content_var = 'content';

/**
 * Rendering for controller actions
 * 2-step strategy
 * returns http response data
 */
function render(string $template, array $vars = [], int $status = response\response_status_ok): response
{
    return response\build_response(
        render_template(layout_template, [
            layout_content_var => render_template($template, $vars)
        ]),
        $status
    );
}

/**
 * Renders one template from `templates` directory
 */
function render_template(string $template, array $vars = []): string
{
    return templating\render_template(
        templates_path . $template . template_ext,
        $vars
    );
}

function error(string $message, int $status): response
{
    $vars = ['message' => $message];
    return render('errors/error', $vars, $status);
}
