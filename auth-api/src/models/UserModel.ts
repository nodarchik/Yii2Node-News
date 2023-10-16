export interface LoginRequest {
    username: string;
    password: string;
}

export interface UserResponse {
    _id: string;
    username: string;
}