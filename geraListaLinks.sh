#!/bin/bash

#
# Web scraper que gera uma lista  de links de pdf de decisoes judiciais
#

# Link das base de dados
dataSet_1="http://eliasdeoliveira.com.br/seminars/pagina-rouboSimples.html"
dataSet_2="http://eliasdeoliveira.com.br/seminars/pagina-furtoSimples.html"
dataSet_3="http://eliasdeoliveira.com.br/seminars/pagina-rouboMajorado.html"
dataSet_4="http://eliasdeoliveira.com.br/seminars/pagina-rouboQualificado.html"

# Obtem o conteudo html (fetch) da pagina do data set
htmlContent=$(curl -s "$dataSet_1")

# Obtem a lista de todos os nomes dos PDFs da pagina
pdfNames=$(echo "$htmlContent" | grep -oP '(?<=./tmp/)[^"]*(?=.pdf)')

# Cria o arquivo para receber os links
arquivo="links/listaLinks.txt"
touch $arquivo

# Define o limite para o numero de links/PDFs
limit=10
count=0

# imprime link no arquivo de lista
for pdfName in $pdfNames; do
    if [ $count -ge $limit ]; then
        break
    fi
    echo http://eliasdeoliveira.com.br/seminars/tmp/${pdfName}.pdf >> $arquivo
    #((count++))
done