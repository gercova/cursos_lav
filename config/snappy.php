<?php

return [
    'pdf' => [
        'enabled' => true,
        // Importante: apunta al .exe de Windows (y con comillas para espacios)
        'binary'  => '"' . env('WKHTML_PDF_BINARY', 'C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe') . '"',
        'timeout' => false,
        'options' => [
            'page-size'     => 'A4',
            'orientation'   => 'Landscape',
            'margin-top'    => '0mm',
            'margin-right'  => '0mm',
            'margin-bottom' => '0mm',
            'margin-left'   => '0mm',
            'encoding'      => 'UTF-8',
            'no-outline'    => true,
            'disable-forms' => true,
            // Para assets locales (img/css con public_path/storage_path)
            'enable-local-file-access' => true,
        ],
        'env' => [],
    ],

    'image' => [
        'enabled'   => true,
        'binary'    => '"' . env('WKHTML_IMG_BINARY', 'C:\Program Files\wkhtmltopdf\bin\wkhtmltoimage.exe') . '"',
        'timeout'   => false,
        'options'   => [],
        'env'       => [],
    ],
];
