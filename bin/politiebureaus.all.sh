#!/usr/bin/env sh

OFFSET=0
URLBASE=https://api.politie.nl/politiebureaus/v1/

curl "${URLBASE}all?offset=${OFFSET}" -o all.json