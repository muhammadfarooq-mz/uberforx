Feature: Welcoming developer
    As a Laravel developer
    In order to proberly begin a new project
    I need to be greeted upon arrival
 
    Scenario: Provider Signup
        Given I am on "provider/signup"
		When I fill in "first_name" with "Lazar"
		When I fill in "last_name" with "Savarinadin"
		When I fill in "phone" with "7358697562"
		When I fill in "email" with "lazar8019@gmail.com"
		When I fill in "password" with "123456"
		And I press "provider-signup"
		Then I should see "sign"

	Scenario: Provider Login
        Given I am on "provider/signin"
		When I fill in "email" with "lazar8019@gmail.com"
		When I fill in "password" with "123456"
		And I press "provider-signin"
		Then I should see "Filter"

	Scenario: Provider Filter Trips
        Given I am on "provider/signin"
		When I fill in "email" with "lazar8019@gmail.com"
		When I fill in "password" with "123456"
		And I press "provider-signin"
		And I am on "provider/trips"
		When I fill in "start-date" with "01/01/2015"
		When I fill in "end-date" with "28/02/2015"
		And I press "filter"
		Then I should see "Filter"

	Scenario: Update Provider Profile
        Given I am on "provider/signin"
		When I fill in "email" with "lazar8019@gmail.com"
		When I fill in "password" with "123456"
		And I press "provider-signin"
		And I am on "provider/profile"
		When I fill in "first_name" with "Lazar"
		When I fill in "last_name" with "Savarinadin"
		When I fill in "email" with "lazar8019@gmail.com"
		When I attach the file "/uploads/" to "picture"
		When I fill in "phone" with "7358697562"
		When I check "service"
		When I fill in "base_price" with "500"
		When I fill in "distance_price" with "60"
		When I fill in "time_price" with "75"
		When I fill in "bio" with "Cool"
		When I fill in "address" with "Sivaji Nagar, Bangalore"
		When I fill in "state" with "Karnataka"
		When I fill in "country" with "India"
		When I fill in "zipcode" with "550066"
		And I press "update"
		Then I should see "Update Availability"

	Scenario: Provider Change Password
	    Given I am on "provider/signin"
		When I fill in "email" with "lazar8019@gmail.com"
		When I fill in "password" with "123456"
		And I press "provider-signin"
		And I am on "provider/profile"
		When I fill in "current_password" with "123456"
		When I fill in "new_password" with "1234566"
		When I fill in "confirm_password" with "1234566"
		And I press "pass"
		Then I should see "Update Availability"

	