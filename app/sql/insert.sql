-- Active: 1694406583293@@127.0.0.1@3306@bookstore

SET FOREIGN_KEY_CHECKS=0;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET time_zone = "+00:00";

INSERT INTO
    `authors` (
        `id`,
        `image`,
        `name`,
        `deleted_at`
    )
VALUES (
        1,
        '/resources/images/authors/jkrowling.jpg',
        'J.K. Rowling',
        NULL
    ), (
        2,
        '/resources/images/authors/stephenking.jpg',
        'Stephen King',
        NULL
    ), (
        3,
        '/resources/images/authors/agatha.jpg',
        'Agatha Christie',
        NULL
    ), (
        4,
        '/resources/images/authors/harperlee.jpg',
        'Harper Lee',
        NULL
    ), (
        5,
        '/resources/images/authors/fitzgerald.jpg',
        'F. Scott Fitzgerald',
        NULL
    ), (
        6,
        '/resources/images/authors/janeausten.jpg',
        'Jane Austen',
        NULL
    ), (
        7,
        '/resources/images/authors/charliedickens.jpg',
        'Charles Dickens',
        NULL
    ), (
        8,
        '/resources/images/authors/marktwain.jpg',
        'Mark Twain',
        NULL
    ), (
        9,
        '/resources/images/authors/ernesthemingway.jpg',
        'Ernest Hemingway',
        NULL
    );

INSERT INTO
    `categories` (
        `id`,
        `image`,
        `name`,
        `deleted_at`
    )
VALUES (
        1,
        '/resources/images/categories/fiction.png',
        'Fiction',
        NULL
    ), (
        2,
        '/resources/images/categories/non-fiction.png',
        'Non-Fiction',
        NULL
    ), (
        3,
        '/resources/images/categories/mystery.png',
        'Mystery',
        NULL
    ), (
        4,
        '/resources/images/categories/romance.png',
        'Romance',
        NULL
    ), (
        5,
        '/resources/images/categories/science-fiction.png',
        'Science Fiction',
        NULL
    ), (
        6,
        '/resources/images/categories/biography.png',
        'Biography',
        NULL
    ), (
        7,
        '/resources/images/categories/history.jpg',
        'History',
        NULL
    ), (
        8,
        '/resources/images/categories/thriller.png',
        'Thriller',
        NULL
    ), (
        9,
        '/resources/images/categories/horror.png',
        'Horror',
        NULL
    );

INSERT INTO
    coupons (
        id,
        code,
        quantity,
        required,
        percent,
        expired,
        description
    )
VALUES (
        1,
        'SUMMERSALE',
        5,
        100,
        10,
        '2023-08-31',
        'Summer Sale! Get 10% off orders over $100.'
    ), (
        2,
        'FALLSALE',
        7,
        100,
        15,
        '2023-11-30',
        'Fall Sale! Get 15% off orders over $150.'
    ), (
        3,
        'WINTERSALE',
        7,
        200,
        20,
        '2024-02-28',
        'Winter Sale! Get 20% off orders over $200.'
    ), (
        4,
        'SPRINGSALE',
        10,
        100,
        5,
        '2024-05-31',
        'Spring Sale! Get 5% off orders over $100.'
    ), (
        5,
        'NEWCUSTOMER',
        50,
        0,
        10,
        '2023-12-31',
        'New Customer Discount! Get 10% off your first order.'
    ), (
        6,
        'RETURNINGCUSTOMER',
        5,
        100,
        10,
        '2023-12-31',
        'Returning Customer Discount! Get 10% off orders over $100.'
    ), (
        7,
        'SPECIALGIFT',
        4,
        500,
        50,
        '2023-12-31',
        'Free Gift with Purchase! Get 50% off with orders over $500.'
    ), (
        8,
        'FREESHIPPING',
        3,
        75,
        7,
        '2023-12-31',
        'Free Shipping! Get 7% off with orders over $75.'
    );

INSERT INTO
    `permissions` (`id`, `name`, `deleted_at`)
VALUES (1, 'dashboard.access', NULL), (2, 'author.access', NULL), (3, 'author.create', NULL), (4, 'author.delete', NULL), (5, 'author.show', NULL), (6, 'author.update', NULL), (7, 'cart.access', NULL), (8, 'cart.create', NULL), (9, 'cart.delete', NULL), (10, 'cart.show', NULL), (11, 'cart.update', NULL), (12, 'category.access', NULL), (13, 'category.create', NULL), (14, 'category.delete', NULL), (15, 'category.show', NULL), (16, 'category.update', NULL), (17, 'coupon.access', NULL), (18, 'coupon.create', NULL), (19, 'coupon.delete', NULL), (20, 'coupon.show', NULL), (21, 'coupon.update', NULL), (22, 'order.access', NULL), (23, 'order.create', NULL), (24, 'order.delete', NULL), (25, 'order.show', NULL), (26, 'order.update', NULL), (27, 'permission.access', NULL), (28, 'permission.create', NULL), (29, 'permission.delete', NULL), (30, 'permission.show', NULL), (31, 'permission.update', NULL), (32, 'product.access', NULL), (33, 'product.create', NULL), (34, 'product.delete', NULL), (35, 'product.show', NULL), (36, 'product.update', NULL), (37, 'import.access', NULL), (38, 'import.create', NULL), (39, 'import.delete', NULL), (40, 'import.show', NULL), (41, 'import.update', NULL), (42, 'review.access', NULL), (43, 'review.create', NULL), (44, 'review.delete', NULL), (45, 'review.show', NULL), (46, 'review.update', NULL), (47, 'role.access', NULL), (48, 'role.create', NULL), (49, 'role.delete', NULL), (50, 'role.show', NULL), (51, 'role.update', NULL), (52, 'setting.access', NULL), (53, 'setting.create', NULL), (54, 'setting.delete', NULL), (55, 'setting.show', NULL), (56, 'setting.update', NULL), (57, 'slide.access', NULL), (58, 'slide.create', NULL), (59, 'slide.delete', NULL), (60, 'slide.show', NULL), (61, 'slide.update', NULL), (62, 'tag.access', NULL), (63, 'tag.create', NULL), (64, 'tag.delete', NULL), (65, 'tag.show', NULL), (66, 'tag.update', NULL), (67, 'user.access', NULL), (68, 'user.create', NULL), (69, 'user.delete', NULL), (70, 'user.show', NULL), (71, 'user.update', NULL), (72, 'wishlist.access', NULL), (73, 'wishlist.create', NULL), (74, 'wishlist.delete', NULL), (75, 'wishlist.show', NULL), (76, 'wishlist.update', NULL);

INSERT INTO
    `products` (
        `id`,
        `isbn`,
        `name`,
        `author_id`,
        `publisher_id`,
        `price`,
        `description`,
        `image`,
        `quantity`,
        `deleted_at`
    )
VALUES (
        1,
        '9780439139601',
        'Harry Potter and the Philosophers Stone',
        1,
        1,
        10.99,
        'The first book in the Harry Potter series',
        '/resources/images/products/harry-potter-and-stone.jpg',
        100,
        NULL
    ), (
        2,
        '9780545010221',
        'Pride and Prejudice',
        2,
        2,
        7.99,
        'The first book in The Hunger Games trilogy',
        '/resources/images/products/PrideAndPrejudice.jpg',
        150,
        NULL
    ), (
        3,
        '9780061891922',
        'To Kill a Mockingbird',
        3,
        3,
        8.99,
        'A Pulitzer Prize-winning novel set in the 1930s',
        '/resources/images/products/To_Kill_a_Mockingbird.jpg',
        80,
        NULL
    ), (
        4,
        '9780547928227',
        'The Hobbit',
        4,
        4,
        6.99,
        'A fantasy novel by J. R. R. Tolkien',
        '/resources/images/products/The_Hobbit.jpg',
        120,
        NULL
    ), (
        5,
        '9780060256654',
        'The Adventures of Huckleberry Fin',
        5,
        5,
        5.99,
        'A childrens picture book by Maurice Sendak',
        '/resources/images/products/The_Adventures_of_Huckleberry_Finn.jpg',
        200,
        NULL
    ), (
        6,
        '9780439554930',
        'Harry Potter and the Chamber of Secrets',
        1,
        1,
        11.99,
        'The second book in the Harry Potter series',
        '/resources/images/products/Harry-Potter-and-the-chamber-of-secrets.jpg',
        90,
        NULL
    ), (
        7,
        '9780439139595',
        'Harry Potter and the Prisoner of Azkaban',
        1,
        1,
        12.99,
        'The third book in the Harry Potter series',
        '/resources/images/products/harry-potter-and-prisoner.jpg',
        70,
        NULL
    ), (
        8,
        '9780439136365',
        'Harry Potter and the Goblet of Fire',
        1,
        1,
        13.99,
        'The fourth book in the Harry Potter series',
        '/resources/images/products/harry-potter-and-goblet-of-fire.jpg',
        60,
        NULL
    ), (
        9,
        '9780439358071',
        'Harry Potter and the Order of Phoenix',
        1,
        1,
        14.99,
        'The fifth book in the Harry Potter series',
        '/resources/images/products/harry-potter-and-order-of-phoenix.jpg',
        50,
        NULL
    ), (
        10,
        '9780345337665',
        'The Great Gatsby',
        1,
        1,
        10.99,
        'The story of the mysteriously wealthy Jay Gatsby and his love for the beautiful Daisy Buchanan',
        '/resources/images/products/The_Great_Gatsby.jpg',
        100,
        NULL
    ), (
        11,
        '9780061120084',
        'The Odyssey',
        2,
        2,
        8.99,
        'The Odyssey does not follow a linear chronology. The reader begins in the middle of the tale',
        '/resources/images/products/The_Odyssey.jpg',
        50,
        NULL
    ), (
        12,
        '9780743273565',
        'The Da Vinci Code',
        3,
        3,
        14.99,
        'A murder in the Louvre and clues in Da Vincis art lead to a religious mystery',
        '/resources/images/products/the-davinci-code.jpg',
        200,
        NULL
    ), (
        13,
        '9780679723165',
        '1984',
        3,
        4,
        9.99,
        'George Orwells dystopian classic.',
        '/resources/images/products/1984.jpg',
        10,
        NULL
    ), (
        14,
        '9780141187761',
        'The Hitchhikers Guide To The Galaxy',
        5,
        6,
        16.99,
        'Gabriel Garcia Marquezs masterpiece of magical realism.',
        '/resources/images/products/The_Hitchhikers_Guide_to_the_Galaxy.jpg',
        15,
        NULL
    ), (
        15,
        '9780060850524',
        'The Picture of Dorian Guy',
        7,
        9,
        14.99,
        'Paulo inspiring tale of following your dreams.',
        '/resources/images/products/The_Picture_of_Dorian_Gray.jpg',
        30,
        NULL
    ), (
        16,
        '9780062315007',
        'The Catcher in the Rye',
        9,
        10,
        10.99,
        'Paula thrilling page-turner.',
        '/resources/images/products/The_Catcher_in_the_Rye.jpg',
        20,
        NULL
    ), (
        17,
        '9780142410370',
        'The Brothers Karamazov',
        5,
        6,
        12.99,
        'The first book in the Hunger Games trilogy.',
        '/resources/images/products/The_Brothers_Karamazov.jpg',
        15,
        NULL
    ), (
        18,
        '9780765331724',
        'Animal Farm',
        6,
        7,
        15.99,
        'The first book in the Kingkiller Chronicle series.',
        '/resources/images/products/Animal_Farm.jpg',
        12,
        NULL
    ), (
        19,
        '9780765311788',
        'Crime and Punishment',
        7,
        9,
        8.99,
        'The first book in the Mistborn trilogy.',
        '/resources/images/products/crime-and-punishment.jpg',
        10,
        NULL
    ), (
        20,
        '9780143134770',
        'Moby Dick',
        6,
        5,
        12.99,
        'An impassioned novel of activism and natural-world power that is literary in its roots and in its soaring imagination.',
        '/resources/images/products/moby-dick.jpg',
        15,
        NULL
    ), (
        21,
        '9780432133693',
        'The Road Not Taken',
        1,
        1,
        10.99,
        'A collection of poems by Robert Frost.',
        '/resources/images/products/TheRoadNotTaken.jpg',
        100,
        NULL
    ), (
        22,
        '9780542013293',
        'The Catcher in the Rye',
        2,
        2,
        7.99,
        'A novel by J.D. Salinger.',
        '/resources/images/products/TheCatcherintheRye.jpg',
        150,
        NULL
    ), (
        23,
        '9780062893993',
        'The Girl with the Dragon Tattoo',
        3,
        3,
        8.99,
        'A psychological thriller by Stieg Larsson.',
        '/resources/images/products/TheGirlwiththeDragonTattoo.jpg',
        80,
        NULL
    ), (
        24,
        '9780542923293',
        'The Hunger Games',
        4,
        4,
        6.99,
        'A dystopian series by Suzanne Collins.',
        '/resources/images/products/The_Hunger_Games.jpg',
        120,
        NULL
    ), (
        25,
        '9780062253693',
        'The Fault in Our Stars',
        5,
        5,
        5.99,
        'A young adult novel by John Green.',
        '/resources/images/products/The_Fault_in_Our_Stars.jpg',
        200,
        NULL
    ), (
        26,
        '9780432553993',
        'The Alchemist',
        1,
        1,
        11.99,
        'A philosophical novel by Paulo Coelho.',
        '/resources/images/products/The_Alchemist.jpg',
        90,
        NULL
    ), (
        27,
        '9780432133593',
        'The Shining',
        1,
        1,
        12.99,
        'A horror novel by Stephen King.',
        '/resources/images/products/harry-potter-and-prisoner.jpg',
        70,
        NULL
    ), (
        28,
        '9780432133393',
        'The Chronicles of Narnia',
        1,
        1,
        13.99,
        'TA fantasy series by C.S. Lewis.',
        '/resources/images/products/harry-potter-and-goblet-of-fire.jpg',
        60,
        NULL
    ), (
        29,
        '9780432353093',
        'Brave New World',
        1,
        1,
        14.99,
        'A dystopian novel by Aldous Huxley.',
        '/resources/images/products/harry-potter-and-order-of-phoenix.jpg',
        50,
        NULL
    ), (
        30,
        '9780342333693',
        'The Grapes of Wrath',
        1,
        1,
        10.99,
        'The story of the mysteriously wealthy Jay Gatsby and his love for the beautiful Daisy Buchanan',
        '/resources/images/products/The_Great_Gatsby.jpg',
        100,
        NULL
    ), (
        31,
        '9780062123093',
        'The Book Thief',
        2,
        2,
        8.99,
        'A historical novel by Markus Zusak.',
        '/resources/images/products/The_Odyssey.jpg',
        50,
        NULL
    ), (
        32,
        '9780742273593',
        'The Brothers Karamazov',
        3,
        3,
        14.99,
        'The Brothers Karamazov is a gripping and philosophical novel by Fyodor Dostoevsky. It explores themes of morality, faith, and the human condition. Set in 19th-century Russia, the story revolves around the complex relationships within the Karamazov family, featuring characters such as the intellectual Ivan, the passionate Dmitri, and the saintly Alyosha. As they grapple with their own desires and beliefs, the novel delves into profound questions about the nature of God and the existence of evil',
        '/resources/images/products/the-davinci-code.jpg',
        200,
        NULL
    ), (
        33,
        '9780672723193',
        'The Count of Monte Cristoa',
        3,
        4,
        9.99,
        'The Count of Monte Cristo is a thrilling adventure novel by Alexandre Dumas. It follows the journey of Edmond Dantès, a young sailor who is falsely accused of treason and imprisoned in the Château' ,
        '/resources/images/products/1984.jpg',
        10,
        NULL
    ), (
        34,
        '9780142183793',
        'The Scarlet Letter',
        5,
        6,
        16.99,
        'The Scarlet Letter is a classic novel by Nathaniel Hawthorne set in 17th-century Puritan Massachusetts. The story centers on Hester Prynne, a woman who is shunned by her community after bearing an illegitimate child and is forced to wear a scarlet letter "A" as a mark of her shame. Through Hesters journey, the novel explores themes of sin, redemption, and the consequences of societal judgment. Hawthornes rich prose and intricate symbolism make this novel a masterwork of American literature.',
        '/resources/images/products/TheScarletLetter.jpg',
        15,
        NULL
    ), (
        35,
        '9780062853593',
        'Don Quixote',
        7,
        9,
        14.99,
        'Don Quixote is a groundbreaking novel by Miguel de Cervantes. It follows the adventures of an aging nobleman, Don Quixote, who becomes convinced that he is a knight and sets out on a series of absurd and chivalrous quests with his loyal squire, Sancho Panza. The novel is a satirical exploration of idealism and reality, and it remains a beloved classic of world literature. Cervantes wit and humor shine through in this timeless tale.',
        '/resources/images/products/donquixote.jpg',
        30,
        NULL
    ), (
        36,
        '9780062313093',
        'One Hundred Years of Solitude',
        9,
        10,
        10.99,
        'One Hundred Years of Solitude is a masterpiece of magical realism by Gabriel García Márquez. It tells the multi-generational saga of the Buendía family in the fictional town of Macondo. The novel weaves together elements of fantasy and reality as it explores themes of love, power, and the cyclical nature of history. Márquezs lush and imaginative storytelling has made this novel an enduring favorite for readers worldwide.',
        '/resources/images/products/OneHundredYearsofSolitude.jpg',
        20,
        NULL
    ), (
        37,
        '9780142413393',
        'The Brothers Karamazov',
        5,
        6,
        12.99,
        'The first book in the Hunger Games trilogy.',
        '/resources/images/products/TheBrothersKaramazov.jpg',
        15,
        NULL
    ), (
        38,
        '9780762333793',
        'Heart of Darkness',
        6,
        7,
        15.99,
        'Heart of Darkness is a novella by Joseph Conrad. It explores the dark heart of colonial Africa as the protagonist, Marlow, embarks on a journey up the Congo River to find Kurtz, a mysterious ivory trader who has descended into madness. The novella is a searing critique of imperialism and the human capacity for cruelty and moral decay. Conrads evocative prose and exploration of the human psyche have cemented this work as a literary classic..',
        '/resources/images/products/HeartofDarkness.jpg',
        12,
        NULL
    ), (
        39,
        '9780762313793',
        'The Lord of the Flies',
        7,
        9,
        8.99,
        'The Lord of the Flies is a thought-provoking novel by William Golding. It follows a group of British boys stranded on a deserted island after a plane crash. As they attempt to govern themselves, their society descends into chaos and violence, revealing the dark impulses of human nature. The novel is a powerful exploration of the inherent savagery in humanity and remains a widely studied work in literature.',
        '/resources/images/products/TheLordoftheFlies.jpg',
        10,
        NULL
    ), (
        40,
        '9780142133793',
        'The Sound and the Fury',
        6,
        5,
        12.99,
        'The Sound and the Fury is a groundbreaking novel by William Faulkner. It employs a stream-of-consciousness narrative style to tell the story of the Compson family in the American South. Through the perspectives of different family members, the novel explores themes of decay, loss, and the passage of time. Faulkner innovative storytelling and complex characters have solidified this novel reputation as a modernist masterpiece.',
        '/resources/images/products/moby-dick.jpg',
        15,
        NULL
    );

INSERT INTO
    `products_categories` (
        `id`,
        `product_id`,
        `category_id`
    )
VALUES (1, 1, 1), (2, 2, 2), (3, 3, 3), (4, 4, 4), (5, 5, 5), (6, 6, 1), (7, 7, 1), (8, 8, 1), (9, 9, 1), (10, 10, 1), (11, 11, 2), (12, 12, 3), (13, 13, 4), (14, 14, 5), (15, 15, 6), (16, 16, 7), (17, 17, 8), (18, 18, 9), (19, 19, 1), (20, 20, 2), (21, 21, 1), (22, 22, 2), (23, 23, 3), (24, 24, 4), (25, 25, 5), (26, 26, 1), (27, 27, 1), (28, 28, 1), (29, 29, 1), (30, 30, 1), (31, 31, 2), (32, 32, 3), (33, 33, 4), (34, 34, 5), (35, 35, 6), (36, 36, 7), (37, 37, 8), (38, 38, 9), (39, 39, 1), (40, 40, 2);

INSERT INTO
    `products_tags` (`id`, `product_id`, `tag_id`)
VALUES (1, 1, 1), (2, 1, 2), (3, 2, 2), (4, 2, 3), (5, 3, 3), (6, 3, 4), (7, 4, 4), (8, 4, 1), (9, 5, 1), (10, 5, 2), (11, 6, 2), (12, 6, 3), (13, 7, 3), (14, 7, 4), (15, 8, 4), (16, 8, 1), (17, 9, 1), (18, 9, 2), (19, 10, 2), (20, 10, 3), (21, 11, 3), (22, 11, 4), (23, 12, 4), (24, 12, 1), (25, 13, 1), (26, 13, 2), (27, 14, 2), (28, 14, 3), (29, 15, 3), (30, 15, 4), (31, 16, 4), (32, 16, 1), (33, 17, 1), (34, 17, 2), (35, 18, 2), (36, 18, 3), (37, 19, 3), (38, 19, 4), (39, 20, 4), (40, 20, 1), (41, 21, 1), (42, 21, 2), (43, 22, 2), (44, 22, 3), (45, 23, 3), (46, 23, 4), (47, 24, 4), (48, 24, 1), (49, 25, 1), (50, 25, 2), (51, 26, 2), (52, 26, 3), (53, 27, 3), (54, 27, 4), (55, 28, 4), (56, 28, 1), (57, 29, 1), (58, 29, 2), (59, 30, 2), (60, 30, 3), (61, 31, 3), (62, 31, 4), (63, 32, 4), (64, 32, 1), (65, 33, 1), (66, 33, 2), (67, 34, 2), (68, 34, 3), (69, 35, 3), (70, 35, 4), (71, 36, 4), (72, 36, 1), (73, 37, 1), (74, 37, 2), (75, 38, 2), (76, 38, 3), (77, 39, 3), (78, 39, 4), (79, 40, 4), (80, 40, 1);

INSERT INTO
    `publishers` (
        `id`,
        `image`,
        `name`,
        `deleted_at`
    )
VALUES (
        1,
        NULL,
        'Penguin Random House',
        NULL
    ), (2, NULL, 'HarperCollins', NULL), (
        3,
        NULL,
        'Simon & Schuster',
        NULL
    ), (4, NULL, 'Hachette Livre', NULL), (
        5,
        NULL,
        'Macmillan Publishers',
        NULL
    ), (
        6,
        NULL,
        'Bloomsbury Publishing',
        NULL
    ), (
        7,
        NULL,
        'Scholastic Corporation',
        NULL
    ), (
        8,
        NULL,
        'Pearson Education',
        NULL
    ), (9, NULL, 'Wiley', NULL), (
        10,
        NULL,
        'Oxford University Press',
        NULL
    );

INSERT INTO
    `roles` (`id`, `name`, `deleted_at`)
VALUES (1, 'customer', NULL), (2, 'moderator', NULL), (3, 'administrator', NULL);

INSERT INTO
    `roles_permissions` (
        `id`,
        `permission_id`,
        `role_id`,
        `status`
    )
VALUES (1, 1, 3, 1), (2, 1, 2, 1), (3, 2, 3, 1), (4, 3, 3, 1), (5, 4, 3, 1), (6, 5, 3, 1), (7, 6, 3, 1), (8, 7, 3, 1), (9, 8, 3, 1), (10, 9, 3, 1), (11, 10, 3, 1), (12, 11, 3, 1), (13, 12, 3, 1), (14, 13, 3, 1), (15, 14, 3, 1), (16, 15, 3, 1), (17, 16, 3, 1), (18, 17, 3, 1), (19, 18, 3, 1), (20, 19, 3, 1), (21, 20, 3, 1), (22, 21, 3, 1), (23, 22, 3, 1), (24, 23, 3, 1), (25, 24, 3, 1), (26, 25, 3, 1), (27, 26, 3, 1), (28, 27, 3, 1), (29, 28, 3, 1), (30, 29, 3, 1), (31, 30, 3, 1), (32, 31, 3, 1), (33, 32, 3, 1), (34, 33, 3, 1), (35, 34, 3, 1), (36, 35, 3, 1), (37, 36, 3, 1), (38, 37, 3, 1), (39, 38, 3, 1), (40, 39, 3, 1), (41, 40, 3, 1), (42, 41, 3, 1), (43, 42, 3, 1), (44, 43, 3, 1), (45, 44, 3, 1), (46, 45, 3, 1), (47, 46, 3, 1), (48, 47, 3, 1), (49, 48, 3, 1), (50, 49, 3, 1), (51, 50, 3, 1), (52, 51, 3, 1), (53, 52, 3, 1), (54, 53, 3, 1), (55, 54, 3, 1), (56, 55, 3, 1), (57, 56, 3, 1), (58, 57, 3, 1), (59, 58, 3, 1), (60, 59, 3, 1), (61, 60, 3, 1), (62, 61, 3, 1), (63, 62, 3, 1), (64, 63, 3, 1), (65, 64, 3, 1), (66, 65, 3, 1), (67, 66, 3, 1), (68, 67, 3, 1), (69, 68, 3, 1), (70, 69, 3, 1), (71, 70, 3, 1), (72, 71, 3, 1), (73, 72, 3, 1), (74, 73, 3, 1), (75, 74, 3, 1), (76, 75, 3, 1), (77, 76, 3, 1);

INSERT INTO
    `settings` (`id`, `name`, `value`)
VALUES (1, 'tax', '50');

INSERT INTO
    `shipping_methods` (
        `id`,
        `name`,
        `price`,
        `description`,
        `status`,
        `deleted_at`
    )
VALUES (
        1,
        'Standard Shipping',
        5.99,
        'Delivery within 7-10 business days',
        1,
        NULL
    ), (
        2,
        'Express Shipping',
        14.99,
        'Delivery within 2-3 business days',
        1,
        NULL
    ), (
        3,
        'Next Day Shipping',
        29.99,
        'Guaranteed delivery next business day',
        1,
        NULL
    ), (
        4,
        'International Shipping',
        24.99,
        'Delivery within 10-14 business days to international destinations',
        1,
        NULL
    );

INSERT INTO
    `slides` (
        `id`,
        `image`,
        `name`,
        `status`,
        `deleted_at`
    )
VALUES (
        1,
        '/resources/images/slides/slide-books-1.jpg',
        'book festival',
        1,
        NULL
    ), (
        2,
        '/resources/images/slides/slide-books-2.png',
        'book festival',
        1,
        NULL
    ), (
        3,
        '/resources/images/slides/slide-books-3.png',
        'book festival',
        1,
        NULL
    ), (
        4,
        '/resources/images/slides/slide-books-4.png',
        'book festival',
        1,
        NULL
    ), (
        5,
        '/resources/images/slides/slide-books-5.jpg',
        'book festival',
        1,
        NULL
    ), (
        6,
        '/resources/images/slides/slide-books-6.png',
        'book festival',
        1,
        NULL
    );

INSERT INTO
    `tags` (
        `id`,
        `image`,
        `name`,
        `deleted_at`
    )
VALUES (1, NULL, 'Bestselling', NULL), (2, NULL, 'Popular', NULL), (3, NULL, 'New', NULL), (4, NULL, 'Recommended', NULL);

INSERT INTO
    import (user_id, total_price)
VALUES (3, 150), (3, 300), (3, 500);

INSERT INTO
    `users` (
        `id`,
        `email`,
        `name`,
        `phone`,
        `password`,
        `gender`,
        `image`,
        `role_id`,
        `status`,
        `address`,
        `deleted_at`
    )
VALUES (
        1,
        'customer@customer.com',
        'Customer',
        '0123456789',
        '$2y$10$msjZCcmhGinMn7R8Mg9zbe29bbnF/wgeIpr/5eIwQugefkko7eiRK',
        0,
        NULL,
        1,
        1,
        NULL,
        NULL
    ), (
        2,
        'moderator@moderator.com',
        'Moderator',
        '0123456789',
        '$2y$10$msjZCcmhGinMn7R8Mg9zbe29bbnF/wigeIpr/5eIwQugefkko7eiRK',
        0,
        NULL,
        2,
        1,
        NULL,
        NULL
    ), (
        3,
        'customer1@customer.com',
        'Customer1',
        '0123456789',
        '$2y$10$msjZCcmhGinMn7R8Mg9zbe29bbnF/wgeIpr/5eIwQugefkko7eiRK',
        0,
        NULL,
        1,
        1,
        NULL,
        NULL
    ),(
        4,
        'customer2@customer.com',
        'Customer2',
        '0123456789',
        '$2y$10$msjZCcmhGinMn7R8Mg9zbe29bbnF/wgeIpr/5eIwQugefkko7eiRK',
        0,
        NULL,
        1,
        1,
        NULL,
        NULL
    ),(
        5,
        'customer3@customer.com',
        'Customer3',
        '0123456789',
        '$2y$10$msjZCcmhGinMn7R8Mg9zbe29bbnF/wgeIpr/5eIwQugefkko7eiRK',
        0,
        NULL,
        1,
        1,
        NULL,
        NULL
    ),(
        6,
        'customer4@customer.com',
        'Customer4',
        '0123456789',
        '$2y$10$msjZCcmhGinMn7R8Mg9zbe29bbnF/wgeIpr/5eIwQugefkko7eiRK',
        0,
        NULL,
        1,
        1,
        NULL,
        NULL
    ),(
        7,
        'customer5@customer.com',
        'Customer5',
        '0123456789',
        '$2y$10$msjZCcmhGinMn7R8Mg9zbe29bbnF/wgeIpr/5eIwQugefkko7eiRK',
        0,
        NULL,
        1,
        1,
        NULL,
        NULL
    ),(
        8,
        'administrator@administrator.com',
        'Administrator',
        '0123456789',
        '$2y$10$msjZCcmhGinMn7R8Mg9zbe29bbnF/wgeIpr/5eIwQugefkko7eiRK',
        0,
        NULL,
        3,
        1,
        NULL,
        NULL
    );

INSERT INTO
    review_status (product_id, status)
VALUES (1, 1), (2, 1), (3, 0), (4, 1), (5, 0), (6, 1), (7, 0), (8, 1), (9, 0), (10, 1), (11, 0), (12, 1), (13, 0), (14, 1), (15, 0), (16, 1), (17, 0), (18, 1), (19, 0), (20, 1), (21, 0), (22, 1), (23, 0), (24, 1), (25, 0), (26, 1), (27, 0), (28, 1), (29, 0), (30, 1), (31, 0), (32, 1), (33, 0), (34, 1), (35, 0), (36, 1), (37, 0), (38, 1), (39, 0), (40, 1);

SET FOREIGN_KEY_CHECKS=1;

COMMIT;
