# Use Node 18 image
FROM node:18

# Set work directory
WORKDIR /usr/src

# Copy package.json and package-lock.json
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy all source files
COPY . .

# Build TypeScript
RUN npm run build

# Expose port
EXPOSE 3002

# Command to run the application
CMD ["npm", "start"]