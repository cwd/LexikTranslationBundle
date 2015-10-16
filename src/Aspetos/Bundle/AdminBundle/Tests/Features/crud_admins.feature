Feature: CRUD Admins
  In order to manage objects
  As an administrator
  I need to be able to browse, show and edit entities

  Background: 
    Given I am loading the default test fixtures
    And I am authenticated as User "max.mustermann@dummy.local"

  Scenario Outline: 100 Lists and grids
    Given I am on "<start>"
    Then I should see "<see>"
    And I should see "Aktualisieren"
    And I will load the grid

    Examples: 
      | start                  | see         |
      | /admin/user/list       | Benutzer    |
      | /admin/cemetery/list   | Cemeteries  |
      | /admin/permission/list | Permissions |

  Scenario Outline: 200 Create forms
    Given I am on "<start>"
    Then I should see "<see>"
    And I should see "Speichern"
    And I press "Speichern"
    Then I should see "Dieser Wert sollte nicht leer sein."

    Examples: 
      | start                    | see        |
      | /admin/user/create       | Benutzer   |
      | /admin/cemetery/create   | Cemetery   |
      | /admin/permission/create | Permission |

  Scenario Outline: 300 Edit form
    Given I am on "<start>"
    #Then I will dump the content
    Then I should see "<title>"
    And I should see "Speichern"

    Examples: 
      | start                    | title |
      | /admin/user/admin/edit/1 | Admin |
      | /admin/cemetery/edit/1   | Admin |
      | /admin/permission/edit/1 | Admin |

  Scenario Outline: 300 Updating objects
    # editing an object 2 times and checking the value afterwards
    Given I am on "<start>"
    And the "<field>" field should contain "<original-value>"
    And I fill in "<field>" with "<test-value>"
    And I press "Speichern"
    Then I should see "Aktualisieren"
    Then I am on "<start>"
    And the "<field>" field should contain "<test-value>"
    And I fill in "<field>" with "<original-value>"
    And I press "Speichern"
    Then I should see "Aktualisieren"
    Then I am on "<start>"
    And the "<field>" field should contain "<original-value>"

    Examples: 
      | start                    | field      | original-value | test-value          |
      | /admin/user/admin/edit/1 | Firstname  | Max            | testMax             |
      | /admin/cemetery/edit/1   | Name       | foo            | test-foo            |
      | /admin/permission/edit/1 | Permission | mortician.view | test.mortician.view |
