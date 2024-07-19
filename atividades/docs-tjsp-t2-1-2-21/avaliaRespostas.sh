#!/bin/bash
feedback="feedback.csv"

# pega cada numero de processo que anotado pelo aluno (resposta.csv)
#awk -F "#" '{print $2}' $resposta | awk -F "|" '{print $1}' | sort | uniq

for aluno in ./*/ ; do

    # resposta de cada aluno
    resposta="$aluno""resposta.txt"
    #cat "$resposta"

    # pega a cada numero de processo que foi enviado para o aluno (feedback.csv)
    # tem que pegar oque esta entre <a href='http://eliasdeoliveira.com.br/seminars/tmp/[numero].pdf'>
    echo "Processos enviados para o aluno $(cat "$aluno""name.info")"
    #grep "$resposta" "$feedback" | grep -oP "(?<=http://eliasdeoliveira.com.br/seminars/tmp/)[0-9\-\.]+(?=\.pdf)" "$feedback"

    # verifica se o processo que esta na resposta tambem esta no feedbagrep -oP "(?<=http://eliasdeoliveira.com.br/seminars/tmp/)[0-9\-\.]+(?=\.pdf)" "$feedback"ck
    processosEnviados=$(grep -oP "(?<=http://eliasdeoliveira.com.br/seminars/tmp/)[0-9\-\.]+(?=\.pdf)" "$feedback")

    echo "Contagem de anotações realizadas dos processos enviados"
    nota=0
    for processo in $processosEnviados; do
        count=$(grep -c "$processo" "$resposta")
        #echo "$processo: $count"
        if [ $count -ne 0 ]; then 
            nota=$(($nota + 1))
        fi
    done
    echo "Nota do aluno foi: $nota"

done
