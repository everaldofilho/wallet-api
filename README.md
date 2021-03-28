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

## Estutura do banco
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