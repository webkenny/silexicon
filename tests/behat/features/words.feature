Feature: Words

Scenario: Finding a specific word
  When I request "GET /words/abasement"
  Then I get a "200" response
  And scope into the "data" property
    And the properties exist:
        """
        word
        wordtype
        definition
        """
    And the "word" property is a string

Scenario: Listing all words is not possible
  When I request "GET /places"
  Then I get a "400" response