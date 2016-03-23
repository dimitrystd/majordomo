# Available settings

* `DB_HOST` - MySQL host
* `DB_NAME` - MySQL database name
* `DB_USER` - MySQL user name
* `DB_PASSWORD` - MySQL user password
* `MAIL_USER` - Email for notification
* `EMAIL_PASSWORD` - Email password
* `EXT_ACCESS_USERNAME` - External authentication user
* `EXT_ACCESS_PASSWORD` - External authentication password

# Usage CLI

## Build image
```
docker build --rm --tag=dmitriy/majordomo -f ./docker/Dockerfile ./docker
```

## Run as container

```
docker run -it --rm -p 80:80 -e DB_HOST=smart-home -e DB_NAME=db_terminal -e DB_USER=majordomo_sql -e DB_PASSWORD=xxx -e MAIL_USER=xxx@gmail.com -e EMAIL_PASSWORD=xxxx  dmitriy/majordomo
```
