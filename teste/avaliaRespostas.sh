#!/bin/bash
resposta="exResposta.html"
feedback="feedback.csv"


# pega cada numero de processo que anotado pelo aluno (resposta.csv)
awk -F "#" '{print $2}' $resposta | awk -F "|" '{print $1}' | sort | uniq

# pega a cada numero de processo que foi enviado para o aluno (feedback.csv)
#awk -F ";" '{print $3}' $feedback | sort | uniq 

# tem que pegar oque esta entre <a href='http://eliasdeoliveira.com.br/seminars/tmp/[numero].pdf'>

# verifica se o processo que esta na resposta tambem esta no feedback
grep -c "$(awk -F "#" '{print $2}' $resposta | awk -F "|" '{print $1}' | sort | uniq)" $feedback 
