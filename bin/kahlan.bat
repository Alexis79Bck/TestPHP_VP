@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../vendor/kahlan/kahlan/bin/kahlan
php "%BIN_TARGET%" %*
