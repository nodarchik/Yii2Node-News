import mongoose from 'mongoose';
import bcrypt from 'bcryptjs';

// Interface for a User document
export interface IUser extends mongoose.Document {
    username: string;
    password: string;
    comparePassword: (password: string) => Promise<boolean>;
}
// Define the IUserResponse interface
export interface IUserResponse {
    _id: mongoose.Schema.Types.ObjectId;
    username: string;
}
// Schema definition
const userSchema = new mongoose.Schema({
    username: {
        type: String,
        required: true,
        unique: true,
        minlength: [5, 'Username must be at least 5 characters long'],
    },
    password: {
        type: String,
        required: true,
        minlength: [8, 'Password must be at least 8 characters long'],
    },
}, {
    timestamps: true,  // Adds createdAt and updatedAt fields
});

// Indexing the username field for faster lookups
userSchema.index({ username: 1 });

// Pre-save hook to hash the password before saving
userSchema.pre<IUser>('save', async function (next) {
    if (this.isModified('password')) {
        const salt = await bcrypt.genSalt(10);
        this.password = await bcrypt.hash(this.password, salt);
    }
    next();
});

// Method to compare a given password with the stored hash
userSchema.methods.comparePassword = async function(password: string): Promise<boolean> {
    try {
        return await bcrypt.compare(password, this.password);
    } catch (error) {
        throw new Error('Comparison failed');
    }
};

// Exporting the User model
export const User = mongoose.model<IUser>('User', userSchema);
