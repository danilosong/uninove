# Sistema ERP Tecnomed
## Sistema de Gerenciamento de PCMSO, PPP e PPRA de Empresas


> Versão 0.5.0 lançada! :tada: 

### Detalhes da versão:
1. Gerenciamento de PCMSO
    1. Cadastro de Holds
    2. Cadastro de Administradoras
    3. Cadastro de Empresas
    4. Cadastro de Clinicas
    5. Cadastro de Funcionários
    6. Cadastro de Médicos
    7. Agenda de Atendimento dos Funcionários
    8. Cadastro de Guias de Encaminhamento
        1. Para empresas com cadastro
        2. Para empresas sem cadastro
    9. Pedido de Liberação de exames
    10. Cadastro de Cargos
    11. Cadastro de Setores
    12. Cadastro de Ocupações
    13. Cadastro de CID (Código Internacional de Doenças)


### Requisitos para executar projeto:
1. PHP 7
2. [Composer](https://getcomposer.org/)

### Instalação:
Clonar o projeto com `git clone https://github.com/PauloJapa/ERP.git` em uma pasta.
Então, deve-se incluir o arquivo * composer.phar * na pasta deste projeto e executar
o comando `composer.phar install`. A pasta * Vendor * será incluída neste projeto.

É necessário que a base exista. Ao criar a base e configurá-lo no projeto
(Necessário saber configurar o banco de dados em um projeto [Zend Framework 2](http://framework.zend.com/)
execute os comandos:
```
php public/index.php orm:schema-tool:update --force
php public/index.php data-fixture:import
```
