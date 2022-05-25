## POC

**Packages used:**

 - **doctrine2** used as DAO pattern. Allows us to easily create functional tests for DB interaction
 - **Seldaek/monolog** used for error logging to stdout
 - **thephpleague/booboo** provides customizable error handling
 - **symfony/http-foundation** provides us with request/response objects and allows us to skip a lot of boilerplate code. Also alows easy request mocking for tests


**To run:**

Use http verbed requests to issue update/create/fetch. 

```
docker compose up
http://localhost:8000/
```

**To run tests:**

```
docker compose exec app vendor/bin/phpunit tests/
```

**Static analysis**

```
docker compose exec app vendor/bin/phpstan   
```
