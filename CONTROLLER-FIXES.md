# BookController Fixes

This document outlines the fixes made to the `BookController.php` file to address issues with relationship methods and return types.

## Issues Fixed

1. **Undefined Method 'booksCreated()'**

    - Problem: The `booksCreated()` method was not being recognized on the User model
    - Fix: Modified the `store()` method to use `Book::create()` directly with the user_id set in the data array

2. **Undefined Method 'booksBorrowed()'**

    - Problem: The `booksBorrowed()` method was not being recognized on the User model
    - Fix: Replaced all `$user->booksBorrowed()` method calls with direct DB queries to the `book_user` pivot table

3. **Undefined Method 'getExpiryDate()'**

    - Problem: The `getExpiryDate()` method was not being recognized on the User model
    - Fix: Implemented the expiry date calculation directly in the controller methods by:
        - Retrieving the borrow record from the `book_user` table
        - Parsing the `created_at` date and adding 7 days to it

4. **Return Type Corrections**
    - Problem: Some methods had incorrect return type declarations
    - Fix: Updated the `show()` method docblock to correctly specify `\Illuminate\Contracts\View\View` as the return type

## Implementation Details

### Store Method

-   Now uses `Book::create()` instead of `$user->booksCreated()->create()`
-   Sets the user_id directly in the data array

### Borrow Method

-   Uses DB queries to check if a book is already borrowed
-   Uses DB queries to create the book_user relationship

### Return Method

-   Gets the borrow record directly from the database
-   Calculates the expiry date based on the record's created_at + 7 days
-   Uses DB queries to remove the book_user relationship

### Extend Method

-   Gets the borrow record directly from the database
-   Calculates the expiry date based on the record's created_at + 7 days
-   Updates the borrow record with a new date using DB queries
-   Properly handles late fees and credit deductions

## Additional Improvements

1. Added proper error handling when a book is not found or not borrowed by the user
2. Improved request validation and error feedback
3. Added clear and consistent messaging for user actions
4. Optimized database queries for better performance
5. Updated imports and added the necessary class imports
