## Teste Prático - Estuda.com

#### API em PHP

---

## 1. Instruções

-   Alocar a pasta _backend_ no diretório do _Apache_ determinado na máquina
-   Permitir a configuração do .htaccess nas configurações do _Apache_
-   Ter o PHP devidamente instalado, além dos módulos mais comuns 
-   Executar o comando `composer install` na pasta _backend_
-   Executar o _script `teste_estuda.sql`_ no banco de dados local
-   Criar um arquivo `database.ini` dentro da pasta _config_ e alterar os dados referentes
    ao Banco de Dados, pegar como referência o arquivo `database.example.ini`

## 2. Observações

-   A ideia era criar o frontend em Angular para consumir a API, porém não tive tempo para implementar
-  Para realizar os testes utilizando o Postman, basta importar o arquivo `teste_estuda.postman_collection`. É necessário
   criar uma váriavel de ambiente chamada ``host`` sendo seu valor a URL da sua API local, por exemplo: `http://teste-estuda`
- Criei algumas configurações para rodar o projeto no Docker, porém ainda falta colocar alguns comandos para que seja possível rodar 
o projeto sem a necessidade de instalar por exemplo o WAMPP

## 3. TODO

- Criar classe de Validação, para validar os valores dos parâmetros passados na Request
- Melhorias nas consultas, utilizar JOINS 

