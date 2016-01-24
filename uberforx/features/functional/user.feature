Feature: Welcoming developer
    As a Laravel developer
    In order to proberly begin a new project
    I need to be greeted upon arrival
 
    Scenario: user Login
        Given I am on "user/signin"
		When I fill in "email" with "mike.jones@example.com"
		When I fill in "password" with "123456"
		And I press "user-signin"
		Then I should see "My Trips"

	Scenario: User Profile
        Given I am on "user/signin"
		When I fill in "email" with "mike.jones@example.com"
		When I fill in "password" with "123456"
		And I press "user-signin"
		And I am on "user/trips"
		When I follow "profile"
		Then I should see "Update Profile"

	Scenario: User Profile
        Given I am on "user/signin"
		When I fill in "email" with "mike.jones@example.com"
		When I fill in "password" with "123456"
		And I press "user-signin"
		And I am on "user/profile"
		When I fill in "first_name" with "Lazar"
		When I fill in "last_name" with "Savarinadin"
		When I fill in "email" with "lazar8019@gmail.com"
		When I attach the file "/uploads/" to "picture"
		When I fill in "phone" with "7358697562"
		When I fill in "bio" with "Cool"
		When I fill in "address" with "Sivaji Nagar, Bangalore"
		When I fill in "state" with "Karnataka"
		When I fill in "country" with "India"
		When I fill in "zipcode" with "550066"
		And I press "update"
		Then I should see "My Profile"

	Scenario: User Payments
        Given I am on "user/signin"
		When I fill in "email" with "mike.jones@example.com"
		When I fill in "password" with "123456"
		And I press "user-signin"
		And I am on "user/payments"
		When I fill in "number" with "4012 0000 3333 0026"
		When I fill in "month" with "08"
		When I fill in "year" with "2025"
		And I press "submit"
		Then I should see "Payments and Credits"	

	
    Scenario: User Signup
        Given I am on "user/signup"
		When I fill in "first_name" with "Lazar"
		When I fill in "last_name" with "Savarinadin"
		When I fill in "phone" with "7358697562"
		When I fill in "email" with "mike.jones@example.com"
		When I fill in "password" with "123456"
		When I fill in "referral_code" with "550066"
		And I press "register"
		Then I should see "sign"

	Scenario: User Change Password
        Given I am on "user/signin"
		When I fill in "email" with "mike.jones@example.com"
		When I fill in "password" with "123456"
		And I press "user-signin"
		And I am on "user/profile"
		When I fill in "current_password" with "Lazar"
		When I fill in "new_password" with "Savarinadin"
		When I fill in "confirm_password" with "7358697562"
		And I press "pass"
		Then I should see "My Profile"