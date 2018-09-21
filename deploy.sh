#!/usr/bin/env bash

set -e

SERVER=misc.heigl.org
mkdir -p ./tmp
export VERSION="`git describe --tags | cut -d "-" -f 1`"
TAG=`git describe --tags --abbrev=0`
TAG="master"
HASH=`git rev-parse $TAG`
echo "Deploying $VERSION ($TAG)"
SED="sed -i "
if [ `uname` = 'Darwin' ] ; then
    SED="sed -i \"\""
fi
git archive --format=tar $TAG | tar x -C tmp
cd tmp
composer update --no-dev --prefer-dist --ignore-platform-reqs

tar czf $HASH.tgz .

scp -r "./$HASH.tgz" "$SERVER:/var/www/org.keyprint/$HASH.tgz"
ssh $SERVER mkdir -p "/var/www/org.keyprint/$HASH"
ssh $SERVER tar xz -f "/var/www/org.keyprint/$HASH.tgz" -C "/var/www/org.keyprint/$HASH" && echo "Extracted tgz"
ssh $SERVER rm -rf "/var/www/org.keyprint/$HASH.tgz" && echo "Removed tgz"
ssh $SERVER "cd '/var/www/org.keyprint' && rm current && ln -s '$HASH' 'current'" && echo "Set symbolic link"
ssh $SERVER ls -tl -I home -I current | sed /^total/d |  tail -n +3 | cut -d " " -f 9 | rm -rf && echo "Removed obsolete folders"

cd ..

rm -rf ./tmp
