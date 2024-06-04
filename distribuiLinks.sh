#!/bin/bash

# 
# colocar links aleatorios no notastreino.csv
#

# Source and target files
SOURCE_FILE="links/listaLinks.txt"
TARGET_CSV="notastreino.csv"
TEMP_FILE="temp_target.csv"
QTD_LINKS=10

# Get all lines from the source file and shuffle them
SHUFFLED_LINES=$(shuf "$SOURCE_FILE")


sed -i 's#^#<li><a herf=#g; s#$#>link</a></li>#g;' $ARQ_LINKS
sed -i '1i <ol>' $ARQ_LINKS
sed -i '$a\''\n''</ol>' $ARQ_LINKS

# Add a new column to each line by pasting the shuffled lines
#paste -d ';' "$TARGET_CSV" <(echo "$SHUFFLED_LINES" | head -n "$QTD_LINKS") > "$TEMP_FILE"
echo $ARQ_LINKS > $TARGET_CSV
# Replace the target CSV with the updated one
#mv "$TEMP_FILE" "$TARGET_CSV"

# para cada linha do notas treino 
#echo diretorio/atividade/$notastreino

#echo diretorio/atividade/*

