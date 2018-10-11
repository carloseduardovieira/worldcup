# worldcup

Primeiro de tudo, verifique se você instalou e ativou o módulo de rewrite na configuração do Apache.

Após isso o arquivo .htaccess no diretório principal da aplicação irá ativae o mecanismo de reescrita.
Em seguida, trataremos de todas as solicitações que não correspondem aos nomes dos arquivos. 
Depois disso, passaremos os pedidos para o arquivo route.php. Agora, route.php irá gerenciar todos os pedidos.

A aplicação worldcup está preparada para receber e retornar arquivos .json, adiante descreve-se os comando para executar o REST.
Sugere-se a utilização do aplicativo Postman, para realizar as requisições.

## Adicionar uma nova equipe - POST method

URI: http://52.67.82.231/worldcup/add-team
Objeto de a ser enviado como POST:
```sh
{
	"name":"Brasil",
	"image":"https://image.freepik.com/fotos-gratis/bandeira-do-brasil_1401-76.jpg"
}
```

Obs: a requisição deve enviar no cabeçalho Content-Type = application/json

## Adicionar um novo jogo - POST method

URI: http://52.67.82.231/worldcup/add-match
Objeto de a ser enviado como POST:
```sh
{
	"idHomeTeam": 1,
	"idVisitingTeam":2,
	"matchTime": "2018-10-09 21:30"
}
```


Obs: a requisição deve enviar no cabeçalho Content-Type = application/json

## Procurar todos os jogos de uma equipe - GET method

URI: http://52.67.82.231/worldcup/find-team/1

Obs: o id do time requisitado deve estar logo após a primeira barra após o nome do metodo

## Procurar por todos os jogos em um determinado dia - GET method

URI: http://52.67.82.231/worldcup/find-match-day/2018-10-09

Obs: o data requisitada deve estar logo após a primeira barra após o nome do metodo, e o formato deve ser yyyy-mm-dd

