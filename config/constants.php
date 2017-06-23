<?php
return array(
    'MENUS' => array(
                        'home' => 'Home',
                        'guests' => 'Guests',
                        'rooms' => 'Rooms',
                        'expense-types' => 'Expense types',
						'income-types' => 'Income types',
						'incomes' => 'Incomes',
						'expenses' => 'Expenses',
                        'rents' => 'Rents',
                        'settings' => 'Settings',
                     ),

    'MENU_LINK' => array(
                        'home' => 'home',
                        'guests' => 'user',
                        'rooms' => 'bed',
                        'expense-types' => 'plus-circle',
                        'income-types' => 'plus',
                        'incomes' => 'rupee',
                        'expenses' => 'eject',
                        'settings' => 'cogs',
                        'rents' => 'home',
    					),
    'SEARCH_KEY' => array(
                        'email',
                        'mobile_no',
                        'city',
                        'state',
                        'name',
                        ),

    /*
        This is the default advance id from the table income types.
        If we clear or change the value then you should update these content too.
    */
    'ADVANCE' => 1,
    'RENT' => 2,
    'SETTLEMENT' => 1,
    'INCOME_EDIT' => ['1'],
    'EXPENSE_EDIT' => ['1'],
    'REPORT_START_DATE' => date('Y-m-d', strtotime("-2 days")),
    'REPORT_END_DATE' => date('Y-m-d')
);