<?php
$headers = [
    'name' => ['nombre', 'comercial', 'artículo', 'producto', 'name'],
    'barcode' => ['código', 'barras', 'ean', 'barcode', 'qr', 'barra'],
    'category' => ['categoría', 'categoria', 'familia', 'category'],
    'serialNumber' => ['serie', 's/n', 'serial'],
];
$headerCells = [
    'A' => 'Nombre Comercial *',
    'B' => 'Código de Barras *',
    'C' => 'Categoría *',
    'Q' => 'Número de Serie'
];
$map = [];
foreach ($headers as $field => $keywords) {
    foreach ($headerCells as $col => $cell) {
        foreach ($keywords as $k) {
            if (mb_strpos(mb_strtolower($cell), mb_strtolower($k)) !== false) {
                $map[$field] = $col;
                break 2;
            }
        }
    }
}
print_r($map);
