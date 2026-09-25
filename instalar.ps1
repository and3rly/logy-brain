# Conecta este brain con el proyecto Logy para Claude Code.
# Uso: clonar logy-brain dentro de la raiz del proyecto (logy/brain) y ejecutar:
#   powershell -ExecutionPolicy Bypass -File brain\instalar.ps1
# Crea en la raiz del proyecto:
#   - CLAUDE.md             -> importa brain/instrucciones.md
#   - .claude/settings.json -> commit y push piden confirmacion
# No sobrescribe archivos que ya existan.
# (Sin acentos a proposito: Windows PowerShell 5.1 lee los .ps1 sin BOM como ANSI.)

$ErrorActionPreference = 'Stop'
$brain = $PSScriptRoot
$raiz = Split-Path $brain -Parent

if ((Split-Path $brain -Leaf) -ne 'brain') {
    Write-Warning "Este script debe estar en una carpeta llamada 'brain' dentro del proyecto. Ubicacion actual: $brain"
    exit 1
}

$claudeMd = Join-Path $raiz 'CLAUDE.md'
if (Test-Path $claudeMd) {
    if ([IO.File]::ReadAllText($claudeMd) -match '@brain/instrucciones\.md') {
        Write-Host "OK   CLAUDE.md ya existe y apunta al brain."
    } else {
        Write-Warning "CLAUDE.md ya existe pero NO importa el brain. Agregale esta linea a mano: @brain/instrucciones.md"
    }
} else {
    Copy-Item (Join-Path $brain 'plantillas\CLAUDE.md') $claudeMd
    Write-Host "NEW  CLAUDE.md creado."
}

$settingsDir = Join-Path $raiz '.claude'
$settings = Join-Path $settingsDir 'settings.json'
if (Test-Path $settings) {
    Write-Host "OK   .claude/settings.json ya existe; no se modifica. Comparalo con brain/plantillas/settings.json."
} else {
    New-Item -ItemType Directory -Force $settingsDir | Out-Null
    Copy-Item (Join-Path $brain 'plantillas\settings.json') $settings
    Write-Host "NEW  .claude/settings.json creado."
}

Write-Host "Listo. Abre Claude Code en: $raiz"
