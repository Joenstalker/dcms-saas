@echo off
REM Add DCMS local development entries to hosts file
REM Run this file as Administrator

echo Adding dcmsapp.local entries to hosts file...
echo.

REM Add entries to hosts file
(
echo.
echo # DCMS Development Environments
echo 127.0.0.1       dcmsapp.local
echo 127.0.0.1       dental.dcmsapp.local
echo 127.0.0.1       dentalcare.dcmsapp.local
echo 127.0.0.1       dddc.dcmsapp.local
) >> C:\Windows\System32\drivers\etc\hosts

echo Hosts file updated!
echo.
echo Flushing DNS cache...
ipconfig /flushdns

echo.
echo Done! You can now access:
echo   http://dcmsapp.local:8000
echo   http://dental.dcmsapp.local:8000
echo   http://dentalcare.dcmsapp.local:8000
echo   http://dddc.dcmsapp.local:8000

pause
