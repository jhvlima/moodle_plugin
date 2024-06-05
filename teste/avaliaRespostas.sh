#!/bin/bash
resposta="exResposta.html"
feedback="feedback.csv"


# pega cada numero de processo que anotado pelo aluno (resposta.csv)
awk -F "#" '{print $2}' $resposta | awk -F "|" '{print $1}'

# pega a cada numero de processo que foi enviado para o aluno (feedback.csv)
awk -F "#" '{print $2}' $feedback | awk -F "|" '{print $1}'

# verifica se o processo que esta na resposta tambem esta no feedback
