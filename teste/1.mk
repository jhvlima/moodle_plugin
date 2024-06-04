ARQ_LINKS="1.txt"
all: x

formataLinks:
	sed -i 's#^#<li><a herf=#g; s#$$#>link</a></li>#g;' $(ARQ_LINKS)
	sed -i '1i <ol>' $(ARQ_LINKS)
	sed -i '$$a\''\n''</ol>' $(ARQ_LINKS)
