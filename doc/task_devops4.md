
**Task:** Set up continuous integration  
**Assigned to:** DevOps  
**Estimate:** 8 - 12h  
**Description:** The goal is to set up continuous integration and delivery server for the Quotes API project
that would run configured pipelines either manually or automatically.   

Create the following CI/CD pipelines:
- **feature:** Build and test the new feature before it is integrated into master.
  - Created automatically for Pull Requests to master branch
  - Triggered by source commits to feature branch
  - Stages: build, test
- **dev:** Build and test a new project version from master on every commit, store produced artifact in the artifact repository.
  - Triggered by source commits to master branch
  - Stages: build, test, deploy to dev server
- **qa:** Deploy the selected build produced by the **dev** pipeline to the QA server.
  - Triggered manually by the QA
  - Stages: deploy selected artifact to QA server
- **production:** Make a production build, store produced artifact in the artifact repository, deploy to staging server,
deploy to production server after manual approval.
  - Created automatically for every release branch
  - Triggered by source commits to the release branch
  - Stages: build, test, deploy to staging, deploy to production

The CI/CD pipelines should have one or more of the following stages:
- **Build:** After a successful build, the produced artifact should be stored in the repository where it can be accessed by other stages. This stage should perform the following steps:
  - Check out source code from the repository.
  - Install dependencies, compile assets (if any).
  - Run phpspec unit tests.
  - Generate code coverage report. If code coverage is below the selected threshold the build fails.
  - Perform static code analysis (checks if coding standards are met, generates code metrics, checks for security vulnerabilities).
- **Test:** Run Behat acceptance tests suite on the previously built package.
- **Deploy:** Deploy the previously built package to the specified environment. All deployments must be zero-downtime.
- Send Slack notification about the build status

