@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../nesbot/carbon/bin/upgrade-carbon
php "%BIN_TARGET%" %*
