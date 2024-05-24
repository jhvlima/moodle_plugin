DIR = moodle/teste

download:
	php5.6 download.php -d ./$(DIR)/ --conf Moodle6.conf

upload:
	php5.6 upload.php -d ./$(DIR)/ --conf Moodle6.conf

enviaLinks:
# executa o script para gerar o lista de links
	./geraListaLinks.sh

# pega o numero de linhas (links)
	wc -l listaLinks

# coloca um link aleatorio para cada resposta de um aluno
