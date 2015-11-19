Feature: Shop public
  In order to buy services
  As a visitor
  I want to be able to browse products and categories

  Background: 
    Given I have loaded the default test fixtures

  Scenario: 01 Shop start page
    Given I am on "/shop"
    Then the response status code should be 200
    And I should see "Beliebte Produkte" in the "h2" element
    And the current main menu item should be "Shop"

  Scenario Outline: 10 Shop category pages
    Given I am on "<start>"
    Then the response status code should be 200
    And the current main menu item should be "Shop"
    And I should see "<title>" in the "h2" element
    And I should see "<title>" in the ".sidebar ul.page-sidebar-menu li.current" element

    Examples: 
      | start                               | title         |
      | /shop/produkte/blumen               | Blumen        |
      | /shop/produkte/blumen/trauerkraenze | Trauerkränze  |
      | /shop/produkte/blumen/straeusse     | Sträuße       |
      | /shop/produkte/verschiedenes        | Verschiedenes |
      | /shop/produkte/kerzen               | Kerzen        |
