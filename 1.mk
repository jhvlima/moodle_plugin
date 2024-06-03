all: x

formataLinks:
	sed -i 's#^#<li>#g; s#$$#</li>#g;' 1.txt
	sed -i '1i <ol>' 1.txt
	sed -i '$$a\''\n''</ol>' 1.txt