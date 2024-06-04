#!/bin/bash

DIR_LINKS="./links/"

# Verifica se foi fornecido um diretório como argumento
if [ $# -ne 1 ]; then
    echo "Uso: $0 <diretório>"
    exit 1
fi

# Armazena o diretório fornecido como argumento
diretorio=$1

# Verifica se o diretório existe
if [ ! -d "$diretorio" ]; then
    echo "O diretório '$diretorio' não existe."
    exit 1https://stackoverflow.com/users
fi

# Percorre todas as subpastas do diretório fornecido
for subpasta in "$diretorio"/*; do
    # Verifica se é um diretório
    if [ -d "$subpasta" ]; then
        #echo "Executando código na subpasta: $subpasta"
        
        python3 verifica_pastas.py $subpasta $DIR_LINKS
        cd $subpasta && rm notastreino.csv && cp feedback.csv notastreino.csv
        
    fi
done
