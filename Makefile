PROJECT_NAME = $(shell basename "$$PWD")
WEBINST = ${PROJECT_NAME}.webinst

all: ${WEBINST}

tmp:
	mkdir -p tmp

tmp/info.xml: info.xml tmp
	cp -p "$<" "$@"

tmp/content.tar.gz: context context/* tmp
	tar -C "$<" -zcvf "$@" \
		--exclude ".DS_Store" \
		--exclude ".~*" \
		--exclude "*~" \
		.

${PROJECT_NAME}.webinst: tmp/info.xml tmp/content.tar.gz
	tar -C "tmp" -zcvf "$@" info.xml content.tar.gz

clean:
	rm -f "${WEBINST}"
	rm -Rf tmp
