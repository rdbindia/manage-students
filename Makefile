migrate:
	docker exec -it manageStudents php artisan migrate

up:
	docker-compose up -d

seed:
	docker exec -it manageStudents php artisan migrate --seed

createuser:
	docker exec -it manageStudents php artisan vendor:publish --tag=filament-config
	docker exec -it manageStudents php artisan filament:install --panels || true
	docker exec -it manageStudents php artisan make:filament-user
