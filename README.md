# SponsorsHub

Uma aplicação Laravel.

Mais informações: [Laravel na Prática - Live Series](https://bit.ly/laravel-na-pratica)

## Rodando SponsorsHub com Docker

O projeto inclui um `docker-compose.yml` para que você possa rodar a aplicação em contêineres.


### Pré-Requisitos:

Você vai precisar ter instalado em sua máquina de desenvolvimento:

- [Docker](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-on-ubuntu-20-04)
- [Docker Compose](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-compose-on-ubuntu-20-04)

Opcional, mas recomendado:

- PHP-cli
- [Composer](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-composer-on-ubuntu-20-04)

Meu setup usa uma máquina local Ubuntu 18.04 rodando Docker e Docker Compose, mas também com um ambiente básico PHP na linha de comando para rodar comandos do (PHP) Composer.

### Instalação:

Importante:
- A branch **main** contém o código mais atual do projeto, de acordo com o último episódio.
- Cada episódio tem uma branch específica para quem quer acompanhar passo a passo.

Primeiro, clone o projeto com:

```bash
git clone https://github.com/erikaheidi/sponsorshub.git
```

Depois, copie o `.env.example` para um novo arquivo `.env`:

```bash
cp .env.example .env
```

Para rodar o ambiente:

```bash
docker-compose up -d
```

Uma vez que o ambiente esteja rodando, você pode usar esse comando para instalar as dependências com Composer direto no container (não precisa ter o Composer instalado na sua máquina host).

```bash
docker-compose exec app composer install
```

Finalmente, gere a chave da aplicação com:

```bash
docker-compose exec app php artisan key:generate
```

E a aplicação poderá ser acessada em `localhost:8000`.

### Comandos do Docker Compose

Para parar a execução sem excluir os containers / volumes / networks:

```bash
docker-compose stop
```

Para destruir o ambiente:

```bash
docker-compose down
```
