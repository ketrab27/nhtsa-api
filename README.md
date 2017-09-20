# nhtsa-api

## Application requirements

* PHP >= 7.0 (tested at 7.1.9)
* OpenSSL PHP Extension
* PDO PHP Extension
* Mbstring PHP Extension

## How to install

* Download repo
* Run composer install
* Run server app by typing in console: php -S localhost:8080

### Requirement 1
```
GET http://localhost:8080/vehicles/<MODEL YEAR>/<MANUFACTURER>/<MODEL>
```

### Requirement 2
```
POST http://localhost:8080/vehicles
```

### Requirement 3

When the endpoint:

```
GET http://localhost:8080/vehicles/<MODEL YEAR>/<MANUFACTURER>/<MODEL>?withRating=true
```
