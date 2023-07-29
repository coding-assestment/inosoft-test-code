# how to run 
## first run 
 docker-compose up -d 
## only up app 
 docker-compose up --build app

## how to test
### using postman 
- import "INOSOFT.postman_collection.json" into your postman and play it
### PHP Unit 
- docker exec -it inosoft sh
- ./vendor/bin/phpunit
