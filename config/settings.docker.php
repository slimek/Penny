<?php

// 正式環境，在 Docker 容器中運行的組態檔

return [

    // Logging

    'enableFileLog' => filter_var(getenv('ENABLE_FILE_LOG'), FILTER_VALIDATE_BOOLEAN),

    'enableFluentdLog' => filter_var(getenv('ENABLE_FLUENTD_LOG'), FILTER_VALIDATE_BOOLEAN),
    'fluentdHost' => getenv('FLUENTD_HOST'),


    // Slim Framework Settings

    'displayErrorDetails' => false,    // 在 production 環境設為 false，其他環境設為 true
    'addContentLengthHeader' => false, // Slim Framework 的說明文件建議設為 false，所以我們照做～
];
