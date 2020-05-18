
**Task:** Set up continuous integration  
**Assigned to:** DevOps  
**Estimate:** 1 - 3h  
**Description:** The goal is to set up continuous integration and deployment server for the Quotes API project
that would run configured pipelines either manually or automatically.   

It should be able to perform the following actions for the Quotes API project:
- Run builds and deployments either manually or automatically on source code updates
- Run phpspec unit tests
- Generate test coverage report
- Run Behat functional tests
- Deploy to development server (either manually or automatically on successful build)
- Send Slack notifications on successful or failed builds
- Rollback deployments