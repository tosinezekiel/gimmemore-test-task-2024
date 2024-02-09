# Book Club Service

## Overview

This service is akin to a book club platform, enabling authenticated users to add books they are reading, track their reading progress with updates, and share their thoughts through reviews upon completion. The user's profile showcases current progress and a history of read books, enhancing the reading experience with social sharing capabilities.

## Preview
[Preview App](https://recordit.co/ZGYOtkvtRo)


### Features

- User registration and login functionality.
- Ability for registered users to add books to their library, including details like title, genre, and page count.
- Users can log their reading progress with updates such as "Today I read N pages of X".
- Upon finishing a book, users can rate the book, leave a review, and share their reviews on social media.

### Technology Stack

- Backend: Symfony 6
- Frontend: Vue 3 with TypeScript
- Styling: Tailwind CSS
- Database: MySQL
- PHP 8

## Setup Instructions

1. **Clone the Repository**

    ```bash
    git clone <repository-url>
    ```

2. **Update Environment Configuration**

    Navigate to the project directory and update the `.env` file with your database credentials.

    ```plaintext
    DATABASE_URL="mysql://username:password@localhost:3306/your_database_name"
    ```

    Run migration
    ```
    php bin/console doctrine:migrations:migrate
    ```

    Run seeder
    ```
    php bin/console doctrine:fixtures:load
    ```

    Test
    ```
    ./vendor/bin/phpunit
    ```

3. **Install Dependencies**

    Inside the project directory, install PHP and JavaScript dependencies.

    ```bash
    composer install
    npm install
    ```

4. **Build the Frontend**

    ```bash
    npm run watch
    ```

5. **Start the Symfony Server**

    ```bash
    symfony server:start
    ```

6. **Access the Application**

    Open your web browser and navigate to the local server address provided by the Symfony server command.

## API Endpoints

- **Auth**
  - Login: `POST /api/login` with payload `-d email, password`
  - Register: `POST /api/register` with payload `-d email, password, last_name, first_name`

- **User Profile and Dashboard**
  - Profile: `GET /api/auth/profile`
  - Dashboard: `GET /api/auth/dashboard`

- **Books**
  - Add Book: `POST /api/auth/books` with payload `-d author, title, genre, page_count`
  - List Books: `GET /api/auth/books`
  - Get Book Details: `GET /api/auth/books/:id`
  - Update Reading Progress: `PATCH /api/auth/books/:id/read` with payload `-d page_read`
  - Leave a Review: `POST /api/auth/books/:id/review` with payload `-d comment, rating`


