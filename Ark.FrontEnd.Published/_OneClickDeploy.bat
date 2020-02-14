@ECHO OFF

for %%I in (.) do set CurrDirName=%%~nxI

:choice
set /P c=Deploy %CurrDirName%?[Y/N]
if /I "%c%" EQU "Y" goto :somewhere
if /I "%c%" EQU "N" goto :somewhere_else
goto :choice


:somewhere

echo Building Release.Ark.FrontEnd ...
echo Copying.. Release.Ark.FrontEnd ...

copy "C:\Projects\PHP\Ark" "C:\Projects\Published\Release.Ark\Ark.FrontEnd.Published" /y

echo Copy Done Successfully. 
echo Deploying to git .... 

cd C:\Projects\Published\Release.Ark
git add *.*
git commit -m "One Click Deploy"

git push

#echo Deploying to server: Portal
#plink -pw Ark@123456 arkweb@52.55.94.8 -m oneclick.portal.sh -batch

echo Deployment done. Have a good day :)
pause
exit

:somewhere_else

echo Deployment Canceled
pause
exit