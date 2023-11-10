## Projeto API Deputado

Projeto:
Visa consumir dados da API da Assembléia Legistiva e via API entregar JSON com as informações consumidas.

Foram criados métodos para armazenar os dados localmente dos Deputados, seus contatos de Redes Sociais e dados das Verbas Indenizatórias solicitadas por eles no ano de 2017.

Projeto desenvolvido em:

- Laravel 5.8.11
- PHP 7.3.2
- Banco de dados MySQL
- Biblioteca para API guzzlehttp 6.0

Na pasta PUBLIC do projeto existe um arquivo SQL com o Backup dos dados já baixados para testes. (bkp_apideputado.sql)

## Implementação do Projeto

- Salvar o projeto
- Criar instância no banco de dados: apideputado
- Rodar comando: composer install
- Rodar comando: php arisan migrate
- Rodar comando: php artisan serve

## Métodos API

- gravaDeputadosBD

Método busca a informação dos Deputados em Exercício na Assembléia e grava no banco de dados.

(GET) URL: http://localhost:8000/api/gravaDeputadosBD

- gravaVerbasBD

Método busca a informação das Verbas solicitadas pelos Deputados em Exercício no ano de 2017 e grava no banco de dados.

(GET) URL: http://localhost:8000/api/gravaVerbasBD

- gravaRedesSociaisDB

Método busca a informação das Rede sociais nos contatos dos Deputados em Exercício na Assembléia e grava no banco de dados.

(GET) URL: http://localhost:8000/api/gravaRedesSociaisDB

- topDeputados

Método retorna os 5 deputados com maior valor em Verbas Indenizatórias registrados em 2017.

(GET) URL: http://localhost:8000/api/topDeputados

Retorno:

{"Resultado":[{"nome":"Leon\u00eddio Bou\u00e7as","total":98380.84},{"nome":"Celinho Sintrocel","total":72534.34000000001},{"nome":"L\u00e9o Portela","total":46858.719999999994},{"nome":"Mar\u00edlia Campos","total":41632.57},{"nome":"Doutor Jean Freire","total":39775.770000000004}]}

- topRedesSociais

Método retorna as Redes Sociais usadas pelos Deputados em ordem das mais usadas.

(GET) URL: http://localhost:8000/api/topRedesSociais

Retorno:

{"Resultado":[{"total":74,"idRedeSocial":0,"nome":"Facebook"},{"total":73,"idRedeSocial":19,"nome":"Instagram"},{"total":43,"idRedeSocial":1,"nome":"Twitter"},{"total":20,"idRedeSocial":3,"nome":"Youtube"},{"total":3,"idRedeSocial":18,"nome":"SoundCloud"},{"total":3,"idRedeSocial":5,"nome":"Flickr"},{"total":1,"idRedeSocial":6,"nome":"LinkedIn"}]}

