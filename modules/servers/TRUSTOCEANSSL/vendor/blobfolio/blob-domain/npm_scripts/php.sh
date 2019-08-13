#!/bin/bash
#
# NPM: PHP Coding Standards
#
# These are a little too cumbersome to deal with inside NPM.
##



# Check dependencies.
if [ ! -e "./node_modules/blobfolio-phpcs/lib/vendor/bin/phpcs" ]; then
	echo -e "\033[31;1mError:\033[0m blobfolio-phpcs is required."
	echo -e "\033[96;1mFix:\033[0m npm i git+ssh://git@blobfolio.com:3417/blobfolio-phpcs"
	exit 1
fi



./node_modules/blobfolio-phpcs/lib/vendor/bin/phpcs --colors --standard=Blobfolio --encoding=utf8 --report=full --extensions=php --parallel=4 ./
if [ $? -eq 0 ]; then
	# Everything was fine; just log to the terminal and exit.
	echo -e "\033[32;1mSuccess:\033[0m Your PHP is looking good!"
else
	# Try one more time without warnings to see if this is bad or not.
	./node_modules/blobfolio-phpcs/lib/vendor/bin/phpcs --colors --standard=Blobfolio --encoding=utf8 --report=notifysend --extensions=php --parallel=4 ./
fi



exit 0
