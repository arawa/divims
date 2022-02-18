#!/usr/bin/env bash

##BEGIN-INSERT
NEW_DOMAIN=
SECRET=
ENABLE_IN_SCALELITE=
##END-INSERT


[ -z ${NEW_DOMAIN+x} ] && echo "New domain is not set" && exit 1;

echo "Add new BBB VM to pool"
COMMAND=( docker exec scalelite-api ./bin/rake servers:add[https://${NEW_DOMAIN}/bigbluebutton/api,${SECRET},1] )
RESULT=$("${COMMAND[@]}")
echo "$RESULT"

if [ $ENABLE_IN_SCALELITE = "true" ]; then
    echo "Enable new VM"
    id=$(echo "$RESULT" | grep "^id" | awk -F ":" '{print $2}' | tr -d ' ')
    COMMAND=( docker exec scalelite-api ./bin/rake servers:enable[$id] )
    RESULT=$("${COMMAND[@]}")
    echo "$RESULT"
fi

#COMMAND=( docker exec scalelite-api ./bin/rake servers )
#RESULT=$("${COMMAND[@]}")
#echo "$RESULT"
