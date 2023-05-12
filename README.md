# API de controle de pagamentos

## Introdução 

O Azapfy é uma empresa que transforma a gestão da entrega e comprovação, que
hoje são totalmente manuais, em processos automatizados de alta performance e
controle. Garantindo que elas recebam por todo o serviço realizado, no menor prazo
possível, com o menor custo de operação e ainda identificando em real time todas
as etapas do processo.
Nesse teste você irá desenvolver uma API que faz controle de pagamento pelas
entregas realizadas.
Disponibilizamos uma API onde você irá consultar as notas fiscais para realizar o
cálculo de pagamento.

## Tarefa

- Agrupar as notas por remetente.
- Calcular o valor total das notas para cada remetente.
- Calcular o valor que o remetente irá receber pelo que já foi entregue.
- Calcular o valor que o remetente irá receber pelo que ainda não foi entregue.
- Calcular quanto o remetente deixou de receber devido ao atraso na entrega.

## Install project

- Faça o Pull do projeto.
  `git clone git@github.com:Matheuspsilva/api_notas.git`
- Entre na pasta raiz do projeto
    `cd api_notas`
- Renomeie o arquivo `.env.example` para `.env` e preencha com as informações do banco de dados.
- Configure suas variáveis de conexão com o banco de dados

        ```
        DB_CONNECTION=mysql
        DB_HOST=mysql
        DB_PORT=3306
        DB_DATABASE=laravel
        DB_USERNAME=sail
        DB_PASSWORD=password

      ```  

- Abra o console e vá até o diretorio raiz
- Execute `composer install`
- Execute `./vendor/bin/sail up -d`
- Execute `./vendor/bin/sail php artisan key:generate`
- Execute `./vendor/bin/sail php artisan migrate`
- Execute `./vendor/bin/sail php artisan db:seed` para rodar seeders
- Execute `./vendor/bin/sail php artisan serve`
- Execute `./vendor/bin/sail php artisan db:populate` para consumir a api da azapfy e popular o banco de dados
- Você pode acessar seu projeto em localhost:80

## Executar Testes
- Execute `./vendor/bin/sail php artisan test` or `./vendor/bin/sail artisan test`

## Documentação
- A Documentação está disponível na url localhost:80/swagger
