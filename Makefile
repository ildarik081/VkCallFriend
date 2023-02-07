ROOT_DIR=$(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))
SRC_DIR=$(ROOT_DIR)/src
BIN_DIR=$(ROOT_DIR)/bin
VENDOR_BIN_DIR=$(ROOT_DIR)/vendor/bin

include .env
-include .env.local

ifeq ($(APP_ENV), prod)
	composer_params = --prefer-dist --optimize-autoloader --no-interaction --no-ansi --no-dev
else
	composer_params = --prefer-dist --optimize-autoloader --no-interaction
endif

phpcs:
	@$(VENDOR_BIN_DIR)/phpcs

phpmd:
	@$(VENDOR_BIN_DIR)/phpmd src text --exclude src/Kernel.php controversial,./phpmd.xml

phpstan:
	$(VENDOR_BIN_DIR)/phpstan analyze -c $(ROOT_DIR)/phpstan.neon

psalm:
	./vendor/bin/psalm
