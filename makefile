DIR = moodle/atv2

atividade = docs-tjsp-t2-1-2-21
aluno = 30-76
path_aluno=$(DIR)/$(atividade)/notastreino.csv

download:
	mkdir -p $(DIR)
	php5.6 download.php -d ./$(DIR)/ --conf Moodle6.conf

upload:
	php5.6 upload.php -d ./$(DIR)/$(atividade) --conf Moodle6.conf

DIR_ATIVIDADES= ./atividades/
x:
	php5.6 download.php -d $(DIR_ATIVIDADES) --conf Moodle6.conf
	./checa_pastas.sh $(DIR_ATIVIDADES)
	php5.6 upload.php -d $(DIR_ATIVIDADES) --conf Moodle6.conf

ARQ_LINKS= ""
formataLinks:
	shuf -n 1 listaLinks.txt >> $(ARQ_LINKS)
	sed -i 's#^#<li>#g; s#$$#</li>#g;' $(ARQ_LINKS)
	sed -i '1i <ol>' $(ARQ_LINKS)
	sed -i '$$a\''\n''</ol>' $(ARQ_LINKS)


enviaLinks:
# executa o script para gerar o lista de links
#./geraListaLinks.sh

# pega o numero de linhas (links)
#wc -l listaLinks

# coloca um link aleatorio para cada resposta de um aluno
	awk -F ;  >>$(path_aluno)
	shuf -n 1 listaLinks.txt >> $(path_aluno)