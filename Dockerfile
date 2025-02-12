# Use the official PHP CLI image
FROM php:8.3-cli

# Install PDO MySQL extension
RUN docker-php-ext-install pdo pdo_mysql

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the application files to the container
COPY ./api /var/www/html

# Expose port 8101 for the PHP built-in server
EXPOSE 8101

# Run the PHP built-in server
CMD ["php", "-S", "0.0.0.0:8101", "-t", "/var/www/html"]