# Storage API Microservice

The Storage API Microservice is an integral part of a larger microservices-based application, focusing on user and news data management. This service facilitates CRUD operations for users and news articles, ensuring data integrity and availability.

## Technologies Used:
- Node.js
- TypeScript
- Express.js
- MongoDB (Mongoose)
- bcrypt.js
- JSON Web Tokens (JWT)

## Microservice Responsibilities:
1. **User Management:** Registering, updating, and deleting user accounts.
2. **News Management:** Creating, retrieving, updating, and deleting news articles.
3. **Authentication:** Generating and validating JWT tokens for secure interactions.

## Directory Structure:
- `src/`
    - `config/` - Contains configuration files such as `db.ts` for database connection and `config.ts` for loading environment variables.
    - `controllers/` - Contains `UserController` and `NewsController` for handling HTTP requests.
    - `models/` - Contains Mongoose models `User` and `News` for database interaction.
    - `routes/` - Contains `v1/userRoutes` and `v1/newsRoutes` for defining HTTP routes.
    - `middleware/` - Contains `errorHandler` middleware for global error handling.
    - `index.ts` - Entry point to the Storage API microservice.

## Setup and Running the Microservice:
1. **Install Dependencies:**
    ```bash
    npm install
    ```

2. **Set Environment Variables:**
    - Rename `.env.example` to `.env` and set the necessary values.

3. **Start the Microservice:**
    ```bash
    npm start
    ```

## API Endpoints:
- **Users:**
    - Register: `POST /api/v1/users/register`
    - Get All Users: `GET /api/v1/users`
    - Update User: `PUT /api/v1/users/:id`
    - Delete User: `DELETE /api/v1/users/:id`

- **News:**
    - Create News: `POST /api/v1/news`
    - Get All News: `GET /api/v1/news`
    - Get News by ID: `GET /api/v1/news/:id`
    - Update News: `PUT /api/v1/news/:id`
    - Delete News: `DELETE /api/v1/news/:id`

## Testing:
Use Postman or CURL to test the endpoints. Ensure to replace `<id>` with the actual ID of the user or news article.

or use Jest for automated testing:
```bash
npm run test
```

---

This README provides an overview of your Storage API microservice, explains the directory structure, and describes how to set up, run, and test the microservice. Adjustments might be needed to fit the exact nature of your project or environment.