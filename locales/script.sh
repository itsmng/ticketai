#! /bin/bash

for n in *.po
do
	msgfmt -o ${n/.po/}.mo -v ./$n
done
