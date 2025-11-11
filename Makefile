-include $(or ${APP_ENV_FILE},${APP_ENV_FILE},.env)

.PHONY: ssh
ssh:
	@docker compose exec -it laravel-app bash

.PHONY: docker-start
docker-start:
	@docker compose up -d

.PHONY: docker-start-fresh
docker-start-fresh:
	@docker compose up --build -d

