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
    And I should see <products> ".main .products div.product-item" elements

    Examples: 
      | start                               | title         | products |
      | /shop/produkte/blumen               | Blumen        | 3        |
      | /shop/produkte/blumen/trauerkraenze | Trauerkränze  | 0        |
      | /shop/produkte/blumen/straeusse     | Sträuße       | 1        |
      | /shop/produkte/verschiedenes        | Verschiedenes | 2        |
      | /shop/produkte/kerzen               | Kerzen        | 0        |

  Scenario Outline: 20 Product detail pages
    Given I am on "<start>"
    Then the response status code should be 200
    And the current main menu item should be "Shop"
    And I should see "<title>" in the "h1" element
    And I should see "<cat-title>" in the ".sidebar ul.page-sidebar-menu li.current" element

    Examples:
      | start                                       | title                                 | cat-title     |
      | /shop/p/erinnerungsrose                     | Erinnerungsrose                       | Blumen        |
      | /shop/p/friedhofsbote                       | Friedhofsbote                         | Blumen        |
      | /shop/p/friedhofsbote-teller                | Friedhofsbote Teller                  | Sträuße       |
      | /shop/p/immer-und-ewig-errinerungskristalle | Immer und Ewig - Errinerungskristalle | Verschiedenes |
      | /shop/p/klamottchen                         | Klamottchen                           | Verschiedenes |

  Scenario: 30 Add item to shopping cart
    Given I am on "/shop/p/klamottchen"
    Then there should be 0 items in the cart, totalling "€0,00"
    Then I press "In Warenkorb"
    And the response status code should be 200
    And there should be 1 items in the cart, totalling "€44,90"
    Then I fill in "aspetos_shop_order_item_amount" with "3"
    Then I press "In Warenkorb"
    And the response status code should be 200
    And there should be 4 items in the cart, totalling "€179,60"
