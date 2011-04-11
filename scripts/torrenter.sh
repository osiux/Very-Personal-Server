#!/bin/sh
case "$1" in
'add')
echo "Torrent Load Service. Please use only urls with .torrents at filename"
echo "URL:" $2
wget -P /mnt/Torrents/  $2 
LPGT=${2##*/}
echo "I haz Torrents/"$LPGT
echo "Im in your daemonz loading your torrentz"
transmission-remote -a "download dir here"$LPGT
echo "KTHNXBYE"
;;
'slow')
transmission-remote -t daemon -as
;;
'speed')
transmission-remote -t daemon -AS
;;
'list')
transmission-remote -t daemon -l
;;
'info')
transmission-remote -t daemon -si
;;
esac 
