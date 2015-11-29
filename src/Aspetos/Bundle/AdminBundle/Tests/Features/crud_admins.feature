Feature: CRUD Admins
  In order to manage objects
  As an administrator
  I need to be able to browse, show and edit entities

  Background: 
    Given I am loading the default test fixtures
    And I am authenticated as User "max.mustermann@dummy.local"

  Scenario Outline: 100 Lists and grids
    Given I am on "<start>"
    Then the response status code should be 200
    And I should see "<caption>" in the "div.caption" element
    And I should see "Refresh" in the "a.refreshGrid" element
    And I will load the grid

    Examples: 
      | start                        | caption            |
      | /admin/mortician/list        | Morticians         |
      | /admin/supplier/list         | Suppliers          |
      | /admin/supplier/type/list    | Supplier Types     |
#      | /admin/cemetery/list         | Cemeteries         |
      | /admin/product/list          | Products           |
      | /admin/product/category/list | Product Categories |
      | /admin/user/list             | Users              |
      | /admin/permission/list       | Permissions        |

  Scenario Outline: 200 Create forms
    Given I am on "<start>"
    Then the response status code should be 200
    And I should see "<caption>" in the "div.caption" element
    And I should see "Save"
    And I press "Save"
    Then I should see "This value should not be blank."

    Examples: 
      | start                          | caption          |
      | /admin/mortician/create        | Mortician        |
      | /admin/supplier/create         | Supplier         |
      | /admin/supplier/type/create    | Supplier Type    |
      | /admin/cemetery/create         | Cemetery         |
      | /admin/product/create          | Product          |
      | /admin/product/category/create | Product Category |
      | /admin/user/create             | User             |
      | /admin/permission/create       | Permission       |

  Scenario Outline: 300 Edit form
    Given I am on "<start>"
    Then the response status code should be 200
    And I should see "<caption>" in the "div.caption" element
    And I should see "Save"

    Examples: 
      | start                          | caption          |
      | /admin/mortician/edit/3        | Mortician        |
      | /admin/supplier/edit/4         | Supplier         |
      | /admin/supplier/type/edit/1    | Supplier Type    |
#      | /admin/cemetery/edit/1         | Cemetery         |
      | /admin/product/edit/1          | Product          |
      | /admin/product/category/edit/1 | Product Category |
      | /admin/user/admin/edit/1       | Admin            |
      | /admin/permission/edit/1       | Permission       |

  Scenario Outline: 300 Updating objects
    # editing an object 2 times and checking the value afterwards
    Given I am on "<start>"
    Then the response status code should be 200
    And the "<field>" field should contain "<original-value>"
    And I fill in "<field>" with "<test-value>"
    And I press "Save"
    Then the response status code should be 200
    And I should see "<list-caption>" in the "div.caption" element
    Then I am on "<start>"
    Then the response status code should be 200
    And the "<field>" field should contain "<test-value>"
    And I fill in "<field>" with "<original-value>"
    And I press "Save"
    Then the response status code should be 200
    And I should see "<list-caption>" in the "div.caption" element
    Then I am on "<start>"
    And the "<field>" field should contain "<original-value>"

    Examples: 
      | start                          | field      | original-value  | test-value           | list-caption       |
      | /admin/supplier/type/edit/1    | Name       | Test Typ 1      | test-Test Typ 1      | Supplier Types     |
      | /admin/cemetery/edit/1         | Name       | foo             | test-foo             | Cemeteries         |
      | /admin/product/edit/2          | Name       | Erinnerungsrose | test-Erinnerungsrose | Products           |
      | /admin/product/category/edit/1 | Name       | Produkte        | test-Produkte        | Product Categories |
      | /admin/user/admin/edit/1       | Firstname  | Max             | testMax              | Users              |
      | /admin/permission/edit/1       | Permission | mortician.view  | test.mortician.view  | Permissions        |
