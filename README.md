[![code-inspector](https://www.code-inspector.com/project/20527/score/svg)](https://frontend.code-inspector.com/public/project/20527/wallet-api/dashboard)
[![CodeFactor](https://www.codefactor.io/repository/github/everaldofilho/wallet-api/badge)](https://www.codefactor.io/repository/github/everaldofilho/wallet-api)
[![Tests](https://github.com/everaldofilho/wallet-api/actions/workflows/tests.yml/badge.svg)](https://github.com/everaldofilho/wallet-api/actions/workflows/tests.yml)
[![Coverage Status](https://coveralls.io/repos/github/everaldofilho/wallet-api/badge.svg)](https://coveralls.io/github/everaldofilho/wallet-api)

# Wallet API

A ideia desse projeto é poder fazer transferências entre usuários do próprio sistema.

## Setup

Para subir o projeto execute o seguinte comando:

````
make setup
````

Caso queira saber o que é acontece nesse comando só abrir o arquivo "Makefile".

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

Arquivos/documentos na pasta [docs](./docs)
- [MER.svg](./docs/mer.svg)
- [WalletApi.postman_collection.json](./docs/WalletApi.postman_collection.json)

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