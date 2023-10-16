import mongoose from 'mongoose';
import { MongoMemoryServer } from 'mongodb-memory-server';
import { UserService } from '../services/userService';
import { User } from '../models/User';
import { Types } from 'mongoose';

describe('UserService', () => {
    let mongoServer: MongoMemoryServer;

    beforeAll(async () => {
        mongoServer = await MongoMemoryServer.create();
        const mongoUri = mongoServer.getUri();
        await mongoose.connect(mongoUri, {
            useNewUrlParser: true,
            useUnifiedTopology: true,
            useFindAndModify: false,
            useCreateIndex: true
        } as mongoose.ConnectOptions);  // Cast as ConnectOptions to satisfy TypeScript
    }, 10000);  // 10 seconds timeout

    afterEach(async () => {
        await User.deleteMany({});
    }, 10000);  // 10 seconds timeout

    afterAll(async () => {
        await mongoose.connection.close();
        await mongoServer.stop();
    }, 10000);  // 10 seconds timeout

    it('should register a user and return a token', async () => {
        const result = await UserService.register('testuser', 'password123', 'password123');
        expect(result.user.username).toBe('testuser');
        expect(result.token).toMatch(/eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9\./);  // Matches JWT format
    });

    it('should throw an error if passwords do not match', async () => {
        await expect(UserService.register('testuser', 'password123', 'password456'))
            .rejects
            .toThrow('Passwords do not match');
    });

    it('should get all users', async () => {
        await UserService.register('testuser1', 'password123', 'password123');
        await UserService.register('testuser2', 'password123', 'password123');
        const users = await UserService.getAllUsers();
        expect(users.length).toBe(2);
    });
    it('should update a user', async () => {
        const { user: registeredUser } = await UserService.register('testuser', 'password123', 'password123');
        const { user: updatedUser } = await UserService.updateUser(registeredUser._id.toString(), 'updateduser', 'updatedpassword');
        expect(updatedUser.username).toBe('updateduser');
    });

    it('should throw an error if user not found on update', async () => {
        await expect(UserService.updateUser(new Types.ObjectId().toString(), 'updateduser', 'updatedpassword'))
            .rejects
            .toThrow('User not found');
    });

    it('should delete a user', async () => {
        const { user: registeredUser } = await UserService.register('testuser', 'password123', 'password123');
        const { user: deletedUser } = await UserService.deleteUser(registeredUser._id.toString());
        expect(deletedUser._id.toString()).toEqual(registeredUser._id.toString());
    });

    it('should throw an error if user not found on delete', async () => {
        await expect(UserService.deleteUser(new Types.ObjectId().toString()))
            .rejects
            .toThrow('User not found');
    });

});it('should throw an error if username, password, or confirmPassword is missing', async () => {
    await expect(UserService.register('', 'password123', 'password123'))
        .rejects
        .toThrow('Username, password, and confirmPassword are required');

    await expect(UserService.register('testuser', '', 'password123'))
        .rejects
        .toThrow('Username, password, and confirmPassword are required');

    await expect(UserService.register('testuser', 'password123', ''))
        .rejects
        .toThrow('Username, password, and confirmPassword are required');
});

it('should throw an error if username is already taken', async () => {
    await UserService.register('testuser', 'password123', 'password123');
    await expect(UserService.register('testuser', 'password123', 'password123'))
        .rejects
        .toThrow('Username is already taken');
});

it('should update only the username', async () => {
    const { user: registeredUser } = await UserService.register('testuser', 'password123', 'password123');
    const { user: updatedUser } = await UserService.updateUser(registeredUser._id.toString(), 'updateduser', '');
    expect(updatedUser.username).toBe('updateduser');
    expect(updatedUser.password).toBeUndefined();
});

it('should update only the password', async () => {
    const { user: registeredUser } = await UserService.register('testuser', 'password123', 'password123');
    const { user: updatedUser } = await UserService.updateUser(registeredUser._id.toString(), '', 'updatedpassword');
    expect(updatedUser.username).toBe('testuser');
    expect(updatedUser.password).toBeDefined();
});

it('should throw an error if both username and password are missing on update', async () => {
    const { user: registeredUser } = await UserService.register('testuser', 'password123', 'password123');
    await expect(UserService.updateUser(registeredUser._id.toString(), '', ''))
        .rejects
        .toThrow('At least one field (username or password) is required to update');
});

it('should delete a user', async () => {
    const { user: registeredUser } = await UserService.register('testuser', 'password123', 'password123');
    const { user: deletedUser } = await UserService.deleteUser(registeredUser._id.toString());
    expect(deletedUser._id.toString()).toEqual(registeredUser._id.toString());
});

it('should throw an error if user not found on delete', async () => {
    await expect(UserService.deleteUser(new Types.ObjectId().toString()))
        .rejects
        .toThrow('User not found');
});