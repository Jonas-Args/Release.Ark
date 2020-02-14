@ECHO OFF

echo Building Release.Ark.FrontEnd ...
echo Copying.. Release.Ark.FrontEnd ...

copy "C:\Projects\PHP\Ark" "C:\Projects\Published\Release.Ark\Ark.FrontEnd.Published" /y

echo Copy Done Successfully. 
echo Deploying to git .... 

cd C:\Projects\Published\Release.Ark
git add *.*
git commit -m "One Click Deploy"

git push

echo Deploying to server: Portal
plink -pw Ark@123456 arkweb@52.55.94.8 -m oneclick.portal.sh -batch
