# Projeto Airbender - PLSI

### Projeto realizado por 
Grupo 16 PL2
>   - Marco Padeiro
>   - Marco Harbuzyuk
>   - TomasMoura

### Instalação do projeto

Fazer clone do repositório na diretoria de preferência...
```
git clone https://github.com/MarcoPadeiroIPL/ProjetoPLSI.git airbender
```

Ir para a diretoria do projeto...
```
cd airbender
```

Executar o composer para instalar a pasta 'vendor'...
```
composer install
```

Inicializar o projeto...
```
php init --env=Development --overwrite=All --delete=All
```

Por último, executar a base de dados sql/airbender.sql como root e mudar no common/config/main-local.php...
```
...
    'dsn' => 'mysql:host=localhost;dbname=airbender',
...
```



- Instituto Politécnico de Leiria 
![Logo IPL](https://www.ipleiria.pt/wp-content/uploads/2022/04/estg_h.svg)
