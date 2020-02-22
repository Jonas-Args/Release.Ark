@ECHO OFF

for %%I in (.) do set CurrDirName=%%~nxI

:choice
set /P c=Deploy %CurrDirName%?[Y/N]
if /I "%c%" EQU "Y" goto :somewhere
if /I "%c%" EQU "N" goto :somewhere_else
goto :choice


:somewhere

echo Building Release.Ark.API ...
dotnet publish Ark.API.sln -o C:\Projects\Published\Release.Ark\Ark.Api.Published


#echo Building Release.Ark.FrontEnd ...
#dotnet publish Ark.FrontEnd.sln -o C:\Projects\Published\Release.Ark\Ark.FrontEnd.Published


echo Deploying to git ...
cd C:\Projects\Published\Release.Ark
git add *.*
git commit -m "One Click Deploy"


git push


echo Deployment done. Have a good day :)

pause
exit

:somewhere_else

echo Deployment Canceled
pause
exit