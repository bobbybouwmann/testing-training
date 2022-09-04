.PHONY: all

info: intro usage

intro:
		@echo ""
		@echo " -- Testing Training -- "
		@echo ""

usage:
		@echo "Project:"
		@echo "  make init                                 Initialise the project"
		@echo "  make update                               Update the project"
		@echo "  make start                                Start the project"
		@echo "  make stop                                 Stop the project"
		@echo "  make migrate                              Migrate the database"
		@echo "  make test                                 Run the tests"
		@echo "  make test-coverage                        Run the tests with code coverage"
		@echo "  make test-infection                       Run the tests with infection php"
		@echo "  make test-suite                           Run the testsuite"
		@echo "  make analyse                              Analyse with phpstan"
		@echo "  make codestyle                            Check the codestyle and fix the problems"
		@echo "  make codestyle-fix                        Check the codestyle and fix the problems"

# ===========================
# Commands
# ===========================

init: intro do_composer do_init do_migrate do_ide_helpers do_assets
update: intro do_composer do_migrate do_ide_helpers do_assets
migrate: intro do_migrate
start: intro do_start
stop: intro do_stop
test: intro do_tests_parallel
test-coverage: intro do_tests_coverage
test-infection: intro do_tests_infection
test-suite: intro do_tests_parallel do_phpstan do_codestyle
analyse: intro do_phpstan
codestyle: intro do_codestyle
codestyle-fix: intro do_codestyle

# ===========================
# Recipes
# ===========================

do_init:
		test -f .env || cp .env.example .env
		php artisan key:generate
		php artisan storage:link
		mysql -uroot -e "CREATE DATABASE IF NOT EXISTS testing_laravel;"
		mysql -uroot -e "CREATE DATABASE IF NOT EXISTS testing_laravel_test;"
		valet start
		valet link testing-training
		valet secure testing-trainings

do_start:
		valet start

do_stop:
		valet stop

do_composer:
		composer install

do_assets:
		npm install
		npm run dev

do_watch:
		npm install
		npm run watch

do_tests_parallel:
		php artisan test --parallel

do_tests_coverage:
		XDEBUG_MODE=coverage ./vendor/bin/phpunit -d memory_limit=2048M --coverage-html public/coverage --coverage-text

do_tests_infection:
		XDEBUG_MODE=coverage vendor/bin/infection --no-progress --threads=8

do_codestyle:
		./vendor/bin/pint

do_phpstan:
		./vendor/bin/phpstan analyse

do_ide_helpers:
		php artisan ide-helper:generate
		php artisan ide-helper:models --nowrite
		php artisan ide-helper:meta

do_migrate:
		php artisan migrate:fresh --seed
