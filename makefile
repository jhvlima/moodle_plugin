DIR = atividades

ARQ_LINKS= ""

all: fazTudo

download:
	mkdir -p $(DIR)
	php5.6 download.php -d ./$(DIR)/ --conf Moodle6.conf

upload:
	php5.6 upload.php -d ./$(DIR)/ --conf Moodle6.conf

fazTudo:
	php5.6 download.php -d $(DIR) --conf Moodle6.conf
	./checa_pastas.sh $(DIR)
	php5.6 upload.php -d $(DIR) --conf Moodle6.conf

formataLinks:
#shuf -n 1 listaLinks.txt >> $(ARQ_LINKS)
	sed -i 's#^#<li><a herf=#g; s#$$#>link</a></li>#g;' $(ARQ_LINKS)
	sed -i '1i <ol>' $(ARQ_LINKS)
	sed -i '$$a\''\n''</ol>' $(ARQ_LINKS)
