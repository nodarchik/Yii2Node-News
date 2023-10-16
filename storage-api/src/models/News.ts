import mongoose from 'mongoose';

// Interface for a News document
export interface INews extends mongoose.Document {
    title: string;
    content: string;
    createdAt: Date;
    updatedAt: Date;
}

// Schema definition
const newsSchema = new mongoose.Schema(
    {
        title: {
            type: String,
            required: true,
            unique: true,  // Ensures that titles are unique
            minlength: [5, 'Title must be at least 5 characters long'],
            maxlength: [255, 'Title cannot be more than 255 characters long'],
        },
        content: {
            type: String,
            required: true,
            minlength: [10, 'Content must be at least 10 characters long'],
        },
    },
    {
        timestamps: true, // This will add createdAt and updatedAt fields
    }
);

// Indexing the title field for faster lookups
newsSchema.index({ title: 1 });

// Exporting the News model
export const News = mongoose.model<INews>('News', newsSchema);
