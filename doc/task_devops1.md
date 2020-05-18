
**Task:** Prepare development environment  
**Assigned to:** DevOps  
**Estimate:** 2 - 3h  
**Description:** Prepare a local development environment for the Quotes API project using docker and
docker-compose.  

The environment should be set up with the following components:
- php-fpm >= 7.1 with composer
- nginx serving quotes api vhost by default
- mysql with quotes_api database and user created (username: quotes_api, password: supersecret)
- rabbitmq
- redis
- php >= 7.1 worker with composer and supervisor. Supervisor should be configured to restart worker
automatically on error and it should log all output to stdout.  

All the components should be in separate docker containers. All the logging should go to stdout so that
logs can be read via `docker logs`
Docker containers should use Alpine OS where possible (so they are more lightweight) and preferably 
officially supported containers 
for software components.  

All the commands supported by the development environment should be specified
in the makefile. It should be able to perform the following tasks:
- create new development environment from scratch
- rebuild existing development environment
- start existing development environment
- stop currently running development environment
- install and update project dependencies
- destroy and recreate development database
- execute database migrations
- load project fixtures
- run Behat tests
- run phpspec tests
- show the list of available commands (show help)

