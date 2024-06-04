import os
import sys
import html2text
import random
import json

QUANTIDADE_DE_LINKS = 10

def verifica_pastas(pasta):
    lista = os.listdir(pasta)
    alunos = []
    for item in lista:
        if os.path.isdir(pasta + '/' + item):
            alunos = alunos + [item]
    return alunos

def carregar_status():
    try:
        with open(sys.argv[1] + '/' +'status.json', 'r') as arquivo:
            status_alunos = json.load(arquivo)
    except FileNotFoundError:
        status_alunos = {}
    return status_alunos

def salvar_status(status_alunos):
    with open(sys.argv[1] + '/' +'status.json', 'w') as arquivo:
        json.dump(status_alunos, arquivo)

def analisa_respostas(lista_alunos, status_alunos):
    for aluno in lista_alunos:
        status = status_alunos.get(aluno, 0)
        print(aluno, status)
        with open(sys.argv[1] + '/' + aluno + '/resposta.txt', 'r') as arquivo:
            texto = arquivo.read()
        arquivo.close()
            
        texto = html2text.html2text(texto)
        texto = texto.replace('\n', '')
        texto = texto.replace(' ', '')
        
        if texto.startswith('#') and status==1:
            print('coletando classificação')
            lista = texto.split('#')
            for item in lista:
                if item == '':
                   lista.remove(item)
            
            for item in lista:
                par = item.split('|')

                if os.path.exists(sys.argv[1] + '/' + 'respostas.csv'):
                    with open(sys.argv[1] + '/' + 'respostas.csv', 'a') as arquivo:
                        arquivo.write(par[0] + ';' + par[1] + '\n')
                    arquivo.close()   
                else:
                    with open(sys.argv[1] + '/' + 'respostas.csv', 'w') as arquivo:
                        arquivo.write(par[0] + ';' + par[1] + '\n')
                    arquivo.close()
            status_alunos[aluno] = 2
        else:
            if(status == 1 or status == 2):
                print('Aluno já corrigido ou classificado')
                continue
            else:
                print('Corrigindo aluno')
                with open(sys.argv[2] + 'listaLinks.txt', 'r') as arquivo:
                    lista_links = arquivo.readlines()
                arquivo.close()
                    
                lista_links = [link.replace('\n', '') for link in lista_links]
                lista_links = [link.replace(' ', '') for link in lista_links]
                amostra_links = random.choices(lista_links, k=QUANTIDADE_DE_LINKS)
                html_dos_links = hyperlinking(amostra_links)
                
                if os.path.exists(sys.argv[1] + '/' + 'feedback.csv'):
                    with open(sys.argv[1] + '/' + 'feedback.csv', 'a') as arquivo:
                        arquivo.write(aluno + ';' + '-1' + ';' + html_dos_links + '\n')
                    arquivo.close()
                else:
                    with open(sys.argv[1] + '/' + 'feedback.csv', 'w') as arquivo:
                        arquivo.write(aluno + ';' + '-1' + ';' + html_dos_links + '\n')
                    arquivo.close()
                status_alunos[aluno] = 1
            
    salvar_status(status_alunos)
        

def hyperlinking(lista_links):
    html = "<ol>"
    for i, link in enumerate(lista_links, start=1):
        html += f"<li><a href='{link}'>Link {i}</a></li>"
    html += "</ol>"
    return html
                
            
            
if __name__ == '__main__':
    estudantes = verifica_pastas(sys.argv[1])
    status_alunos = carregar_status()
    analisa_respostas(estudantes, status_alunos)
