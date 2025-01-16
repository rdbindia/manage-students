migrate:
	docker exec -it manageStudents php artisan migrate

up:
	docker-compose up -d

seed:
	docker exec -it manageStudents php artisan migrate --seed
