# Sistema ERP Uninove
## Sistema de Gerenciamento de colaboradores, cartão de ponto e cadastro de produtos.


> Versão 1.0 :tada: 

### Detalhes da versão:
1. Gerenciamento de Colaborador
    1. Cadastro de Colaboradores
    2. Cadastro de Pontos
2. Gerenciamento de produtos e fornecedores
    1. Cadastro de produtos
    2. Cadastro de fornecedores
    3. Cadastro de pedidos
    4. Cadastro de saidas
    5. Listagem de saidas
3. Gerenciamento de Cadastro de configuração de sistema
    1. Menu
    2. Papeis
    3. Recursos
    4. Privilegios
    5. Parametros
    6. Usuarios do Sistema
    7. Mensagem
    8. Gerar Gets Sets
    9. user
    10. Enviado
    11. Contatos
    12. Grupos
    13. AppLog
4. Gerenciamento de Controle do C.I
    1. Abrir chamado
    2. Chamado resposta



### Requisitos para executar projeto:
1. PHP 7
2. [Composer](https://getcomposer.org/)
3. [ZendFramework 2.*](https://framework.zend.com/)
4. [Doctrine 2.*](http://www.doctrine-project.org/)

### Instalação:
Clonar o projeto com `git clone https://github.com/danilosong/uninove` em uma pasta.
Então, deve-se incluir o arquivo * composer.phar * na pasta deste projeto e executar
o comando `composer.phar install`. A pasta Vendor e Data é essencial [DOWNLOAD AQUI](https://goo.gl/Voyacn) e 
adicione descompacte dentro da pasta uninove do projeto.

### Banco de dados

É necessário de alguns arquivos para o funcionamento dos menus e cadastro através da conta
de administrador baixe o [DUMP](https://goo.gl/k6vUEq) antes.
```
Login : adm;
Senha : 1234;
```

ou tente o procedimento a seguir:
É necessário que a base exista. 
Ao criar a base e configurá-lo no projeto (Necessário saber configurar o banco 
de dados em um projeto [Zend Framework 2](https://framework.zend.com/) execute os comandos:
```
php public/index.php orm:schema-tool:update --force
php public/index.php data-fixture:import
```
