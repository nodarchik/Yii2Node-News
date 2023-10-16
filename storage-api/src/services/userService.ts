import { IUser, IUserResponse, User } from '../models/User';
import jwt from 'jsonwebtoken';
import bcrypt from 'bcryptjs';
import { config } from '../config/config';

export class UserService {
    static async register(username: string, password: string, confirmPassword: string) {
        if (!username || !password || !confirmPassword) {
            throw new Error('Username, password, and confirmPassword are required');
        }
        if (password !== confirmPassword) {
            throw new Error('Passwords do not match');
        }
        const existingUser = await User.findOne({ username });
        if (existingUser) {
            throw new Error('Username is already taken');
        }

        const hashedPassword = await bcrypt.hash(password, 10);
        const user = new User({ username, password: hashedPassword });
        await user.save();

        const token = jwt.sign({ _id: user._id, username: user.username }, config.jwtSecret, {
            expiresIn: '1h',
        });

        const userResponse: IUserResponse = {
            _id: user._id,
            username: user.username,
        };
        return { user: userResponse, token };
    }

    static async getAllUsers() {
        return User.find().select('-password');
    }

    static async updateUser(id: string, username: string, password: string) {
        if (!username && !password) {
            throw new Error('At least one field (username or password) is required to update');
        }

        const updateData: Partial<IUser> = {};
        if (username) updateData.username = username;
        if (password) {
            updateData.password = await bcrypt.hash(password, 10);
        }

        const updatedUser = await User.findByIdAndUpdate(
            id,
            updateData,
            { new: true, runValidators: true }
        ).select('-password');

        if (!updatedUser) {
            throw new Error('User not found');
        }

        return { user: updatedUser };
    }

    static async deleteUser(id: string) {
        const deletedUser = await User.findByIdAndDelete(id);
        if (!deletedUser) {
            throw new Error('User not found');
        }
        return { user: deletedUser };
    }
}
