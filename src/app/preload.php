<?php

(function () {
    $files = [
        dirname(__DIR__, 2) . '/vendor/aint/framework/library',
        __DIR__,
        dirname(__DIR__) . '/app.php',
    ];
    foreach ($files as $f) {
        if (is_file($f))
            require $f;
        else {
            $rdi = new RecursiveDirectoryIterator($f, RecursiveDirectoryIterator::SKIP_DOTS);
            $rii = new RecursiveIteratorIterator($rdi);
            foreach ($rii as $f) {
                if ($f->isDir()) continue;
                if ($f->getExtension() !== 'php') continue;
                if ($f->getPathname() === __FILE__) continue;
                require $f->getPathname();
            }
        }
    }
})();
