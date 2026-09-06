FROM node:24-slim

WORKDIR /app

RUN npm install tailwindcss @tailwindcss/cli

COPY . .

CMD ["npx", "@tailwindcss/cli", "-i", "./css/input.css", "-o", "./css/dist.css", "--watch", "--poll"]
