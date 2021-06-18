install:
	composer install

validate:
	composer validate

brain-games:
	./bin/brain-games

brain-calc:
	./bin/brain-calc

brain-even:
	./bin/brain-even

brain-gcd:
	./bin/brain-gcd

brain-progression:
	./bin/brain-progression

brain-prime:
	./bin/brain-prime

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin
