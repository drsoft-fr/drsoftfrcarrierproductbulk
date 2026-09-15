MODULE_NAME := drsoftfrcarrierproductbulk
VERSION := 1.1.1
COMPOSER ?= composer
ARCHIVE := $(CURDIR)/dist/$(MODULE_NAME)-$(VERSION).zip

.PHONY: build package clean

# Rebuild the runtime autoloader and the vendor directory used by the module.
# composer.lock is intentionally not required: it is ignored by the module
# repository and this module currently has no third-party package dependency.
build:
	$(COMPOSER) install --no-dev --prefer-dist --optimize-autoloader --no-interaction

# Build a self-contained archive that can be uploaded directly to PrestaShop.
# vendor/ is deliberately kept; composer manifests are development/build files
# and are not needed by the shop at runtime.
package: build
	mkdir -p dist
	rm -f "$(ARCHIVE)"
	cd .. && zip -rq "$(ARCHIVE)" "$(MODULE_NAME)" \
		-x "$(MODULE_NAME)/.git/*" \
		   "$(MODULE_NAME)/.idea/*" \
		   "$(MODULE_NAME)/dist/*" \
		   "$(MODULE_NAME)/docs/*" \
		   "$(MODULE_NAME)/.gitignore" \
		   "$(MODULE_NAME)/Makefile" \
		   "$(MODULE_NAME)/composer.json" \
		   "$(MODULE_NAME)/composer.lock" \
		   "$(MODULE_NAME)/config_*.xml" \
		   "$(MODULE_NAME)/*.log"
	@echo "Archive créée : $(ARCHIVE)"

clean:
	rm -rf dist
