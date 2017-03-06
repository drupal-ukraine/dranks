@openy @api
Feature: Program and Subcategory pages
  As Admin I want to make sure that Program and Subcategory can be created.
  And I should see paragraph with Subcategory teaser on Program page.

  Scenario: Create basic program and subcategory and check fields
    Given I am logged in as a user with the "Administrator" role
    And I create a color term
    When I go to "/node/add/program"
    And I fill in "Title" with "Fitness"
    And I select "Magenta" from "Color"
    And I fill in the following:
      | Description | Program suggests fitness classes for all ages. |
    And I press "Add Categories Listing" in the "content_area"
    When I press "Save and publish"
    Then I should see the message "Program Fitness has been created."

    Given I am logged in as a user with the "Administrator" role
    When I go to "/node/add/program_subcategory"
    And I fill in "Title" with "Personal Training"
    And I fill in "Program" with "Fitness"
    And I select "Magenta" from "Color"
    And I fill media field "edit-field-category-image-target-id" with "media:1"
    And I fill in the following:
      | Description | Program suggests fitness classes for all ages. |
    When I press "Save and publish"
    Then I should see the message "Program Subcategory Personal Training has been created."

    When I go to "/programs/fitness"
    Then I see the heading "Fitness"
    And I should see the heading "Personal Training"
    And I should see a ".subprogram-listing-item img" element
    And I should see "Program suggests fitness classes for all ages."
    And I should see the link "Open category"

