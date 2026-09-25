<?php
// Uso (desde logy/): php brain/herramientas/db_lectura.php resumen|columnas|fks|catalogo <tabla>
// Lee las credenciales de api/application/config/database.php (nunca se copian aquí).
// Lectura de la estructura de la BD. Sesión en modo READ ONLY: el servidor rechaza cualquier escritura.
define('BASEPATH', true);
define('ENVIRONMENT', 'development');
require __DIR__ . '/../../api/application/config/database.php';
$c = $db['default'];

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$m = new mysqli($c['hostname'], $c['username'], $c['password'], $c['database']);
$m->set_charset('utf8');
$m->query("SET SESSION TRANSACTION READ ONLY");
$m->query("START TRANSACTION READ ONLY");

function q($m, $sql) { return $m->query($sql)->fetch_all(MYSQLI_ASSOC); }
$schema = $m->real_escape_string($c['database']);
$modo = $argv[1] ?? 'resumen';

if ($modo === 'resumen') {
    echo "== Servidor: " . $m->server_info . "\n";
    echo "== Privilegios del usuario:\n";
    foreach (q($m, "SHOW GRANTS") as $g) echo "  " . array_values($g)[0] . "\n";
    echo "== Tablas y vistas:\n";
    foreach (q($m, "SELECT TABLE_NAME, TABLE_TYPE, TABLE_ROWS, TABLE_COMMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA='$schema' ORDER BY TABLE_NAME") as $t)
        printf("  %-40s %-10s ~%s filas %s\n", $t['TABLE_NAME'], $t['TABLE_TYPE'] === 'VIEW' ? 'VISTA' : '', $t['TABLE_ROWS'] ?? '-', $t['TABLE_COMMENT'] ? "// {$t['TABLE_COMMENT']}" : '');
    echo "== Rutinas y triggers:\n";
    foreach (q($m, "SELECT ROUTINE_TYPE, ROUTINE_NAME FROM information_schema.ROUTINES WHERE ROUTINE_SCHEMA='$schema'") as $r) echo "  {$r['ROUTINE_TYPE']} {$r['ROUTINE_NAME']}\n";
    foreach (q($m, "SELECT TRIGGER_NAME, EVENT_MANIPULATION, EVENT_OBJECT_TABLE FROM information_schema.TRIGGERS WHERE TRIGGER_SCHEMA='$schema'") as $r) echo "  TRIGGER {$r['TRIGGER_NAME']} ({$r['EVENT_MANIPULATION']} {$r['EVENT_OBJECT_TABLE']})\n";
} elseif ($modo === 'columnas') {
    $tabla = '';
    foreach (q($m, "SELECT TABLE_NAME, COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE, COLUMN_KEY, COLUMN_DEFAULT, EXTRA, COLUMN_COMMENT FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='$schema' ORDER BY TABLE_NAME, ORDINAL_POSITION") as $col) {
        if ($col['TABLE_NAME'] !== $tabla) { $tabla = $col['TABLE_NAME']; echo "\n[$tabla]\n"; }
        printf("  %-28s %-22s %s%s%s%s\n", $col['COLUMN_NAME'], $col['COLUMN_TYPE'], $col['IS_NULLABLE'] === 'NO' ? 'NN ' : '', $col['COLUMN_KEY'] ? "{$col['COLUMN_KEY']} " : '', $col['EXTRA'] ? "{$col['EXTRA']} " : '', $col['COLUMN_COMMENT'] ? "// {$col['COLUMN_COMMENT']}" : '');
    }
} elseif ($modo === 'fks') {
    foreach (q($m, "SELECT TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA='$schema' AND REFERENCED_TABLE_NAME IS NOT NULL ORDER BY TABLE_NAME") as $f)
        echo "  {$f['TABLE_NAME']}.{$f['COLUMN_NAME']} -> {$f['REFERENCED_TABLE_NAME']}.{$f['REFERENCED_COLUMN_NAME']}\n";
} elseif ($modo === 'catalogo' && isset($argv[2])) {
    // Muestra filas de tablas catálogo (pocas filas, sin datos personales)
    $t = preg_replace('/[^A-Za-z0-9_]/', '', $argv[2]);
    foreach (q($m, "SELECT * FROM `$t` LIMIT 30") as $r) echo "  " . json_encode($r, JSON_UNESCAPED_UNICODE) . "\n";
}
$m->query("ROLLBACK");
