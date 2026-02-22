init:
	docker compose build
	docker compose up -d
	cp src/.env.example src/.env
	docker compose exec -T php composer install
	docker compose exec -T php php artisan key:generate
	@echo "MySQLの起動を待っています..."
	@until docker compose exec -T mysql mysqladmin ping -h 127.0.0.1 -u laravel_user -plaravel_pass --silent; do \
		echo "接続待機中..."; \
		sleep 2; \
	done
	docker compose exec -T php php artisan migrate --seed
	docker compose exec -T php php artisan storage:link
	@echo "初期セットアップ完了"
