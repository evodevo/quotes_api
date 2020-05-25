
## Quotes API Project Execution Strategy Document

### Purpose of this document

This document presents a project plan of a Quotes API. This document is defined at the
beginning of the project and will be revised whenever a change in project organization occurs. Any change in
deadlines, documents or parts of the program that are supposed to be delivered will also be documented 
in future revisions.

### Scope

This document gives general information about Quotes API project. It describes a team organization and
development process in general. All the project plan, roles and risks significant for the
project are defined in this document. However, detailed information about some parts of the project (such as
implementation details) are given in other documents.

### Objectives

The main goal of this project is to develop REST API for shouting famous people quotes. The API is supposed 
to be well-tested, secure, stable and meet high performance demands. 
The development will begin with the core functionality and new features will be added with time, if needed. 
The integration strategy of the system will be feature-based. After the feature is completed, it will be 
tested standalone first and then it will be integrated into the system. After the integration, another 
round of testing will take place. After the system reaches a set milestone, it will be released to production. 
The developed software will be complemented by any necessary documentation.

### Development Process

Initially Kanban project management methodology will be used, however, this might change in the 
future. Product owner is responsible for always keeping product backlog in priority order.
Product backlog consists of both technical tasks and product features described as User Stories, 
User Stories are the things that make sense to the end-users of the system. So, for example, since 
setting up a system infrastructure is not something that would be visible to any of the stakeholders 
of the project, it isn’t considered a User Story. An example User Story is “In order to get inspired, 
as a user, I want to get famous quotes shouted to me,” implying that both system infrastructure and 
coding related tasks need to be finished before User Story would be considered done. User Story is 
considered done when all its acceptance criteria are satisfied. Development is done on a 
feature-by-feature basis. Developers are required to pick User Stories from the backlog with the highest 
priority first. We measure project progress by establishing milestones. Once we set up a project 
milestone, we are able to calculate how long it will take to reach this milestone by adding up the 
estimates of all the tasks included.  
Jira will be used to help team members organize their work and monitor the status of the project.

### Project Roles

**Product Owner**

Responsible for defining product requirements and features, prioritizes product backlog. 
He is also responsible for reviewing and accepting or declining every new feature a development team delivers.

**Team Lead**

Interfaces with outside parties and helps the team to remove the impediments and difficulties they experience 
that prevent them from doing the work required to deliver the product on time. Responsible to keeping the 
smooth coordination and collaboration between the team members. He may also lead internal 
team meetings like stand-ups and retrospectives.

**Development Team (Developers, QAs, DevOps)**

Responsible for gathering requirements and, implementing, testing, and integrating product features.

### Project Risks

**Risk**: Failing to finish development on time  
**Preventive action**: Dividing the features into more manageable tasks, scheduling as much work in parallel 
as possible, distributing the work evenly between team members to avoid overloading some team members.
In case there is a clear delay the following actions can be taken: either reduce project scope or add extra resources
(bring more people, extend working hours). If project delay is the result of poor planning, it should be
addressed and solved in the next retrospective meeting, so that the issue does not repeat.

**Risk**: Bad communication between team members  
**Preventive action**: Regular weekly meetings, the help from the Team Lead to coordinate the team.

**Risk**: Lack of motivation for working on a project  
**Preventive action**: Constant communication between team members and collaboratively solving the problems.

**Risk**: Lack of technological knowledge  
**Preventive action**: Choosing technologies for the project that most team members are familiar with, 
and taking their expertise into consideration when dividing tasks between team members.

**Risk**: Team member leaves the project  
**Preventive action**: Making sure that Team Lead is always informed about project status and making sure 
that always at least two team members work together on important parts of the project.

**Risk**: Problems with system integration  
**Preventive action**: Well defined interfaces between components and fluent communication between members 
who are developing related components.

**Risk**: Final product doesn’t meet the requirements  
**Preventive action**: Regularly reviewing the work that has been done by the team members to keep the 
project on track.


### Communication

Day to day communication will be carried mainly on Slack. Progress and issues on individual
tasks can be discussed through task comments on Jira. The team has a daily stand-up meeting 
to catch up on the current progress of the project and to express if there are any impediments that
prevent them from moving forward. More meetings can be arranged during the week if the need arises.
Every meeting has a person who leads the meeting. This team member should be prepared
for the meeting. He should decide on the topics that will be discussed and write the summary of 
the meeting afterwards which can be read by other interested parties. The meetings will be 
held on Google Hangouts.  
Other non-urgent communication is done through project documentation. All the changes to 
project organization or processes need to be documented. The person introducing these 
changes is also responsible for updating respective documentation. Developers are required to 
write or update any required documentation in order for the User Story to be considered done. 
QA is responsible for verifying that all required documentation was updated.


### Development workflow

The goal is to enable a true Continuous Integration and have a tight feedback loop according
to the Lean principles, therefore a modified version of trunk-based development workflow will be 
used which involves short-lived feature branches and Pull-Request style code reviews. Large-scale 
changes will be made using branch-by-abstraction. Unfinished features will be hidden from user 
using feature toggles.  

The following git branches will be used:
- **feature branch:** Branched off from master when a new feature needs to be implemented.
The lifetime of this branch should be as short as possible (couple of days at most) so that 
small changes are applied incrementally without big merges and developers get fast feedback,
and can iterate quickly.
- **master branch:** A branch where all the developed features are integrated and tested by QA. 
 All production release branches will be created from this branch.
- **release branch:** A branch created off of master when its time for a new release. This is what
gets deployed to staging for another round of QA testing and eventually to production.
- **hotfix branch:** A branch made off of the release branch in case there is a problem that
needs to be fixed after the release is created. 

The development workflow is as follows:  
- Developers make feature branches from master. 
- While developing a feature developers run automated tests on their local machines to get a 
quick feedback about the quality of their code. 
- Whenever developers need feedback or when a feature is completed, they raise a Pull Request 
to start a discussion. A pool of designated reviewers are assigned to this Pull Request. 
The discussion in this Pull Request can also act as searchable documentation of the 
implementation details of this particular feature.   
- A CI pipeline is created automatically for every Pull Request so that CI build is 
run on the feature branch before it is merged to master. So it is very unlikely that the build
will break after the merge.
- Commits are squashed when merging to master (by doing a squash-merge) so that we have a clean 
history. We do *fast-forward only* merges so that there are no merge commits and the history on 
master stays linear. The following merges are not allowed for feature branches:
    - Merge to other developer's short-lived feature branches
    - Direct merge to any of the release branches
- After the feature branch is merged into the master and the CI build is successful, the branch 
should be deleted. Developers are not allowed to break builds on master. If the build on master 
is broken it is the first priority for the team to fix it.   
- QA engineers can test the deployed build from master branch, covering the tasks up to a certain 
commit or the latest commit. If the testing is done to the latest commit, a release branch can be 
created from the tip of the master. Otherwise we can create the release branch from the commit 
which the testing is completed up to.  
- When a release branch is created from master, a CI build is run on it and it is deployed to a
staging server. QA does another round of testing in a production-like environment possibly with 
real (masked) data before promoting this release to production.  
- If any bug is found at this point, a hotfix branch is created from the release branch. When the 
fix is implemented it is merged into master and then back into the release branch. We do the 
merging here instead of cherry-picking therefore it is important for developers to remember to 
merge a hotfix to master also, otherwise we will have a regression in the next release.  
- After the release is deployed to production successfully and smoke tests have passed, the old 
release branch is deleted (if any).


**CI/CD pipeline**
 
Changes to source repository branches (feature, master, release, hotfix) trigger CI/CD 
pipeline for that specific branch. The artifact that is built by the CI pipeline is promoted 
across environments (qa, staging, production). The same artifact is reused for each environment 
to have a guarantee that the same thing that was built and tested was actually released.
Each pipeline has one or more of the following stages:
- **Build:** A successful build generates and stores an artifact - a package that is ready to be 
deployed to an environment. This stage performs the following steps:
    - Checks out source code from the repository.
    - Installs dependencies, compiles assets (if any).
    - Runs unit tests.
    - Generates code coverage report. If code coverage is below the selected threshold the build
     fails.
    - Performs static code analysis (checks if coding standards are met, generates code metrics, 
    checks for security vulnerabilities).  
- **Test:** Runs acceptance tests on the previously built package.
- **Deploy:** Deploys the previously built package to the selected environment.  
After the pipeline is finished it sends notification about its success or failure.

The pipelines are defined in [DEVOPS4](https://github.com/evodevo/quotes_api/blob/master/doc/task_devops4.md)


### Software quality control

Software quality is ensured by manual and automated testing. Developers are required to cover 
software components with unit tests using phpspec. High test coverage is required (> 90%).
Continuous Integration server generates coverage report for each build. If coverage is less
then predefined threshold, the build fails.  
QA engineers are responsible for creating a set of test cases and writing functional tests 
using Behat. Automated tests are run on continuous integration server for every build and status of 
every build is reported to Slack. Additionally manual testing is performed to catch 
cases missed by automated tests. All the found bugs are reported in Jira and fixed by the developers
according to priority.

### Definition of done
 
The following criteria must be satisfied in order for any User Story to be considered done:
- source code written
- unit tests written and passed
- code review passed
- acceptance criteria met
- functional tests passed
- non-functional requirements met
- all needed documentation written or updated
- Product Owner accepts User Story


### Project Plan

Project will be executed in the following steps:
- Prepare development environment ([DEVOPS1](https://github.com/evodevo/quotes_api/tree/master/doc/task_devops1.md))
- Prepare test environment ([DEVOPS2](https://github.com/evodevo/quotes_api/tree/master/doc/task_devops2.md))
- Initial project setup ([DEV1](https://github.com/evodevo/quotes_api/tree/master/doc/task_dev1.md))
- Quotes shouting API User Story ([US1](https://github.com/evodevo/quotes_api/tree/master/doc/user_story1.md)).  
    Tasks for the User Story US1:
    - Design system architecture ([DEV2](https://github.com/evodevo/quotes_api/tree/master/doc/task_dev2.md))
    - Develop quotes shouting API endpoint ([DEV3](https://github.com/evodevo/quotes_api/tree/master/doc/task_dev3.md))
    - Write functional tests ([QA1](https://github.com/evodevo/quotes_api/tree/master/doc/task_qa1.md))
    - Implement caching layer ([DEV4](https://github.com/evodevo/quotes_api/tree/master/doc/task_dev4.md))
    - Test caching layer ([QA2](https://github.com/evodevo/quotes_api/tree/master/doc/task_qa2.md))
- Set up development server ([DEVOPS3](https://github.com/evodevo/quotes_api/tree/master/doc/task_devops3.md))
- Set up continuous integration ([DEVOPS4](https://github.com/evodevo/quotes_api/tree/master/doc/task_devops4.md))


*Remarks:*
- Since requirements for the RabbitMQ and Redis are known from the beginning, we set up these
components in the initial setup of the development environment;
- Initially we set up development server so that developers can use it to test and integrate their work.
We will set up staging and production servers when we are ready to go to production;
- Quotes shouting API is split into base functionality and caching layer so that
QA can start testing the base functionality while caching layer is still being developed;


### Out of scope

The following things are not yet considered for this project:
- Staging and production infrastructure and deployment
- Logging and monitoring
- Uptime and Site Reliability
- Scaling
- Backups and disaster recovery
