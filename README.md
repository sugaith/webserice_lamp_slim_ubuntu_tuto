# CRIE UM WEBSERVICE COM SLIM FRAMEWORK E LAMP LIKE A PRO RAPIDÃO 


###Estou criando isto por..
- que ja tive q passar por este processo mesmo.. para o desenvolvimento de um novo aplicativo em react-native
- servir como guia de apresentação de um trabaho de Redes2 sobre REST que ocorrerá em breve
- que quero movimentoar esse Git
- que a documentação do Slim Framework deixa a desejar.. pessoal fica perdida batendo cabeça, ainda mais os colegas que nao sabem inglês. 

Na documentacao do Slim eles falam pra voce instalar um esqueleto deles, 
que é bem robusto e realmente voce nao precisa de tudo isso pra começar.
Ao inves disso, neste turoria voce so vai precisar de um arquivo (index.php) deste repositorio, 
localizado na pasta servida pelo apache: '/var/www/girorest/src/public'



Sintam-se livre pra colaborar arrumando isso aqui: 
- removendo meus comentarios desnecessarios.
- deixando o passo a passo mais didático
- formalizando (um pouco)
- formatando este readme
- agregando rotas/funcionalidades que mostre o que a tecnologia e capaz funcionamento do slim
- coneitos..
- diferenca do processo em diferentes versoes linux
__________________________


COM ESTE PASSO A PASSO VOCE VAI PODER TER UM AMBIENTE COM:
- LINUX UBUNTU
- MYSQL, PHP, APACHE2, (vulgos LIMP)
- SLIM FRAMEWORK (MICRO WEBSERVICE EM PHP TIPO REST)

... RODANDO LIKE A PRO RAPIDÃO

- PRE REQUISITOS:
    - UBUNTU SECO INSTALADO (eu usei versao 14) 


1.  Instalar Apache2

```php
$ apt-get update
$ apt-get install apache2
```


2. instalar php

apt-get install php5 libapache2-mod-php5 php5-mcrypt

3. instalar o mysql

$ sudo apt-get install mysql-server


1,2,3. OU instala o LAMP de uma vez :

$ sudo apt-get install lamp-server^

escolhe uma senha pra seu root e ja era!

* A este ponto seu servidor deve estar de pé.. acesse http://localhost ou http://seudominio.ae se vc estiver remoto.

Apareceu a pagina? beleza ta td certo...

agora vamos continuar configurando os servidor e preparar pra instalar o Slim

4. Agora vamos configurar o apache para receber o SLIM

Primeiro.. faz um
$ sudo chmod -R 777 /var/www
para nao termos problemas de permissao pra criar pastas e instalacoes de pacotes...

Agora temos que certificar que o documentRoot é /var/www/ (só pra manter um padrao mas se vc ja sabe manipular isso, GG véi),
e arruma umas permissoes de pasta e rewrite de regras, e outras coisas pra suportar URL amigavel e etc.. bora lá

4.1. Edite o arquivo apache2.conf

$ /etc/apache2/apache2.conf

Procure as linhas a seguir (em algumas versoes pode estar '/var/www/html/', se estiver deixa '/var/www/')
<Directory /var/www/>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
</Directory>
***note que o atributo 'AllowOverride' DEVE estar setado para 'All'!!!

4.2. Habilite o modo rewrite de regras do apache, digite no seu terminal:

a2enmod rewrite

4.3. Certo, agora edite este arquivo (para algumas verões ele pode ser https.conf, ou algo assim. vc vai ter q pesqusar)

$ /etc/apache2/sites-enabled/000-default.conf

Na linha:

DocumentRoot /var/www/

substitua por

DocumentRoot /var/www/girorest/src/public/

***RELAXA, vamos criar isto em seguida
Pronto.. estamos preparados pre

4.4) Reinicie o servico do apache:

$ sudo service apache2 restart

____

Beleza.. agora o apache esta preparado pra receber o Slim e ja deve estar servindo no diretorio
'DocumentRoot /var/www/girorest/src/public/'. MAs nao existe ainda entao vamos criar.ë aqui que vem o slim

5) Criar Pasta do Projeto com o comando

$ mkdir /var/www/girorest/src/public/

Entre na pasta /var/www/src/girorest/ ('girorest' é o nome do meu projeto mas vc poder escolher qualquer um )

$ cd /var/www/girorest/

6. Aqui instalaremos gerenciador de pacotes Composer para instalar o Slim (padrão do slim)
Os comandos baixo vao baixar e instalar o composer usando o executavel do php.
**antes, certifique-se que voce tem voce tem zip e unzip

$ apt-get install zip unzip

agora, Digite comando por comando.

$ php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"

$ php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"

$ sudo php composer-setup.php

$ php -r "unlink('composer-setup.php');"


7. Agora vamos instalar o SLIM. Digite:

$ composer.phar require slim/slim
________

8. DEU! Agora vamos criar nosso WS
crie um arquivo .htaccess na pasta '/var/www/girorest/src/public/'
e cole o seguinte codigo:

RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule . index.php [L]

9. Crie um arquivo index.php na mesma pasta '/var/www/girorest/src/public/'
e cola O snippet deste GIT com o mesmo nome 'index.php', (ou trasnfere e depois abre tanto faz)

midifique as linhas

$config['db']['host']   = 'localhost';
$config['db']['user']   = 'thiago';
$config['db']['pass']   = 'thiago123.';
$config['db']['dbname'] = 'girodb';

para os dados do seu banco.
 (ah sim.. crie um banco no seu mysql e crie uma tabela qualquer tipo usuario, com campos de id e nome)

 e pronto.. Acesse

 http://localhost/mundo/

 A explicação do funcionemtno do código esta como comentário do próprio código.









