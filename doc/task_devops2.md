
**Task:** Prepare test environment  
**Assigned to:** DevOps  
**Estimate:** 1 - 2h  
**Description:** Setup test infrastructure on docker and docker-compose. This environment should be identical
to dev environment specified in [DEVOPS1](https://github.com/evodevo/quotes_api/tree/master/doc/task_devops1.md) except it has to use separate test database (quotes_api_test)
and separate environment variables file (.env.test).  

Update makefile produced in task DEVOPS1 to support `make test` command. This command should run Behat
tests on test environment. When executed, it should perform the following actions:
- Start up test docker containers
- Create test database
- Run database migrations
- Load fixtures (quotes.json)
- Run Behat tests
- Stop test docker containers

