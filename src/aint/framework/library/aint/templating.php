<?php
/**
 * Simple, PHP-based template engine
 */
namespace aint\templating;

/**
 * Renders template specified
 *
 * Usage example:
 * render_template('/home/alex/my_templates/template.tpl', ['data' => 'Hello World'])
 */
function render_template(string $_template_, array $_data_ = []): string {
    ob_start();
    if (!empty($_data_))
        extract($_data_);
    include $_template_;
    return ob_get_clean();
}
