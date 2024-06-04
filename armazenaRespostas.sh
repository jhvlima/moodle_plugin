#!/bin/bash

resposta="exResposta.html"
respostaArmazenadas="respostas.csv"

# extrai da resposta do aluno as linhas no formato # processo | nome | tempo
grep '<p>' $resposta | sed 's/<[^>]*>//g' >> $respostaArmazenadas

# Process each HTML file in the directory
input_file=$respostaArmazenadas    # Your input file
output_file="output.csv"     # Output CSV file

# formata as linhas em um csv
grep '#' "$input_file" | sed 's/# //; s/ | /;/g' > "$output_file"


# novos comandos
awk -F "#" '{print $2}' $resposta | awk -F "|" '{print $1";"$2";"$3}'
