export interface AuthServiceInterface {
    login(email: string, password: string): Promise<string>;
    logout(token: string): void;
    validateToken(token: string): boolean;
}
