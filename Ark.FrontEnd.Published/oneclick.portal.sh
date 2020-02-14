#!/bin/bash
clear
echo "Change Directory to: /opt/lampp/htdocs/Release.Ark/"
cd /opt/lampp/htdocs

echo "Git Hard Reset.."
echo ""
sudo rm -r Release.Ark
echo "Git cloning: release-master"

sudo git clone https://github.com/kaizendevsio/Release.CryptoCityWallet.git Release.Ark

echo "Git clone successful.."

#clear
echo "Deployment Done. Have a good day :)"