-- Active: 1680850809935@@127.0.0.1@3306@bookstore

DROP DATABASE IF EXISTS bookstore;

CREATE DATABASE bookstore;

USE bookstore;

CREATE TABLE
    categories (
        id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        image varchar(255) DEFAULT NULL,
        name varchar(100) NOT NULL,
        deleted_at datetime DEFAULT NULL
    );

CREATE TABLE
    tags (
        id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        image varchar(255) DEFAULT NULL,
        name varchar(100) NOT NULL,
        deleted_at datetime DEFAULT NULL
    );

CREATE TABLE
    slides (
        id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        image varchar(255) DEFAULT NULL,
        name varchar(100) NOT NULL,
        status tinyint NOT NULL DEFAULT "1",
        deleted_at datetime DEFAULT NULL
    );

CREATE TABLE
    authors (
        id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        image varchar(255) DEFAULT NULL,
        name varchar(100) NOT NULL,
        deleted_at datetime DEFAULT NULL
    );

CREATE TABLE
    publishers (
        id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        image varchar(255) DEFAULT NULL,
        name varchar(100) NOT NULL,
        deleted_at datetime DEFAULT NULL
    );

CREATE TABLE
    products (
        id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        isbn varchar(13) NOT NULL,
        name varchar(100) NOT NULL,
        author_id INT DEFAULT NULL,
        publisher_id INT DEFAULT NULL,
        price decimal(10, 2) NOT NULL,
        description text NOT NULL,
        image varchar(255) NOT NULL,
        quantity int NOT NULL DEFAULT "0",
        deleted_at datetime DEFAULT NULL,
        -- key
        UNIQUE KEY isbn (isbn),
        FOREIGN KEY (author_id) REFERENCES authors (id),
        FOREIGN KEY (publisher_id) REFERENCES publishers (id)
    );

CREATE TABLE
    products_tags (
        id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        product_id int NOT NULL,
        tag_id int NOT NULL,
        -- key
        FOREIGN KEY (product_id) REFERENCES products (id),
        FOREIGN KEY (tag_id) REFERENCES tags (id)
    );

CREATE TABLE
    products_categories (
        id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        product_id int NOT NULL,
        category_id int NOT NULL,
        -- key
        FOREIGN KEY (product_id) REFERENCES products (id),
        FOREIGN KEY (category_id) REFERENCES categories (id)
    );

-- User

CREATE TABLE
    permissions (
        id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        name varchar(50) NOT NULL,
        deleted_at datetime DEFAULT NULL
    );

CREATE TABLE
    roles (
        id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        name varchar(50) NOT NULL,
        deleted_at datetime DEFAULT NULL
    );

CREATE TABLE
    users (
        id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        email varchar(50) NOT NULL,
        name varchar(50) NOT NULL,
        phone varchar(50) DEFAULT NULL,
        password varchar(255) NOT NULL,
        gender tinyint NOT NULL DEFAULT "0",
        image varchar(255) DEFAULT NULL,
        role_id int DEFAULT NULL,
        status tinyint NOT NULL DEFAULT "1",
        address text DEFAULT NULL,
        deleted_at datetime DEFAULT NULL,
        -- key
        UNIQUE KEY email (email),
        FOREIGN KEY (role_id) REFERENCES roles (id)
    );

CREATE TABLE
    roles_permissions (
        id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        permission_id int NOT NULL,
        role_id int NOT NULL,
        status tinyint NOT NULL DEFAULT "1",
        FOREIGN KEY (permission_id) REFERENCES permissions (id),
        FOREIGN KEY (role_id) REFERENCES roles (id)
    );

CREATE TABLE
    users_permissions (
        id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        permission_id int NOT NULL,
        user_id int NOT NULL,
        status tinyint NOT NULL DEFAULT "1",
        FOREIGN KEY (permission_id) REFERENCES permissions (id),
        FOREIGN KEY (user_id) REFERENCES users (id)
    );

-- Wishlist

CREATE TABLE
    wishlists (
        id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        user_id int NOT NULL,
        product_id int NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users (id),
        FOREIGN KEY (product_id) REFERENCES products (id)
    );

-- Cart

CREATE TABLE
    carts (
        id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        user_id int NOT NULL,
        product_id int NOT NULL,
        quantity int NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users (id),
        FOREIGN KEY (product_id) REFERENCES products (id)
    );

-- Order

CREATE TABLE
    coupons (
        id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        code VARCHAR(50) NOT NULL,
        quantity int NOT NULL,
        required int(11) NOT NULL,
        percent int(3) NOT NULL,
        expired date NOT NULL,
        description VARCHAR(255) NOT NULL,
        deleted_at DATETIME DEFAULT NULL
    );

CREATE TABLE
    shipping_methods (
        id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        name text NOT NULL,
        price decimal(10, 2) NOT NULL,
        description text NOT NULL,
        status int NOT NULL DEFAULT "1",
        deleted_at datetime DEFAULT NULL
    );

CREATE TABLE
    orders (
        id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        name varchar(255) NOT NULL,
        email varchar(255) NOT NULL,
        address text NOT NULL,
        phone varchar(255) NOT NULL,
        shipping_method_id int NOT NULL,
        coupon_id int NOT NULL,
        description text DEFAULT NULL,
        payment_method_type varchar(255) NOT NULL,
        card_number varchar(255) DEFAULT NULL,
        expired_date varchar(255) DEFAULT NULL,
        cvv varchar(255) DEFAULT NULL,
        user_id int NOT NULL,
        total_price decimal(10, 2) NOT NULL,
        status tinyint NOT NULL DEFAULT "0",
        create_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        deleted_at datetime DEFAULT NULL,
        FOREIGN KEY (shipping_method_id) REFERENCES shipping_methods (id),
        FOREIGN KEY (coupon_id) REFERENCES coupons (id),
        FOREIGN KEY (user_id) REFERENCES users (id)
    );

CREATE TABLE
    orders_products (
        id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        order_id int NOT NULL,
        product_id int NOT NULL,
        quantity int NOT NULL,
        price decimal(10, 2) NOT NULL,
        FOREIGN KEY (order_id) REFERENCES orders (id),
        FOREIGN KEY (product_id) REFERENCES products (id)
    );

CREATE TABLE
    settings (
        id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        name text NOT NULL,
        value text NOT NULL
    );

CREATE TABLE
    reviews (
        id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        user_id int NOT NULL,
        product_id int NOT NULL,
        content text NOT NULL,
        rating int NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        deleted_at DATETIME DEFAULT NULL,
        FOREIGN KEY (user_id) REFERENCES users (id),
        FOREIGN KEY (product_id) REFERENCES products (id)
    );

CREATE TABLE
    review_status (
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        product_id INT NOT NULL,
        status INT NOT NULL
    );

CREATE TABLE
    import (
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        total_price decimal(10, 2) NOT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        deleted_at DATETIME DEFAULT NULL,
        FOREIGN KEY (user_id) REFERENCES users (id)
    );

CREATE TABLE
    imports_products (
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        import_id INT NOT NULL,
        product_id INT NOT NULL,
        quantity INT NOT NULL,
        price decimal(10, 2) NOT NULL,
        FOREIGN KEY (import_id) REFERENCES import (id),
        FOREIGN KEY (product_id) REFERENCES products (id)
    );
