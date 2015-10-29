#!/bin/bash

# get url from cmd arg
URL=$1;

# extract domain from url
if [[ $URL =~ https://(.+)$ ]]; then
 DOMAIN=${BASH_REMATCH[1]};
fi;

# extract tag from domain www.foo.bar -> www_foo_bar
TAG="$(echo $DOMAIN|awk '{gsub("\\.", "_")}1';)"
# define directory for cert files
# CDIR='tmp/';
CDIR=$2;
# define aux file suffixes 
# LEVEL='level';
LEVEL=$3;

# change to current dir
cd ${0%/*};

data=$(openssl x509 -noout -subject -in "$CDIR$TAG"_"$LEVEL"0.crt);
echo $data;