#!/usr/bin/env sh

LAT=53.1511173
LON=6.756634599999984
RADIUS=5.0  # 0.5, 2.0, 5.0, 10.0 and 25.0. default 5.0
MAXNUMBEROFITEMS=10 # 10 and 25. default 10
OFFSET=0
URLBASE=https://api.politie.nl/politiebureaus/v1

curl "${URLBASE}?lat=${LAT}&lon=${LON}&radius=${RADIUS}&maxnumberofitems=${MAXNUMBEROFITEMS}&offset=${OFFSET}" -o some2.json