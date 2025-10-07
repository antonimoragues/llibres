<?php
$locale = 'es_ES';

// 1) Números y moneda
$fmtNum = new NumberFormatter($locale, NumberFormatter::DECIMAL);
echo $fmtNum->format(12345.678) . PHP_EOL;      // 12.345,678

$fmtCur = new NumberFormatter($locale, NumberFormatter::CURRENCY);
echo $fmtCur->formatCurrency(1234.5, 'EUR') . PHP_EOL; // 1.234,50 €

// 2) Fechas y horas
$fmtDate = new IntlDateFormatter(
    $locale,
    IntlDateFormatter::LONG,   // fecha
    IntlDateFormatter::SHORT,  // hora
    'Europe/Madrid',
    IntlDateFormatter::GREGORIAN
);
echo $fmtDate->format(new DateTime('2025-10-07 18:30')) . PHP_EOL;
// p.ej. "7 de octubre de 2025, 18:30"
