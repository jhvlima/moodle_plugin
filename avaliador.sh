#!/bin/bash

atividade="atividades/docs-tjsp-t2-1-2-21"
rm -f resultados.csv

# Extrai da resposta de cada aluno
for aluno in $atividade/*/; do
	resposta="$aluno/resposta.txt"
	nota=0

	idAluno=$(basename $aluno | awk -F "-" '{print $1}')
	qntLinks=$(grep "$idAluno-" "$atividade/feedback.csv" | grep  -c 'link')
	qntAnotacoes=0
	processosVerificados=0
	reusEncontrados=0
	sentenasEncontradas=0
	echo "PRCOCESSANDO O ALUNO: $aluno"

	# Extrai da resposta do aluno as linhas no formato # processo | nome | tempo
	if [[ -f "$resposta" ]]; then
		grep '<p>' "$resposta" | sed 's/<[^>]*>/ /g' | sed 's/ #/\n#/g' | sed 's/&nbsp/ /g' | grep '#' > "$aluno/respostaArmazenada.txt"
		qntAnotacoes=$(grep -c '#' $aluno/respostaArmazenada.txt)

		while IFS= read -r anotacao; do
			#echo "$anotacao"
			#echo $anotacao | awk -F "|" '{print NF}'

			# Verifica se a quantidade de campos na resposta está correta
			if [[ $(echo $anotacao | awk -F "|" '{print NF}') -ne 3 ]]; then
				echo "ANOTACAO ERRADA: $anotacao"
			else

			# Pega o número do processo
			processo=$(echo "$anotacao" | awk -F "|" '{print $1}' | grep -Eo '[0-9]{7}-[0-9]{2}\.[0-9]{4}\.[0-9]\.[0-9]{2}\.[0-9]{4}')

			# Pega o réu do processo
			reu=$(echo "$anotacao" | awk -F "|" '{print $2}')

			# Pega a sentenca do processo
			sentenca=$(echo "$anotacao" | awk -F "|" '{print $3}')

				# Verifica se o nome está no documento
				if [[ -n "$processo" ]]; then
					if grep -q "$processo" <(ls ~/JoaoLima/decisoesJudiciaisTxt/); then
						((processosVerificados++))

						if [[ -n "$reu" ]]; then
							if grep -q "$reu" ~/JoaoLima/decisoesJudiciaisTxt/"$processo".txt; then
								((reusEncontrados++))
								# verifica a sentenca esta no documento
								#grep "$sentenca" ~/JoaoLima/decisoesJudiciaisTxt/"$processo".txt
								if grep -q "$sentenca" ~/JoaoLima/decisoesJudiciaisTxt/"$processo".txt; then
									((nota++))
									((sentenasEncontradas++))
								fi
							fi
						fi
					fi
				fi
			fi
		done < "$aluno/respostaArmazenada.txt"
	else
       		echo "Resposta nao encontrada para $aluno"
	fi
	echo "QTD DE ANOTACOES ENVIADAS PELO ALUNO: $qntAnotacoes"
	echo "NOTA FINAL DO ALUNO: $nota"
	# coloca a nota do aluno no csv dentro de diretorio do aluno
	echo "$idAluno;$qntLinks;$qntAnotacoes;$processosVerificados;$reusEncontrados;$sentenasEncontradas" >> $aluno/vet-anotacao-sentencas.csv
done
