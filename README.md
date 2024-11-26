# Esercizio Test Php Laravel

## Specifications

Implementare una web application laravel che dopo il login di utente tramite username e password fornisca un token.

Non è necessario la fase di registrazione dell'utente con invio mail, puoi immaginare di partire con un db già contenente un utenza user: "root" password: "password"

Utilizzare il token recuperato dopo l'accesso per interrogare un api esposta dal sistema che faccia da proxy verso openbrewerydb (https://www.openbrewerydb.org/documentation/) e mostri una lista paginata di birre recuperata dai loro servizi.

Fornire una pagina web in grado di fare la chiamate verso il backend per poter testare l’api e renderizzare la lista paginata.

E’ importante prestare particolare attenzione alla struttura del codice ed al riuso
E' gradita la presenza di test e l’utilizzo di docker

Invito inoltre a valutare l’utilzzo del pattern MVC e della dependency Injection.

## About

This sample project is using Laravel 11 with its built-in Browser Authentication Services to provide cookie-based authentication for requests that are initiated from web browsers, plus Sanctum package as API security service for SPA by Bearer Token.

The classic Laravel's Welcome [page](http://localhost:8080) displays a link to the Login [page](http://localhost:8080/login) when user is not logged in already, otherwise a link to the Home [page](http://localhost:8080/home).

## Setup

Copy env.example to .env  
There are three specific variables for the Brewery Service:
- BREWERY_API_BASE_URL="https://api.openbrewerydb.org/v1"
- BREWERY_API_PATH="breweries"
- BREWERY_API_PAGINATION=10

### Build and run docker
```bash
$ docker compose up -d --build
```

### Install
```bash
# Enter laravel container
$ docker exec -it php bash

/var/www/html# composer install
/var/www/html# php artisan key:generate
```


### DB migrations and seeding
After creating a database as configured in .env:

```bash
# Enter mysql container
$ docker exec -it db bash

bash-5.1# mysql -ularavel_user -p
mysql> CREATE DATABASE laravel;
mysql> exit
bash-5.1# exit
```

```bash
# Enter laravel container
$ docker exec -it php bash

/var/www/html# php artisan migrate
/var/www/html# php artisan db:seed
```

## Browse the app

Open [localhost:8080](localhost:8080)

## Login

Access with default credentials:

Email: root@mail.com  
Password: password

After successful login, you get redirected to the home page where you can:
- view the Brewery List's paginated table
- navigate through the list with "next" and "prev" buttons
- change the default "per_page" query parameter
- go back to Home
- logout

## Api login

POST http://localhost:8080/api/login

Body:

```JSON
{
  "email": "root@mail.com",
  "password": "password"
}
```
Response:

```JSON
{
  "success": true,
  "data": {
    "token": "1|Bfne1hSpJgpAHtbwJhpYR0LgOXIkqcw9mmlsmlZc10cf0b3e",
    "name": "root"
  },
  "message": "User login successfully."
}
```

## Api logout

Setting an Authorization header with Bearer token, you can call:

POST http://localhost:8080/api/logout

```
Response:

```JSON
{
  "status": "success",
  "message": "User logged out successfully"
}
```

## Api call

Setting an Authorization header with Bearer token, you can call:

GET http://localhost:8080/api/breweries?page=1&per_page=10

Response:

```JSON
{
  "success": true,
  "data": [
    {
      "id": "6d14b220-8926-4521-8d19-b98a2d6ec3db",
      "name": "10 Barrel Brewing Co",
      "brewery_type": "large",
      "address_1": "62970 18th St",
      "address_2": null,
      "address_3": null,
      "city": "Bend",
      "state_province": "Oregon",
      "postal_code": "97701-9847",
      "country": "United States",
      "longitude": "-121.281706",
      "latitude": "44.08683531",
      "phone": "5415851007",
      "website_url": "http:\/\/www.10barrel.com",
      "state": "Oregon",
      "street": "62970 18th St"
    },
    ...
  ]
}
```

## Test

```bash
/var/www/html# php artisan test
```
> **_NOTE:_** tests use RefreshDatabase, so after test please run again the seeder.