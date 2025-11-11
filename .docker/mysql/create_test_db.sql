-- Create the test database
CREATE DATABASE IF NOT EXISTS laravel_test;

-- Grant full privileges on the test database to existing user
GRANT ALL PRIVILEGES ON laravel_test.* TO 'laravel'@'%';

FLUSH PRIVILEGES;
