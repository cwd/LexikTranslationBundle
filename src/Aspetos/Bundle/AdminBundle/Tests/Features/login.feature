Feature: Login
  In order to login an admin user
  As a visitor
  I need to be able to submit the login form

  Scenario: 01 Login Form
    Given I am on "/admin"
    Then I should see "Sign In"

  Scenario: 02 Login to Dashboard
    Given I am on "/admin"
    When I fill in "username" with "max.mustermann@dummy.local"
    And I fill in "password" with "asdf"
    And I press "Login"
    Then I am on "/admin"
    And I should see "Overview Aspetos.at"
    And I should see "Overview Aspetos.de"
