@echo off
setlocal
set "HOSTS=C:\Windows\System32\drivers\etc\hosts"
set "BASE=dcmsapp.local"

if "%~1"=="" goto add_defaults

call :add_domain "%~1.%BASE%"
goto done

:add_defaults
call :add_domain "%BASE%"
call :add_domain "dental.%BASE%"
call :add_domain "dentalcare.%BASE%"
call :add_domain "dddc.%BASE%"
call :add_domain "cudalblanco.%BASE%"

:done
echo.
echo Hosts file updated!
echo.
echo Flushing DNS cache...
ipconfig /flushdns
echo.
pause

:add_domain
findstr /I /C:" %~1" "%HOSTS%" >nul 2>&1
if errorlevel 1 (
    echo 127.0.0.1       %~1>>"%HOSTS%"
)
goto :eof
