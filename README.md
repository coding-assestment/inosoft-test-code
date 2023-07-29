# Inosoft Test Code 
## working scope
1. Service Repository Pattern (done)
2. Strict class type (done)
3. Menyertakan unit test (done)
4. Format request dan response menggunakan standar HTTP REST API (done)
5. Menggunakan akses token untuk autentikasi (Json Web Token)
# how to run 
## first run 
- cp .env.example .env
- docker-compose up -d 
## only up app 
 docker-compose up --build app

## how to test
### using postman 
- import "INOSOFT.postman_collection.json" into your postman and play it
### PHP Unit 
- docker exec -it inosoft sh
- ./vendor/bin/phpunit
