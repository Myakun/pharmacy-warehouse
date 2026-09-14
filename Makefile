composer-update:
	docker exec pharmacy-warehouse-php /usr/local/bin/composer update -d /var/www/app --prefer-dist
	docker exec pharmacy-warehouse-php /usr/local/bin/composer dump-autoload -d /var/www/app -o

docker-rebuild:
	docker compose stop
	docker compose build
	docker compose up -d --remove-orphans

reinstall-demo-data:
	docker exec pharmacy-warehouse-php /usr/local/bin/php /var/www/app/src/yii.php migrate/down all --interactive=0
	docker exec pharmacy-warehouse-php /usr/local/bin/php /var/www/app/src/yii.php migrate/up --interactive=0
	docker exec pharmacy-warehouse-php /usr/local/bin/php /var/www/app/src/yii.php install-demo-data

yii-migrate-down:
	docker exec pharmacy-warehouse-php /usr/local/bin/php /var/www/app/src/yii.php migrate/down --interactive=0

yii-migrate-down-all:
	docker exec pharmacy-warehouse-php /usr/local/bin/php /var/www/app/src/yii.php migrate/down all --interactive=0

yii-migrate-up:
	docker exec pharmacy-warehouse-php /usr/local/bin/php /var/www/app/src/yii.php migrate/up --interactive=0