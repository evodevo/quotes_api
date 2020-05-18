Feature:
  In order to get inspired
  As an api consumer
  I want to get quotes from famous people shouted to me

  Scenario: Request quotes from author
    When I make request "GET" "/shout/steve-jobs"
    Then the response status code should be 200
    And the response JSON collection should not be empty
    And the response collection should count "2" items
    And the response JSON collection should contain value "YOUR TIME IS LIMITED, SO DON’T WASTE IT LIVING SOMEONE ELSE’S LIFE!"
    And the response JSON collection should contain value "THE ONLY WAY TO DO GREAT WORK IS TO LOVE WHAT YOU DO!"

  Scenario: Request quotes from author with limit
    When I make request "GET" "/shout/steve-jobs?limit=1"
    Then the response status code should be 200
    And the response JSON collection should not be empty
    And the response collection should count "1" items

  Scenario: Request quotes from author with zero limit (unlimited)
    When I make request "GET" "/shout/steve-jobs?limit=0"
    Then the response status code should be 200
    And the response JSON collection should not be empty
    And the response collection should count "2" items

  Scenario: Request quotes from author with limit over threshold
    When I make request "GET" "/shout/steve-jobs?limit=11"
    Then the response status code should be 400
    And the response JSON should be a single object
    And the response JSON should have "code" field with value "400"
    And the response JSON should have "message" field

  Scenario: Request quotes from author with invalid limit
    When I make request "GET" "/shout/steve-jobs?limit=invalid"
    Then the response status code should be 400
    And the response JSON should be a single object
    And the response JSON should have "code" field with value "400"
    And the response JSON should have "message" field

  Scenario: Request quotes from non-existing author
    When I make request "GET" "/shout/elon-musk"
    Then the response status code should be 404
    And the response JSON should be a single object
    And the response JSON should have "code" field with value "404"
    And the response JSON should have "message" field

  Scenario: Request non-existing api method
    When I make request "GET" "/non-existing"
    Then the response status code should be 404
    And the response JSON should be a single object
    And the response JSON should have "code" field with value "404"
    And the response JSON should have "message" field



