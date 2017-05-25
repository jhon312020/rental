<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Whole app Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    | ex: trans('message.')
    */
    /* Page name language */
    'incomes_page' => 'Incomes',
    'income_types' => 'Income types',
    'expense_types' => 'Expense types',
    'rooms' => 'Rooms',
    'guests' => 'Guests',
    'expenses_page' => 'Expenses',
    'home_page' => 'Home',
    'users' => 'Users',
    'settings_page' => 'Settings',
    'rents_page' => 'Rents',


    /* page title start */

        /* income page */
        'income_lists' => 'Income lists',
        'income_new' => 'Income new',
        'income_update' => 'Income update',
        'income_create' => 'Income create',
        /* income page end */

         /* income type page */
        'income_type_lists' => 'Income type lists',
        'income_type_new' => 'Income type new',
        'income_type_update' => 'Income type update',
        'income_type_create' => 'Income type create',
        /* income type page end */

        /* expense type page */
        'expense_type_lists' => 'Expense type lists',
        'expense_type_new' => 'Expense type new',
        'expense_type_update' => 'Expense type update',
        'expense_type_create' => 'Expense type create',
        /* expense type page end */

        /* rooms page */
        'room_lists' => 'Room lists',
        'room_new' => 'Room new',
        'room_update' => 'Room update',
        'room_create' => 'Room create',
        /* rooms page end */

        /* guests page */
        'guests_lists' => 'Guest lists',
        'guests_new' => 'Guest new',
        'guests_update' => 'Guest update',
        'guests_create' => 'Guest create',
        /* guests page end */

        /* Expenses page */
        'expense_lists' => 'Expense lists',
        'expense_new' => 'Expense new',
        'expense_update' => 'Expense update',
        'expense_create' => 'Expense create',
        /* Expenses page end */

        /* User page */
        'user_lists' => 'User lists',
        'user_new' => 'User new',
        'user_update' => 'User update',
        'user_create' => 'User create',
        'user_profile_update' => 'User profile update',
        /* User page end */

        /* Rent page */
        'rent_lists' => 'Rent lists',
        'rent_new' => 'Rent new',
        'rent_update' => 'Rent update',
        'rent_create' => 'Rent create',
        'rent_details' => 'Rent details',
        'guest_details' => 'Guest details',
        'add_another_guest' => 'Add another guest',
        'rent_monthly' => 'Monthwise rent',
        'rent_monthly_report' => 'Monthwise rent report',
        'electric_bill_details_month' => 'Monthwise electric bill details',
        'room_rent_details_month' => 'Monthwise room rent details',
        'no_of_vacancy' => 'No of vacancy',
        /* Rent page end */

        /* Setting page */
        'setting_update' => 'Setting update',
        /* Setting page end */

        /* Report page */
        'rent_report' => 'Rent report',
        'room_report' => 'Room report',
        'income_report' => 'Income report',
        'monthly_income_report' => 'Monthly income report',
        'yearly_income_report' => 'Yearly income report',
        'income_report_between_date' => 'Income report between date',
        'expense_report' => 'Expense report',
        'monthly_expense_report' => 'Monthly expense report',
        'yearly_expense_report' => 'Yearly expense report',
        'expense_report_between_date' => 'Expense report between date',
        'electricity_bill_report' => 'Electricity bill report',
        'yearly_electricity_bill_report' => 'Yearly electricity bill report',
        'electricity_bill_report_between_months' => 'Electricity bill report between months',
        'billing_month_year' => 'Billing month year',
        /* Report page end */

        /*Dashboars start*/
        'pending_rent_count' => 'Pending rent count',
        'pending_rent_amount' => 'Pending rent amount',
        'total_expenses' => 'Total expenses',
        'total_incomes' => 'Total incomes',
        'last_30_days_income_expense' => 'Last 30 days income and expense',
        'paid_guests_list' => 'Paid guests list',
        'unpaid_guests_list' => 'Unpaid guests list',
        /*Dashboard end*/

    /* page title end */


    /* Success and error messages start */
    
        /* income page start */
        'income_remove_success' => 'Income successfully removed!',
        'income_update_success' => 'Income updated successfully!',
        'income_create_success' => 'New income created successfully!',
        /* income page end */    

        /* income type start */
        'income_type_remove_success' => 'Income type successfully removed!',
        'income_type_update_success' => 'Income type updated successfully!',
        'income_type_create_success' => 'New income type created successfully!',
        /* income type end */

        /* Expense type start */
        'expense_type_remove_success' => 'Expense type successfully removed!',
        'expense_type_update_success' => 'Expense type updated successfully!',
        'expense_type_create_success' => 'New expense type created successfully!',
        /* Expense type end */

        /* Room start */
        'room_remove_success' => 'Room successfully removed!',
        'room_update_success' => 'Room updated successfully!',
        'room_create_success' => 'New room created successfully!',
        /* Room end */

        /* Gueests start */
        'guests_remove_success' => 'Guest successfully removed!',
        'guests_update_success' => 'Guest updated successfully!',
        'guests_create_success' => 'New guest created successfully!',
        /* Guests end */

        /* Expense start */
        'expense_remove_success' => 'Expense successfully removed!',
        'expense_update_success' => 'Expense updated successfully!',
        'expense_create_success' => 'New expense created successfully!',
        /* Expense end */

        /* User start */
        'user_remove_success' => 'User successfully removed!',
        'user_update_success' => 'User updated successfully!',
        'user_create_success' => 'New user created successfully!',
        'user_update_profile_success' => 'User profile updated successfully!',
        /* User end */

        /* Rent start */
        'rent_remove_success' => 'Rent successfully removed!',
        'rent_update_success' => 'Rent updated successfully!',
        'rent_create_success' => 'New rent created successfully!',
        /* Rent end */

        /* Setting start */
        'setting_update_success' => 'Setting updated successfully!',
        /* Setting end */

    /* Success and error messages end */


    /* label name start */

        /* income label start */
        'income_type' => 'Income type',
        'amount' => 'Amount',
        'notes' => 'Notes',
        'entry_by' => 'Entry by',
        'date_of_income' => 'Date of income',
        'rent_amount_received' => 'Rent amount received',
        /* income label end */

        /* income type label start */
        'type_of_income' => 'Type of income',
        /* income type label end */

        /* Expense type label start */
        'type_of_expense' => 'Type of expense',
        /* Expense type label end */

        /* Room label start */
        'room_name' => 'Room name',
        'room_no' => 'Room no',
        'max_persons_allowed' => 'Maximum person allowed',
        'rent_amount_person' => 'Rent amount per person',
        'total_rent_amount' => 'Total rent amount',
        'no_of_person_stayed' => 'No of person stayed',
        'rent_amount_get' => 'Rent amount get',
        /* Room label end */

        /* Guests label start */
        'name' => 'Name',
        'address' => 'Address',
        'city' => 'City',
        'state' => 'State',
        'country' => 'Country',
        'zip' => 'Zip',
        'email' => 'Email',
        'mobile_no' => 'Mobile no',
        /* Guests label end */

        /* Expense label start */
        'expense_type' => 'Expense type',
        'date_of_expense' => 'Date of expense',
        /* Expense label end */

        /* User label start */
        'username' => 'Username',
        'password' => 'Password',
        /* User label end */

        /* Rent label start */
        'advance' => 'Advance',
        'checkin_date' => 'Checkin date',
        'checkout_date' => 'Checkout date',
        'search_by' => 'Search by',
        'search_value' => 'Search value',
        'guest_search' => 'Guest search',
        /* Rent label end */

        /* Setting label start */
        'title' => 'Title',
        'electricity_bill_units' => 'Electricity bill per units',
        'admin_email' => 'Admin email',
        /* Setting label end */

    /* label name end */


    /* Button text start */

        /* income button label start*/
        'create_income' => 'Create the income!',
        'update_income' => 'Update the income!',
        'new_income' => 'New income',
        /* income button label end*/

        /* income type button label start*/
        'create_income_type' => 'Create the income type!',
        'update_income_type' => 'Update the income type!',
        'new_income_type' => 'New income type',
        /* income button label end*/

        /* expense type button label start*/
        'create_expense_type' => 'Create the expense type!',
        'update_expense_type' => 'Update the expense type!',
        'new_expense_type' => 'New expense type',
        /* expense button label end*/

        /* room button label start*/
        'create_room' => 'Create the room!',
        'update_room' => 'Update the room!',
        'new_room' => 'New room',
        /* room label end*/

        /* guests button label start*/
        'create_guest' => 'Create the guest!',
        'update_guest' => 'Update the guest!',
        'new_guest' => 'New guest',
        /* guests label end*/

        /* expense button label start*/
        'create_expense' => 'Create the expense!',
        'update_expense' => 'Update the expense!',
        'new_expense' => 'New expense',
        /* expense label end*/

        /* user button label start*/
        'create_user' => 'Create the user!',
        'update_user' => 'Update the user!',
        'new_user' => 'New user',
        'update_user_profile' => 'Update user profile!',
        /* user label end*/

        /* Rent button label start*/
        'create_rent' => 'Create the rent!',
        'update_rent' => 'Update the rent!',
        'new_rent' => 'New rent',
        /* Rent label end*/

        /* Setting button label start*/
        'updatesetting' => 'Update the setting!',
        /* Setting label end*/

    /* Button text end */


    /* Coomon buttons start */
    'actions' => 'Actions',

    /* Coomon buttons end */

];
