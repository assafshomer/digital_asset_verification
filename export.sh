#!/bin/bash
AID="U9a36XP1UwL5pxaYYiZYJ86sUqWAJ2dGbLaer";
mkdir -p prod;
cp -R domain prod;
cp -R social prod;
cp -R fixtures prod;
rm -f prod/fixtures/*.*;
rm -rf prod/fixtures/images;
cp "fixtures/verified_"$AID"_bofa.json" prod/fixtures ;
cp "fixtures/verified_"$AID"_colu.json" prod/fixtures ;
cp README.md prod/
cp json_reader.php prod
cp asset_verifier.php prod
cp api.php prod
rm -f prod/domain/certs/*.txt;
rm -f prod/domain/certs/*.crt;
rm -f prod/social/facebook_verifier_userpage.php;
COM="$(git rev-parse --short HEAD)"
zip -r "zip/verifications_"$COM".zip" prod
echo "production folder is zip/verifications_"$COM".zip"