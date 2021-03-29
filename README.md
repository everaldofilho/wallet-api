[![code-inspector](https://www.code-inspector.com/project/20527/score/svg)](https://frontend.code-inspector.com/public/project/20527/wallet-api/dashboard)
[![CodeFactor](https://www.codefactor.io/repository/github/everaldofilho/wallet-api/badge)](https://www.codefactor.io/repository/github/everaldofilho/wallet-api)
[![Tests](https://github.com/everaldofilho/wallet-api/actions/workflows/tests.yml/badge.svg)](https://github.com/everaldofilho/wallet-api/actions/workflows/tests.yml)
[![Coverage Status](https://coveralls.io/repos/github/everaldofilho/wallet-api/badge.svg)](https://coveralls.io/github/everaldofilho/wallet-api)

# Wallet API

A ideia desse projeto é poder fazer transferências entre usuários do próprio sistema.

## Setup

Para subir o projeto execute o seguinte comando:

````bash
make setup
````

Caso queira saber o que é acontece nesse comando só abrir o arquivo "Makefile".

Outros comandos uteis:

````bash
make database-create # Cria o banco
make migrate # Cria as tabelas do banco
make seed # Alimenta o banco com dados Fake
make test # Rota os tests

# Fila
make queue-transaction # Roda a fila de "transaction"
make queue-notification # Roda a fila de "notification"

# Fila Dead
make queue-transaction-dead # Roda a fila de "transaction-dql"
make queue-notification-dead # Roda a fila de "notification-dql"
````
OBS: Por padrão a fila __transaction__ e __notification__ já vem em execução dentro da docker, atraves da configuração do __supervisor__ que se encontra no arquivo [./supervisor.conf](./supervisor.conf)

### HOSTS

RABBITMQ:http://localhost:15672/
- User: guest
- Password: guest

WALLET API URL: http://localhost:8089/api/doc
![documentacao](./docs/documentation-api.png)


POSTMAN: https://documenter.getpostman.com/view/9357548/TzCHCWB9

Database Postgres: 
- host: `localhost`
- port: `5433`
- user: `postgres`
- password: `root`

## Estrutura

### Fluxo de Transação
[![](https://mermaid.ink/img/eyJjb2RlIjoic2VxdWVuY2VEaWFncmFtXG4gICAgIyBUcmFuc2HDp8OjbyBTaW5jcm9uYVxuICAgIFVzZXItPj4rV0FMTEVUX0FQSTogVHJhbnNmZXJlbmNpYSBTaW5jcm9uYVxuICAgIFdBTExFVF9BUEktLT4-K0RCOiBWZXJpZmljYXIgc2FsZG9cbiAgICBXQUxMRVRfQVBJLS0-PitEQjogUmVnaXN0cmEgdHJhbnNmZXLDqm5jaWFcbiAgICBXQUxMRVRfQVBJLT4-K0FQSV9BVVRPUklaQUNBTzogVmVyaWZpY2Egc2UgcG9kZSB0cmFuc2ZlcmlyXG4gICAgQVBJX0FVVE9SSVpBQ0FPLT4-LVdBTExFVF9BUEk6IFwiQXV0b3JpemFkb1wiXG4gICAgV0FMTEVUX0FQSS0tPj4rREI6IERlYml0YSBkYSBjYXJ0ZWlyYSBvcmlnZW1cbiAgICBXQUxMRVRfQVBJLS0-PitEQjogQ3JlZGl0YSBkYSBjYXJ0ZWlyYSBkZXN0aW5vXG4gICAgV0FMTEVUX0FQSS0-PitSQUJCSVQ6IFJlZ2lzdHJhIG5hIGZpbGEgXCJub3RpZmljYXRpb25cIlxuICAgIFdBTExFVF9BUEktPj4tVXNlcjogMjAxXG5cbiAgICAjIFRyYW5zYcOnw6NvIEFzc2luY3JvbmFcbiAgICBVc2VyLT4-K1dBTExFVF9BUEk6IFRyYW5zZmVyw6puY2lhIEFzc2luY3JvbmFcbiAgICBXQUxMRVRfQVBJLS0-PitEQjogVmVyaWZpY2FyIHNhbGRvXG4gICAgV0FMTEVUX0FQSS0tPj4rREI6IFJlZ2lzdHJhIHRyYW5zZmVyw6puY2lhXG4gICAgV0FMTEVUX0FQSS0tPj4rUkFCQklUOiBSZWdpc3RyYSBuYSBmaWxhIFwidHJhbnNhY3Rpb25cIlxuICAgIFdBTExFVF9BUEktPj4tVXNlcjogMjAxXG4gICAgXG4gICAgICAgICAgICAiLCJtZXJtYWlkIjp7InRoZW1lIjoiZGVmYXVsdCJ9LCJ1cGRhdGVFZGl0b3IiOmZhbHNlfQ)](https://mermaid-js.github.io/mermaid-live-editor/#/edit/eyJjb2RlIjoic2VxdWVuY2VEaWFncmFtXG4gICAgIyBUcmFuc2HDp8OjbyBTaW5jcm9uYVxuICAgIFVzZXItPj4rV0FMTEVUX0FQSTogVHJhbnNmZXJlbmNpYSBTaW5jcm9uYVxuICAgIFdBTExFVF9BUEktLT4-K0RCOiBWZXJpZmljYXIgc2FsZG9cbiAgICBXQUxMRVRfQVBJLS0-PitEQjogUmVnaXN0cmEgdHJhbnNmZXLDqm5jaWFcbiAgICBXQUxMRVRfQVBJLT4-K0FQSV9BVVRPUklaQUNBTzogVmVyaWZpY2Egc2UgcG9kZSB0cmFuc2ZlcmlyXG4gICAgQVBJX0FVVE9SSVpBQ0FPLT4-LVdBTExFVF9BUEk6IFwiQXV0b3JpemFkb1wiXG4gICAgV0FMTEVUX0FQSS0tPj4rREI6IERlYml0YSBkYSBjYXJ0ZWlyYSBvcmlnZW1cbiAgICBXQUxMRVRfQVBJLS0-PitEQjogQ3JlZGl0YSBkYSBjYXJ0ZWlyYSBkZXN0aW5vXG4gICAgV0FMTEVUX0FQSS0-PitSQUJCSVQ6IFJlZ2lzdHJhIG5hIGZpbGEgXCJub3RpZmljYXRpb25cIlxuICAgIFdBTExFVF9BUEktPj4tVXNlcjogMjAxXG5cbiAgICAjIFRyYW5zYcOnw6NvIEFzc2luY3JvbmFcbiAgICBVc2VyLT4-K1dBTExFVF9BUEk6IFRyYW5zZmVyw6puY2lhIEFzc2luY3JvbmFcbiAgICBXQUxMRVRfQVBJLS0-PitEQjogVmVyaWZpY2FyIHNhbGRvXG4gICAgV0FMTEVUX0FQSS0tPj4rREI6IFJlZ2lzdHJhIHRyYW5zZmVyw6puY2lhXG4gICAgV0FMTEVUX0FQSS0tPj4rUkFCQklUOiBSZWdpc3RyYSBuYSBmaWxhIFwidHJhbnNhY3Rpb25cIlxuICAgIFdBTExFVF9BUEktPj4tVXNlcjogMjAxXG4gICAgXG4gICAgICAgICAgICAiLCJtZXJtYWlkIjp7InRoZW1lIjoiZGVmYXVsdCJ9LCJ1cGRhdGVFZGl0b3IiOmZhbHNlfQ)

### Estutura do banco
![a](./docs/mer.svg)

## Tecnologias utilizadas

Container: #Docker


Linguagem: #PHP 7.4

Framework: #Symfony

Servidor: #Nginx

Fila: #RabbitMQ

Banco de dados: #Postgree

Geração do Token: #JWT

Documentação API: #NelmioApiDoc