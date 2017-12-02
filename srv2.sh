#!/bin/bash

if [ $1 ]; then
    P=$1
else
    P="8001"
fi

if [ $2 ]; then
    IP_ATUAL=$2
else
    IP_ATUAL=`ifconfig eth0 | grep "inet end" | awk -F: '{ print $2 }' | awk '{ print $1 }'`
fi

if [$IP_ATUAL = '']; then
    IP_ATUAL=`ifconfig eth0 | grep "inet end" | awk -F: '{ print $2 }' | awk '{ print $1 }'`
fi

if [$IP_ATUAL = '']; then
    ETH=`ifconfig |grep eno | awk ' {print $1} '`
    IP_ATUAL=`ifconfig $ETH | grep "inet addr" | awk -F: '{ print $2 }' | awk '{ print $1 }'`
fi


if [ $3 ]; then
    D=$3
else
    D="/var/www/phpmyadmin"
fi


cd $D

echo $IP_ATUAL
echo $P
echo $D

php -S $IP_ATUAL:$P


# exibir o branch
# PS1='${debian_chroot:+($debian_chroot)}\[\033[01;32m\]\u@\h\[\033[00m\]:\[\033[01;34m\]\w\[\033[0;31m\]$(__git_ps1 "(%s)")\[\033[00m\]\$ '