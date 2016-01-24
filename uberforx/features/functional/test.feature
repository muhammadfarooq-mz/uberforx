Feature: Welcoming developer
    As a Laravel developer
    In order to proberly begin a new project
    I need to be greeted upon arrival
 
    Scenario: Admin Login
        Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		When I fill in "start_date" with "01/01/2015"


	Scenario: Add Provider Page
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/providers"
		When I follow "addpro"
		Then I should see "Zipcode"

	Scenario: Provider Bank Details Page
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/providers"
		When I follow "addbank"
		Then I should see "Banking Details Provider"

	Scenario: Provider History Page
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/providers"
		When I follow "history"
		Then I should see "Trip History"
			
	Scenario: Provider Approved Page
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/providers"
		When I follow "approve"
		Then I should see "Approved"
	
	Scenario: Provider Decline Page
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/providers"
		When I follow "decline"
		Then I should see "Pending"

	Scenario: Currently Providing Provider Page
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/providers"
		When I follow "currently"
		Then I should see "Providers | Currently Providing"

	Scenario: Currently Providing Provider Page
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/provider/current"
		When I follow "providers"
		Then I should see "Providers"

	Scenario: Sort Acsending Provider Page by Provider ID
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/providers"
		When I select "provid" from "sortdrop"
		When I select "asc" from "sortdroporder"
		And I press "btnsort"
		Then I should see "Providers | Sorted by Providers ID in asc"

	Scenario: Sort Acsending Provider Page by Provider Name
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/providers"
		When I select "pvname" from "sortdrop"
		When I select "asc" from "sortdroporder"
		And I press "btnsort"
		Then I should see "Providers | Sorted by Providers Name in asc"

	Scenario: Sort Acsending Provider Page by Provider Email
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/providers"
		When I select "pvemail" from "sortdrop"
		When I select "asc" from "sortdroporder"
		And I press "btnsort"
		Then I should see "Providers | Sorted by Providers Email in asc"

	Scenario: Sort Acsending Provider Page by Provider Address
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/providers"
		When I select "pvaddress" from "sortdrop"
		When I select "asc" from "sortdroporder"
		And I press "btnsort"
		Then I should see "Providers | Sorted by Providers Address in asc"		

	Scenario: Sort Decsending Provider Page by Provider ID
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/providers"
		When I select "provid" from "sortdrop"
		When I select "desc" from "sortdroporder"
		And I press "btnsort"
		Then I should see "Providers | Sorted by Providers ID in desc"

	Scenario: Sort Decsending Provider Page by Provider Name
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/providers"
		When I select "pvname" from "sortdrop"
		When I select "desc" from "sortdroporder"
		And I press "btnsort"
		Then I should see "Providers | Sorted by Providers Name in desc"

	Scenario: Sort Decsending Provider Page by Provider Email
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/providers"
		When I select "pvemail" from "sortdrop"
		When I select "desc" from "sortdroporder"
		And I press "btnsort"
		Then I should see "Providers | Sorted by Providers Email in desc"

	Scenario: Sort Decsending Provider Page by Provider Address
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/providers"
		When I select "pvaddress" from "sortdrop"
		When I select "desc" from "sortdroporder"
		And I press "btnsort"
		Then I should see "Providers | Sorted by Providers Address in desc"		

	Scenario: Search Provider Page by Provider ID
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/providers"
		When I select "provid" from "searchdrop"
		When I fill in "insearch" with "1"
		And I press "btnsearch"
		Then I should see "Providers | Search Result"		

	Scenario: Search Provider Page by Provider Name
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/providers"
		When I select "pvname" from "searchdrop"
		When I fill in "insearch" with "Shan Wilson"
		And I press "btnsearch"
		Then I should see "Providers | Search Result"		

	Scenario: Search Provider Page by Provider Email
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/providers"
		When I select "pvemail" from "searchdrop"
		When I fill in "insearch" with "Shan Wilson"
		And I press "btnsearch"
		Then I should see "Providers | Search Result"		

	Scenario: Search Provider Page by Provider Email
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/providers"
		When I select "bio" from "searchdrop"
		When I fill in "insearch" with "Shan Wilson"
		And I press "btnsearch"
		Then I should see "Providers | Search Result"		



	Scenario: Request Page View Map
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/requests"
		When I follow "map"
		Then I should see "Maps"


	Scenario: Sort Acsending Request Page by Request ID
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/requests"
		When I select "reqid" from "sortdrop"
		When I select "asc" from "sortdroporder"
		And I press "btnsort"
		Then I should see "Requests | Sorted by Request ID in asc"

	Scenario: Sort Acsending Request Page by Owner Name
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/requests"
		When I select "owner" from "sortdrop"
		When I select "asc" from "sortdroporder"
		And I press "btnsort"
		Then I should see "Requests | Sorted by Owner Name in asc"

	Scenario: Sort Acsending Request Page by Provider Name
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/requests"
		When I select "walker" from "sortdrop"
		When I select "asc" from "sortdroporder"
		And I press "btnsort"
		Then I should see "Requests | Sorted by Provider Name in asc"


	Scenario: Sort Decsending Request Page by Request ID
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/requests"
		When I select "reqid" from "sortdrop"
		When I select "desc" from "sortdroporder"
		And I press "btnsort"
		Then I should see "Requests | Sorted by Request ID in desc"

	Scenario: Sort Decsending Requests Page by Owner Name
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/requests"
		When I select "owner" from "sortdrop"
		When I select "desc" from "sortdroporder"
		And I press "btnsort"
		Then I should see "Requests | Sorted by Owner Name in desc"

	Scenario: Sort Decsending Request Page by Provider Name
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/requests"
		When I select "walker" from "sortdrop"
		When I select "desc" from "sortdroporder"
		And I press "btnsort"
		Then I should see "Requests | Sorted by Provider Name in desc"

	Scenario: Search Request Page by Request ID
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/requests"
		When I select "reqid" from "searchdrop"
		When I fill in "insearch" with "1"
		And I press "btnsearch"
		Then I should see "Requests | Search Result"		

	Scenario: Search Requests Page by Owner Name
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/requests"
		When I select "owner" from "searchdrop"
		When I fill in "insearch" with "Shan Wilson"
		And I press "btnsearch"
		Then I should see "Requests | Search Result"		

	Scenario: Search Requests Page by Provider Name
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/requests"
		When I select "walker" from "searchdrop"
		When I fill in "insearch" with "Lazar Savarinathan"
		And I press "btnsearch"
		Then I should see "Requests | Search Result"		







	Scenario: Owner Edit Details Page
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/users"
		When I follow "edit"
		Then I should see "Edit User"


	Scenario: Owner Edit Details Page
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/user/edit/1"
		When I fill in "first_name" with "Lazar"
		When I fill in "last_name" with "Savarinadin"
		When I fill in "email" with "lazar8019@gmail.com"
		When I fill in "phone" with "7358697562"
		When I fill in "address" with "Sivaji Nagar, Bangalore"
		When I fill in "state" with "Karnataka"
		When I fill in "state" with "550066"
		And I press "edit"
		Then I should see "Edit User"

	Scenario: Provider History Page
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/users"
		When I follow "history"
		Then I should see "Trip History"
			
	Scenario: Coupon Statistics Page
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/users"
		When I follow "coupon"
		Then I should see "Owner | Coupon Statistics"
	
	Scenario: Sort Acsending Owners Page by Owner ID
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/users"
		When I select "userid" from "sortdrop"
		When I select "asc" from "sortdroporder"
		And I press "btnsort"
		Then I should see "Owners | Sorted by Owner ID in asc"

	Scenario: Sort Acsending Owner Page by Owner Name
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/users"
		When I select "username" from "sortdrop"
		When I select "asc" from "sortdroporder"
		And I press "btnsort"
		Then I should see "Owners | Sorted by Owner Name in asc"

	Scenario: Sort Acsending owners Page by owner Email
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/users"
		When I select "useremail" from "sortdrop"
		When I select "asc" from "sortdroporder"
		And I press "btnsort"
		Then I should see "Owners | Sorted by Owner Email in asc"

Scenario: Sort Decsending Owners Page by Owner ID
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/users"
		When I select "userid" from "sortdrop"
		When I select "desc" from "sortdroporder"
		And I press "btnsort"
		Then I should see "Owners | Sorted by Owner ID in desc"

	Scenario: Sort Decsending Owner Page by Owner Name
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/users"
		When I select "username" from "sortdrop"
		When I select "desc" from "sortdroporder"
		And I press "btnsort"
		Then I should see "Owners | Sorted by Owner Name in desc"

	Scenario: Sort Decsending owners Page by owner Email
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/users"
		When I select "useremail" from "sortdrop"
		When I select "desc" from "sortdroporder"
		And I press "btnsort"
		Then I should see "Owners | Sorted by Owner Email in desc"

	Scenario: Search Owner Page by Owner ID
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/users"
		When I select "userid" from "searchdrop"
		When I fill in "insearch" with "1"
		And I press "btnsearch"
		Then I should see "Owners | Search Result"		

	Scenario: Search owners Page by Owner Name
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/users"
		When I select "username" from "searchdrop"
		When I fill in "insearch" with "Lazar Savarinathan"
		And I press "btnsearch"
		Then I should see "Owners | Search Result"		

	Scenario: Search Owners Page by Owner Email
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/users"
		When I select "useremail" from "searchdrop"
		When I fill in "insearch" with "lazarsavarinathan@gmail.com"
		And I press "btnsearch"
		Then I should see "Owners | Search Result"		

	Scenario: Search Owners Page by Owner Address
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/users"
		When I select "useraddress" from "searchdrop"
		When I fill in "insearch" with "Sivaji Nagar"
		And I press "btnsearch"
		Then I should see "Owners | Search Result"	

 	Scenario: Search Reviews Page by Owner Name
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/reviews"
		When I select "owner" from "searchdrop"
		When I fill in "insearch" with "Lazar Savarinathan"
		And I press "btnsearch"
		Then I should see "Reviews | Search Result"		

	Scenario: Search Reviews Page by provider Name
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/reviews"
		When I select "walker" from "searchdrop"
		When I fill in "insearch" with "Shan Wilson"
		And I press "btnsearch"
		Then I should see "Reviews | Search Result"			

	Scenario: Theme Settings Page
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/settings"
		When I attach the file "/uploads/" to "logo"
		When I attach the file "/uploads/" to "icon"
		And I press "theme"
		Then I should see "Theme Settings"


	Scenario: Information Page
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/informations"
		Then I should see "Title"

	Scenario: Add Information Page
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/informations"
		When I follow "addinfo"
		Then I should see "Icon File"

	Scenario: Add Information Page
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/information/edit/0"
		When I fill in "title" with "booking"
		When I attach the file "/uploads/" to "icon"
		And I press "add_info"
		Then I should see "Information Page"

	Scenario: Edit Information Page
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/informations"
		When I follow "edit"
		Then I should see "Information Page"

	Scenario: Edit Information Page
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/informations"
		When I follow "delete"
		Then I should see "Information Page"

	Scenario: Provider Type Page
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/provider-types"
		Then I should see "Provider Types"

	Scenario: Provider Type Page
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/provider-types"
		When I follow "addtype"
		Then I should see "Type name"

	Scenario: Add Provider Type Page
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/provider-type/edit/0"
		When I fill in "name" with "SUV"
		When I attach the file "/uploads/" to "icon"
		And I press "add"
		Then I should see "Provider Types"

	Scenario: Sort Acsending Provider Type Page by Provider Type ID
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/provider-types"
		When I select "provid" from "sortdrop"
		When I select "asc" from "sortdroporder"
		And I press "btnsort"
		Then I should see "Provider Types | Sorted by Providers Type ID in asc"

	Scenario: Sort Acsending Provider Type Page by Provider Type Name
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/provider-types"
		When I select "pvname" from "sortdrop"
		When I select "asc" from "sortdroporder"
		And I press "btnsort"
		Then I should see "Provider Types | Sorted by Providers Name in asc"

	
Scenario: Sort Decsending Provider Types Page by Provider Type ID
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/provider-types"
		When I select "provid" from "sortdrop"
		When I select "desc" from "sortdroporder"
		And I press "btnsort"
		Then I should see "Provider Types | Sorted by Providers Type ID in desc"

	Scenario: Sort Decsending Provider Types Page by Provider Type Name
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/provider-types"
		When I select "pvname" from "sortdrop"
		When I select "desc" from "sortdroporder"
		And I press "btnsort"
		Then I should see "Provider Types | Sorted by Providers Name in desc"

	
	Scenario: Search Provider Types Page by Provider Type ID
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/provider-types"
		When I select "provid" from "searchdrop"
		When I fill in "insearch" with "1"
		And I press "btnsearch"
		Then I should see "Provider Types"		

	Scenario: Search Provider Types Page by Provider Name
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/provider-types"
		When I select "provname" from "searchdrop"
		When I fill in "insearch" with "1"
		And I press "btnsearch"
		Then I should see "Provider Types"			

	Scenario: Documents Page
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/document-types"
		Then I should see "Document Types"

	Scenario: Add Documents Page
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/document-types"
		When I follow "adddoc"
		Then I should see "Document Name"

	Scenario: Add Documents Page
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/document-type/edit/0"
		When I fill in "name" with "License"
		And I press "doc"
		Then I should see "Document Types"

	Scenario: Edit Information Page
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/document-types"
		When I follow "edit"
		Then I should see "Document Name"

	Scenario: Edit Information Page
		Given I am on "admin/login"
		When I fill in "username" with "lazar@provenlogic.net"
		When I fill in "password" with "12345"
		And I press "submit1"
		And I am on "admin/document-types"
		When I follow "delete"
		Then I should see "Document Types"

