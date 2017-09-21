#!/usr/bin/env bash

# 請在 penny 的專案根目錄以 bin/build-and-push.sh 的方式來執行此腳本
# - 參數
#   $1 : 版本編號
#   $2 : Docker 儲存庫

version=${1-}
repository=${2-slimek/penny}

if [ -z $version ]; then
  echo "usage: bin/build-and-push.sh <version>"
  exit 1
fi

docker build -t $repository:$version .
docker push $repository:$version
