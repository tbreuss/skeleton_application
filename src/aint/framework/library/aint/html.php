<?php

namespace aint\html;

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
            $title = h($text);
        else
            $title = h($text . head_title_separator) . $title;
    return '<title>' . $title . '</title>';
}
