import { AuthService } from '../services/authService';
import axios from 'axios';
import { generateToken, verifyToken } from '../utils/jwtUtils';

// Mocking axios post method
jest.mock('axios');

describe('AuthService', () => {
    let authService: AuthService;

    beforeEach(() => {
        authService = new AuthService();  // instantiate a new AuthService before each test
    });

    afterEach(() => {
        jest.resetAllMocks();  // reset all mocks after each test
    });

    test('login should return a token on successful login', async () => {
        const mockUserId = 'userId123';
        const mockEmail = 'test@example.com';
        const mockPassword = 'password123';

        // Mock axios post response
        (axios.post as jest.Mock).mockResolvedValueOnce({ data: { _id: mockUserId } });

        const token = await authService.login(mockEmail, mockPassword);

        expect(token).toBeDefined();
        expect(verifyToken(token)).toBe(true);  // assert that the token is valid
    });

    test('login should throw an error on failed login', async () => {
        const mockEmail = 'test@example.com';
        const mockPassword = 'password123';

        // Mock axios post to throw an error
        (axios.post as jest.Mock).mockRejectedValueOnce(new Error('Failed to login'));

        await expect(authService.login(mockEmail, mockPassword)).rejects.toThrow('Failed to login');
    });

    test('logout should blacklist token', () => {
        const token = generateToken('userId123');
        authService.logout(token);
        expect(AuthService.blacklistedTokens[token]).toBe(true);
    });

    test('validateToken should return true for a valid non-blacklisted token', async () => {  // marked as async
        const token = generateToken('userId123');
        const isValid = authService.validateToken(token);
        expect(isValid).toBe(true);
    });



    test('validateToken should return false for a blacklisted token', () => {
        const token = generateToken('userId123');
        authService.logout(token);  // blacklisting the token
        const isValid = authService.validateToken(token);
        expect(isValid).toBe(false);
    });

    test('validateToken should return false for an invalid token', () => {
        const invalidToken = 'invalidToken';
        const isValid = authService.validateToken(invalidToken);
        expect(isValid).toBe(false);
    });
});
