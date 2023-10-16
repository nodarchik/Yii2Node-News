import axios from 'axios';
import { config } from '../config/config';
import { AuthServiceInterface } from '../interfaces/authServiceInterface';
import { generateToken, verifyToken } from '../utils/jwtUtils';


export class AuthService implements AuthServiceInterface {
    static blacklistedTokens: Record<string, boolean> = {};

    async login(email: string, password: string): Promise<string> {
        try {
            const { data } = await axios.post(`${config.STORAGE_API_URL}/users/login`, { email, password });
            return generateToken(data._id);
        } catch (error) {
            throw new Error('Failed to login');
        }
    }

    logout(token: string): void {
        AuthService.blacklistedTokens[token] = true;
    }

    validateToken(token: string): boolean {
        if (AuthService.blacklistedTokens[token]) {
            return false;
        }
        return verifyToken(token);
    }
}
