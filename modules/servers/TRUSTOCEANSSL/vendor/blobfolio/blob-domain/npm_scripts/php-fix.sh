#!/bin/bash
#
# NPM: PHP Coding Standards (Fix)
#
# These are a little too cumbersome to deal with inside NPM.
##



# Check dependencies.
if [ ! -e "./node_modules/blobfolio-phpcs/lib/vendor/bin/phpcbf" ]; then
	echo -e "\033[31;1mError:\033[0m blobfolio-phpcs is required."
	echo -e "\033[96;1mFix:\033[0m npm i git+ssh://git@blobfolio.com:3417/blobfolio-phpcs"
	exit 1
fi



# This is just one command, but it is a bitch.
./node_modules/blobfolio-phpcs/lib/vendor/bin/phpcbf --colors --standard=Blobfolio --encoding=utf8 --extensions=php --parallel=4 ./



exit 0
