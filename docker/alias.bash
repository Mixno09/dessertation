# shellcheck source=src/util.sh
source ~/.bashrc

dockerDir=$(dirname "${BASH_SOURCE[0]}")
dockerDir=$(realpath "$dockerDir");

function php()
{
  eval "(cd $dockerDir && docker-compose exec php-fpm php $*)"
}

function composer()
{
  eval "(cd $dockerDir && docker-compose exec php-fpm composer $*)"
}

function console()
{
  eval "(cd $dockerDir && docker-compose exec php-fpm php bin/console $*)"
}

function phpunit()
{
  eval "(cd $dockerDir && docker-compose exec php-fpm php bin/phpunit $*)"
}

function build()
{
  eval "(cd $dockerDir && docker-compose build)"
}