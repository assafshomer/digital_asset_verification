#!/bin/bash

# get url from cmd arg
URL=$1;

# change to current dir
cd ${0%/*};

# extract domain from url
if [[ $URL =~ https://(.+)$ ]]; then
 DOMAIN=${BASH_REMATCH[1]};
fi;

# extract tag from domain www.foo.bar -> www_foo_bar
TAG="$(echo $DOMAIN|awk '{gsub("\\.", "_")}1';)"

# define directory for cert files (create and chmod it to 777 before)
# CDIR='tmp/';
CDIR=$2;

# define the name of certificate files
CAF=$CDIR'CAbundle.crt';
MZF=$CDIR'mozbunle.crt';

# define aux file suffixes 
# certificate chain files
# LEVEL='level';
LEVEL=$3;
# Authority information access 
AIA='aia';
# verification result
RESULT='result';

# define path to mozilla.org ca bundle
MB='https://raw.githubusercontent.com/bagder/ca-bundle/master/ca-bundle.crt';

# copy the system CA certificates file for local use
cat /etc/ssl/certs/ca-certificates.crt > $CAF;

# add auto converted CA Certs from mozilla.org
wget -q -O $MZF $MB;
cat $MZF >> $CAF;

# import the certificate chain to files level0.crt, level1.crt etc
openssl s_client -showcerts -connect \
$DOMAIN:443 -CAfile $CAF < /dev/null | \
awk -v c=-1 '/-----BEGIN CERTIFICATE-----/{inc=1;c++} 
             inc {print > ("'$CDIR$TAG'_'$LEVEL'" c ".crt")}
             /---END CERTIFICATE-----/{inc=0}'


# grab Authority information access url from certificates and save to aia#.txt
for i in "$CDIR$TAG"_"$LEVEL"?.crt; do
	I=$(echo "$i" | sed -e s/[^0-9]//g); 
	output=$(openssl x509 -noout -text -in "$i" | grep OCSP);
	if [[ $output =~ URI:(.+)$ ]]; then
			aia=${BASH_REMATCH[1]};
	    echo $aia > $CDIR$TAG"_"$AIA$I".txt";
	fi
done

# add certificate chain to the CAbundle
q='{0,';
for i in "$CDIR$TAG"_"$LEVEL"?.crt; do
	I=$(echo "$i" | sed -e s/[^0-9]//g);
	q=$q$(($I+1))','
done
q=$CDIR$TAG'/'$LEVEL${q::-3}'}.crt'
cmd='cat /etc/ssl/certs/ca-certificates.crt '$q' > '$CAF
eval $cmd

# verifying the chain 
for i in "$CDIR$TAG"_"$LEVEL"?.crt; do
	I=$(echo "$i" | sed -e s/[^0-9]//g)	
	for j in "$CDIR$TAG"_"$LEVEL"?.crt; do
		J=$(echo "$j" | sed -e s/[^0-9]//g)
  	if [ "$J" -eq $(($I+1)) ]; then
			aiaurl=$(cat "$CDIR$TAG"_$AIA$I.txt)
			serial=$(openssl x509 -serial -noout -in $i); 
			serial=${serial#*=};
			openssl ocsp -issuer $j -CAfile $CAF -VAfile $CAF -url $aiaurl -serial "0x${serial}" -out $CDIR$TAG"_"$RESULT$I".txt"
		fi		
	done
done

# echo file indexes so that the max is the number returned to php
for i in "$CDIR$TAG"_"$LEVEL"?.crt; do
	echo $(echo "$i" | sed -e s/[^0-9]//g)	
done