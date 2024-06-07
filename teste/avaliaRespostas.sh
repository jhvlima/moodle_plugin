#!/bin/bash
resposta="resp2.html"
feedback="feedback.csv"


# pega cada numero de processo que anotado pelo aluno (resposta.csv)
#awk -F "#" '{print $2}' $resposta | awk -F "|" '{print $1}' | sort | uniq

# pega a cada numero de processo que foi enviado para o aluno (feedback.csv)
# tem que pegar oque esta entre <a href='http://eliasdeoliveira.com.br/seminars/tmp/[numero].pdf'>
echo "Processos enivados para o aluno"
grep -oP "(?<=http://eliasdeoliveira.com.br/seminars/tmp/)[0-9\-\.]+(?=\.pdf)" "$feedback"

# verifica se o processo que esta na resposta tambem esta no feedbagrep -oP "(?<=http://eliasdeoliveira.com.br/seminars/tmp/)[0-9\-\.]+(?=\.pdf)" "$feedback"ck
processosEnviados=$(grep -oP "(?<=http://eliasdeoliveira.com.br/seminars/tmp/)[0-9\-\.]+(?=\.pdf)" "$feedback")

echo "Contagem de anotadções realizadas dos processos enviados"
for processo in $processosEnviados; do
    count=$(grep -c "$processo" "$resposta")
    echo "$processo: $count"
done
