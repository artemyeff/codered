## SSL

```
mkdir -p config/jwt
touch config/jwt/private.pem
touch config/jwt/public.pem
```
```
docker-compose exec php openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
docker-compose exec php openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```
