import { ObjectId } from 'mongodb';

export interface News {
    _id?: ObjectId;
    title: string;
    content: string;
}
