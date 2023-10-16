# Auth API Microservice

The Auth API Microservice is part of a larger microservices-based application. It is responsible for user authentication and token management. This service provides endpoints for logging in, logging out, and validating JWT tokens.

## Technologies Used:
- Node.js
- TypeScript
- Express.js
- Axios
- JSON Web Tokens (JWT)

## Microservice Responsibilities:
1. **Token Generation:** Generates a JWT token upon successful login.
2. **Token Validation:** Validates a given JWT token to ensure it's valid and not blacklisted.
3. **Token Blacklisting:** Blacklists a token upon logout to ensure it can't be used again.

## Directory Structure:
- `src/`
    - `config/` - Contains configuration files like `config.ts` which loads environment variables.
    - `controllers/` - Contains the `AuthController` which handles incoming HTTP requests.
    - `interfaces/` - Contains the `AuthServiceInterface` which defines the contract for the authentication service.
    - `middleware/` - Contains `authMiddleware` which validates JWT tokens on protected routes.
    - `routes/` - Contains `authRoutes` which defines the HTTP routes for the authentication endpoints.
    - `services/` - Contains the `AuthService` which implements the logic for login, logout, and token validation.
    - `utils/` - Contains utility functions like `jwtUtils` for JWT token generation and verification.
    - `index.ts` - The entry point to the Auth API microservice.

## Setup and Running the Microservice:
1. **Install Dependencies:**
    ```bash
    npm install
    ```

2. **Set Environment Variables:**
    - Rename `.env.example` to `.env`.

3. **Start the Microservice:**
    ```bash
    docker-compose up --build

## API Endpoints:
- **Login:** `POST /auth/v1/login`
    - Request Body: `{ "email": "user@example.com", "password": "password" }`
    - Response: JWT Token in the Authorization header.

- **Logout:** `POST /auth/v1/logout`
    - Headers: `Authorization: Bearer <token>`

- **Validate Token:** `POST /auth/v1/validate-token`
    - Headers: `Authorization: Bearer <token>`
    - Response: `{ "valid": true }` or `{ "valid": false }`

## Testing:
To test the endpoints, you can use tools like Postman or CURL. Ensure you replace `<token>` with the actual token received from the login endpoint.
or use npm jest
    ```bash
    npm run test
    ```

---

This README provides an overview of your Auth API microservice, explains the directory structure, and describes how to set up, run, and test the microservice. Adjustments might be needed to fit the exact nature of your project or environment.