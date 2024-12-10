<?php

return [
    'pdf' => [
        'enabled' => true,
        'binary' => env('WKHTMLTOPDF_BINARY', '"C:\\Program Files\\wkhtmltopdf\\bin\\wkhtmltopdf.exe"'), // Állítsd be az utat a wkhtmltopdf.exe-hez
        'timeout' => false,
        'options' => [],
    ],
    'image' => [
        'enabled' => true,
        'binary' => env('WKHTMLTOIMAGE_BINARY', '"C:\\Program Files\\wkhtmltopdf\\bin\\wkhtmltoimage.exe"'), // Ha képeket is használsz
        'timeout' => false,
        'options' => [],
    ],
];
