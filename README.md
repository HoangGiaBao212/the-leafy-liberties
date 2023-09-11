# The Leafy Liberties

The Leafy Liberties is a PHP-based bookstore project developed using the MVC (Model-View-Controller) architecture, MySQL as the database, and Tailwind CSS for styling.

## How to Run

1. Copy the `.env.example` file and rename it to `.env`.
2. Open the `.env` file and configure the necessary environment variables according to your setup (e.g., database credentials).
3. Open your terminal or command prompt and navigate to the project directory.
4. Navigate to the `docker` directory within the project.
5. Run the following command to start the Docker containers:

```bash
   docker-compose up
```

This command will set up the required containers for the project, including the PHP server and the MySQL database.

6. Once the containers are up and running, you can access the project in your web browser using the provided URL or localhost address.

## Project Structure

The project follows a typical MVC structure:

- **`app/`**: Contains the core application files.
  - **`Controllers/`**: Contains the controller files responsible for handling the logic and interaction with the views.
  - **`Models/`**: Contains the model files responsible for interacting with the database.
  - **`Views/`**: Contains the view files responsible for rendering the user interface.

- **`resources/`**: Contains both non-public and public resources.
- **`docker/`**: Contains the Docker configuration files.
- **`core/`**: Contains the core files of the MVC system.
- **`routes/`**: Contains the router files.
- **`utils/`**: Contains utility classes.

## Contributing
If you'd like to contribute to the project, please follow these steps:

1. Fork the repository.
2. Create a new branch for your feature or bug fix.
3. Make your changes and commit them.
4. Push your changes to your forked repository.
5. Open a pull request to the main repository.
