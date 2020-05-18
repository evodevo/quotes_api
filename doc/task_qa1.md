
**Task:** Write functional tests  
**Assigned to:** QA  
**Estimate:** 1 - 2h  
**Description:** The goal is to write functional tests for the Quotes API. These tests
should run on test environment produced in [DEVOPS2](https://github.com/evodevo/quotes_api/tree/master/doc/task_devops2.md).

Write functional tests for the following test cases that are derived from Acceptance Criteria 
in User Story 1:
- A user successfully requests quotes to be shouted
- A user successfully requests quotes with specified result limit applied
- A user successfully requests quotes with default result limit
- A user gets an exception if request limit provided is over threshold
- A user gets an exception if request limit provided is invalid
- A user gets an exception if author with invalid slug is requested
- A user gets an exception if non-existing author is requested
- A user gets an exception if non-existing API method is called

**Constraints:**
- Behat must be used as a testing tool


