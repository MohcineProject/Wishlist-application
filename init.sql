-- Start transaction
START TRANSACTION;

-- Insert users (one admin and one non-admin)
INSERT INTO `user` (`id`, `email`, `first_name`, `last_name`, `password`, `is_locked`, `is_admin`, `image`) VALUES
(1, 'admin@example.com', 'Admin', 'User', '$2y$10$adminpasswordhash', 0, 1, NULL), -- Admin user
(2, 'user@example.com', 'Regular', 'User', '$2y$10$userpasswordhash', 0, 0, NULL); -- Non-admin user

-- Insert wishlists (one for each user)
INSERT INTO `wishlist` (`id`, `name`, `deadline`, `is_disabled`, `owner_id`) VALUES
(1, 'Admin Wishlist', '2025-12-31 23:59:59', 0, 1), -- Admin's wishlist
(2, 'User Wishlist', '2025-12-31 23:59:59', 0, 2); -- Non-admin's wishlist

-- Insert items (linked to wishlists)
INSERT INTO `item` (`id`, `wishlist_id`, `title`, `description`, `url`, `image`, `price`) VALUES
(1, 1, 'Admin Item 1', 'Description for Admin Item 1', 'https://example.com/admin-item-1', NULL, 100.00), -- Item for admin's wishlist
(2, 1, 'Admin Item 2', 'Description for Admin Item 2', 'https://example.com/admin-item-2', NULL, 200.00), -- Item for admin's wishlist
(3, 2, 'User Item 1', 'Description for User Item 1', 'https://example.com/user-item-1', NULL, 50.00), -- Item for useVALUESr's wishlist
(4, 2, 'User Item 2', 'Description for User Item 2', 'https://example.com/user-item-2', NULL, 75.00); -- Item for user's wishlist

-- Insert purchase proofs (linked to items)
INSERT INTO `purchase_proof` (`id`, `item_id`, `buyer_id`, `congrats_text`, `image_path`) VALUES
(1, 1, 1, 'Congrats on purchasing Admin Item 1!', '/path/to/admin-item-1-proof.png'), -- Proof for admin's item, buyer is user with ID 2
(2, 3, 1, 'Congrats on purchasing User Item 1!', '/path/to/user-item-1-proof.png'); -- Proof for user's item, buyer is user with ID 1

-- Commit transaction
COMMIT;