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

### Instalação manual:
Clonar o projeto com `git clone https://github.com/danilosong/uninove` em uma pasta.

* vendor e data *
Então, depois de ter instalado o composer deve-se incluir o arquivo * composer.phar * 
na pasta deste projeto e executar o comando `composer.phar install` no terminal. 

OU

### Download apenas do vendor e data:
A pasta Vendor e Data é essencial [DOWNLOAD AQUI](https://goo.gl/Voyacn) e 
adicione descompacte dentro da pasta uninove do projeto.

OU

### Pasta do projeto completa!!!
```
161MB
[Download](https://goo.gl/AGTAVm)
```

### Banco de dados

É necessário de alguns arquivos para o funcionamento dos menus e cadastro através da conta
de administrador baixe o [DUMP](https://goo.gl/k6vUEq) antes. Para importar o banco
recomendo que use o [Mysql Workbench](https://dev.mysql.com/downloads/workbench/)

### EXPORTAR COM WOKRBENCH
Você pode exportar o seu banco no Workbench em ** Management **-> ** Data Export **. 
Selecionar a database, escolha o diretório e nome do arquivo e clique em ** Start 
Export **. Será gerado um arquivo sql.

### IMPORTAR COM WOKRBENCH
Clique em ** Management **->  ** Data Import/Restore **, 
procure o arquivo sql gerado anteriormente e clique em ** Start Import **.
```
Testado na versão 6.1 do MySQL Workbench.
```

### Configuração do servidor apache no windows
- Procure por virtualhost de seu servidor e configure no virtualHost do Apache:
```
<VirtualHost *:80>
    ServerAdmin danilosong@outlook.com (SEU EMAIL)
    DocumentRoot "C:/xampp/htdocs/uninove/public" (CAMINHO DO PROJETO NO SERVER)
    ServerName uninove.dev
    ServerAlias www.uninove.dev
    ErrorLog "logs/dummy-host.example.com-error.log"
    CustomLog "logs/dummy-host.example.com-access.log" common
</VirtualHost>
```

### Hosts no client
Configurar C:\Windows\System32\drivers\etc
```
número_do_ip_da_maquina www.uninove.dev
```

No linux depois de ter feito o clone do arquivo e instalado os complementos do
composer ou ter baixado os complementos o ter baixado o projeto inteiro 
via download que está citado acima segue os comandos.

### COMANDO:
```
sudo nano ~/.bashrc
```

adicionar alias no bashrc para facilitar na execução de comandos no terminal.

```
alias uninove="cd /var/www/uninove"
alias svn="/var/www/uninove/srv.sh 8000 ip_da_maquina"
```
Em seguida reinicie no terminal e com php, apache e mysql instalados 
execute os comandos:
```
uninove
svn
```

### Acesso ao sistema usuario adm padrão:

```
Login : adm;
Senha : 1234;
```

Para atualizar a base de dados caso mude as entity no doctrine:
```
php public/index.php orm:schema-tool:update --force
php public/index.php data-fixture:import
```
Tutorial de manuseio 

Segue o link: 