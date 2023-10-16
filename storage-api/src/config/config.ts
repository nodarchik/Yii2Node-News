import dotenv from 'dotenv';

dotenv.config();

interface Config {
    mongoURI: string;
    jwtSecret: string;
}

const config: Config = {
    mongoURI: process.env.MONGO_URI || '',
    jwtSecret: process.env.JWT_SECRET || ''
};

if (!config.mongoURI) {
    throw new Error('MONGO_URI must be specified in the environment variables.');
}

if (!config.jwtSecret) {
    throw new Error('JWT_SECRET must be specified in the environment variables.');
}

export { config };
