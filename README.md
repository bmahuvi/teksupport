# TekTek Support Portal (TekSupport)

## Introduction

**TekSupport** is a customer support portal built for **TekTek Solutions** clients.  
It provides a centralized platform for submitting, tracking, and resolving support requests efficiently.

---
## Features

-   Client account registration and authentication
-   Support ticket creation and management
-   Ticket status tracking (Open, In Progress, Resolved, Closed)
-   File and attachment uploads
-   Admin and support agent dashboard
-   Internal notes and client responses
-   Email notifications
-   Role-based access
-   Activity logs and audit trail

## Tech Stack
-   **Backend:** Laravel & Filament
-   **Frontend:** Blade / Livewire
-   **Database:** MySQL
-   **Authentication:** Laravel Breeze / Sanctum
-   **Styling:** Tailwind CSS
-   **Server:** Apache


## Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/bmahuvi/teksupport.git
    ```
2. Navigate to the project directory:
    ```bash
    cd teksupport
    ```
3. Install dependencies:
    ```bash
    composer install
    npm install && npm run build
    ```
4. Copy the `.env.example` file to `.env` and configure your environment variables:
    ```bash
    cp .env.example .env
    ```
5. Generate an application key:
    ```bash
    php artisan key:generate
    ```
6. Run the migrations and seeders:
    ```bash
    php artisan migrate --seed
    ```

## Usage

Start the development server:

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser to access the application.

## Contributing

Feel free to submit issues or pull requests. For major changes, please open an issue first to discuss what you would like to change.

## License

This project is licensed under the MIT License.
