node_modules := $(CURDIR)/node_modules
node_bin_dir := $(node_modules)/.bin

all: build

$(node_bin_dir):
	yarn

build: $(node_bin_dir)
	yarn run build

develop: $(node_bin_dir)
	yarn run develop

serve: $(node_bin_dir)
	yarn run serve

eslint-fix: $(node_bin_dir)
	yarn run eslint-fix

clean:
	rm -rf $(node_modules) .cache public
