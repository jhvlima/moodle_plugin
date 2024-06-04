#!/bin/bash

# Web scraper que baixa os pdf de decisoes judiciais

# Link das base de dados
dataSets=(  "http://eliasdeoliveira.com.br/seminars/pagina-rouboSimples.html"
            "http://eliasdeoliveira.com.br/seminars/pagina-furtoSimples.html"
            "http://eliasdeoliveira.com.br/seminars/pagina-rouboMajorado.html"
            "http://eliasdeoliveira.com.br/seminars/pagina-rouboQualificado.html")


# Obtem o conteudo html (fetch) da pagina do data set
for dataSet in $dataSets; do
   htmlContent=$(curl -s "$dataSet")
done

# Obtem a lista de todos os nomes dos PDFs da pagina
pdfNames=$(echo "$htmlContent" | grep -oP '(?<=./tmp/)[^"]*(?=.pdf)')

# Cria diretorio do dataSet com os PDFs
dirSet="dataSetDecisoesJudiciais"
mkdir -p $dirSet

# Define o limite para o numero de PDFs para download e convercao
limit=10
count=0

# Download dos PDFs
for pdfName in $pdfNames; do
    if [ $count -ge $limit ]; then
        break
    fi
    wget http://eliasdeoliveira.com.br/seminars/tmp/${pdfName}.pdf -P $dirSet/
    #((count++))
done
# Remove replicas
rm -Rf $dirSet/*.[0-9]

# Converte PDFs para texto usando pdftotext
mkdir -p dataSetTxt
count=0
for pdf_file in $dirSet/*.pdf; do
    if [ $count -ge $limit ]; then
        break
    fi
    pdftotext "$pdf_file" "dataSetTxt/$(basename "${pdf_file%.pdf}").txt"
    ((count++))
done