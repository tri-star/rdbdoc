<?php

/**
 * ドキュメント生成プラグイン - Excel
 *
 * このファイルはプラグインのインスタンスを生成してreturnします。
 */

require_once __DIR__ . '/DocumentWriterExcel.php';

$plugin = new DocumentWriterExcel();

return $plugin;
