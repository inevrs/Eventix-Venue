-- Demo seed for Eventix: add 30 customer users, 30 bookings, and 30 reviews
-- Password for all seeded users: password

INSERT INTO users (full_name, email, phone, password, role, created_at)
VALUES
('Jack', 'customer01@eventix.dev', '+60120000001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW()),
('Alicia', 'customer02@eventix.dev', '+60120000002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW()),
('Daniel', 'customer03@eventix.dev', '+60120000003', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW()),
('Mia', 'customer04@eventix.dev', '+60120000004', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW()),
('Noah', 'customer05@eventix.dev', '+60120000005', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW()),
('Sophia', 'customer06@eventix.dev', '+60120000006', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW()),
('Liam', 'customer07@eventix.dev', '+60120000007', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW()),
('Emma', 'customer08@eventix.dev', '+60120000008', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW()),
('Ethan', 'customer09@eventix.dev', '+60120000009', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW()),
('Olivia', 'customer10@eventix.dev', '+60120000010', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW()),
('James', 'customer11@eventix.dev', '+60120000011', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW()),
('Charlotte', 'customer12@eventix.dev', '+60120000012', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW()),
('Benjamin', 'customer13@eventix.dev', '+60120000013', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW()),
('Amelia', 'customer14@eventix.dev', '+60120000014', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW()),
('Lucas', 'customer15@eventix.dev', '+60120000015', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW()),
('Harper', 'customer16@eventix.dev', '+60120000016', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW()),
('Henry', 'customer17@eventix.dev', '+60120000017', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW()),
('Ella', 'customer18@eventix.dev', '+60120000018', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW()),
('Alexander', 'customer19@eventix.dev', '+60120000019', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW()),
('Grace', 'customer20@eventix.dev', '+60120000020', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW()),
('Mason', 'customer21@eventix.dev', '+60120000021', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW()),
('Chloe', 'customer22@eventix.dev', '+60120000022', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW()),
('Logan', 'customer23@eventix.dev', '+60120000023', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW()),
('Lily', 'customer24@eventix.dev', '+60120000024', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW()),
('Owen', 'customer25@eventix.dev', '+60120000025', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW()),
('Zara', 'customer26@eventix.dev', '+60120000026', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW()),
('Leo', 'customer27@eventix.dev', '+60120000027', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW()),
('Nora', 'customer28@eventix.dev', '+60120000028', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW()),
('Caleb', 'customer29@eventix.dev', '+60120000029', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW()),
('Scarlett', 'customer30@eventix.dev', '+60120000030', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW());

INSERT INTO bookings (user_id, venue_id, start_date, end_date, guest_count, notes, status, created_at)
SELECT u.id, seed.venue_id, DATE_SUB(CURDATE(), INTERVAL 20 DAY), DATE_SUB(CURDATE(), INTERVAL 10 DAY), 80 + MOD(u.id, 8), 'Demo booking for seeded review', 'confirmed', NOW()
FROM (
    SELECT 'customer01@eventix.dev' AS email, 4 AS venue_id UNION ALL
    SELECT 'customer02@eventix.dev', 5 UNION ALL
    SELECT 'customer03@eventix.dev', 6 UNION ALL
    SELECT 'customer04@eventix.dev', 7 UNION ALL
    SELECT 'customer05@eventix.dev', 8 UNION ALL
    SELECT 'customer06@eventix.dev', 9 UNION ALL
    SELECT 'customer07@eventix.dev', 11 UNION ALL
    SELECT 'customer08@eventix.dev', 13 UNION ALL
    SELECT 'customer09@eventix.dev', 16 UNION ALL
    SELECT 'customer10@eventix.dev', 17 UNION ALL
    SELECT 'customer11@eventix.dev', 18 UNION ALL
    SELECT 'customer12@eventix.dev', 19 UNION ALL
    SELECT 'customer13@eventix.dev', 4 UNION ALL
    SELECT 'customer14@eventix.dev', 5 UNION ALL
    SELECT 'customer15@eventix.dev', 6 UNION ALL
    SELECT 'customer16@eventix.dev', 7 UNION ALL
    SELECT 'customer17@eventix.dev', 8 UNION ALL
    SELECT 'customer18@eventix.dev', 9 UNION ALL
    SELECT 'customer19@eventix.dev', 11 UNION ALL
    SELECT 'customer20@eventix.dev', 13 UNION ALL
    SELECT 'customer21@eventix.dev', 16 UNION ALL
    SELECT 'customer22@eventix.dev', 17 UNION ALL
    SELECT 'customer23@eventix.dev', 18 UNION ALL
    SELECT 'customer24@eventix.dev', 19 UNION ALL
    SELECT 'customer25@eventix.dev', 4 UNION ALL
    SELECT 'customer26@eventix.dev', 5 UNION ALL
    SELECT 'customer27@eventix.dev', 6 UNION ALL
    SELECT 'customer28@eventix.dev', 7 UNION ALL
    SELECT 'customer29@eventix.dev', 8 UNION ALL
    SELECT 'customer30@eventix.dev', 9
) AS seed
JOIN users u ON u.email = seed.email;

INSERT INTO ratings (user_id, venue_id, rating, review, created_at)
SELECT b.user_id, b.venue_id, seed.rating, seed.review, NOW()
FROM (
    SELECT 'customer01@eventix.dev' AS email, 5 AS rating, 'The venue felt elegant and the overall experience was flawless. We loved the atmosphere and service.' AS review UNION ALL
    SELECT 'customer02@eventix.dev', 4, 'Beautiful space with great lighting and a very professional team. Everything was well organized.' UNION ALL
    SELECT 'customer03@eventix.dev', 5, 'Such a charming location for a small celebration. The backdrop and ambience were perfect.' UNION ALL
    SELECT 'customer04@eventix.dev', 4, 'Really smooth booking process and the venue looked stunning for our event.' UNION ALL
    SELECT 'customer05@eventix.dev', 5, 'Amazing views and a classy setup. Great attention to detail from start to finish.' UNION ALL
    SELECT 'customer06@eventix.dev', 5, 'The rooftop feel was so relaxing and stylish. A memorable venue for a private dinner.' UNION ALL
    SELECT 'customer07@eventix.dev', 4, 'Great for corporate events and the arrangement was very professional.' UNION ALL
    SELECT 'customer08@eventix.dev', 5, 'Luxury atmosphere and excellent service. We would definitely recommend this place.' UNION ALL
    SELECT 'customer09@eventix.dev', 4, 'Loved the modern look and the event flow was very smooth.' UNION ALL
    SELECT 'customer10@eventix.dev', 5, 'Lovely venue with an open layout that made the event feel extra special.' UNION ALL
    SELECT 'customer11@eventix.dev', 4, 'Great setup and friendly staff. The location helped make our celebration stand out.' UNION ALL
    SELECT 'customer12@eventix.dev', 5, 'Excellent experience from planning to the event day. Truly worth it.' UNION ALL
    SELECT 'customer13@eventix.dev', 4, 'A wonderful venue for an intimate gathering and the decor felt very polished.' UNION ALL
    SELECT 'customer14@eventix.dev', 5, 'Everything was beautiful and easy to manage. The staff were very accommodating.' UNION ALL
    SELECT 'customer15@eventix.dev', 4, 'Great atmosphere and comfortable setting for a relaxed social event.' UNION ALL
    SELECT 'customer16@eventix.dev', 5, 'The space felt premium and the family event went smoothly from start to finish.' UNION ALL
    SELECT 'customer17@eventix.dev', 4, 'Fantastic views and a very welcoming environment for guests.' UNION ALL
    SELECT 'customer18@eventix.dev', 4, 'Perfect venue for a casual but elegant gathering. We enjoyed every moment.' UNION ALL
    SELECT 'customer19@eventix.dev', 5, 'The setup was impressive and the team handled every detail with care.' UNION ALL
    SELECT 'customer20@eventix.dev', 4, 'Well managed event and very pretty surroundings. Highly recommended.' UNION ALL
    SELECT 'customer21@eventix.dev', 5, 'Loved the overall vibe and the venue made our celebration feel premium.' UNION ALL
    SELECT 'customer22@eventix.dev', 4, 'Great experience and the venue fit our theme perfectly.' UNION ALL
    SELECT 'customer23@eventix.dev', 5, 'Beautiful place with plenty of charm and a great location for guests.' UNION ALL
    SELECT 'customer24@eventix.dev', 4, 'This venue created a stunning setting for our event and the service was excellent.' UNION ALL
    SELECT 'customer25@eventix.dev', 5, 'A classy and comfortable venue that made our party feel truly special.' UNION ALL
    SELECT 'customer26@eventix.dev', 4, 'Great event experience with a wonderful ambiance and seamless planning.' UNION ALL
    SELECT 'customer27@eventix.dev', 5, 'Loved the warm atmosphere and the venue looked fantastic on the day.' UNION ALL
    SELECT 'customer28@eventix.dev', 4, 'Very memorable and nicely managed. We had a great time celebrating here.' UNION ALL
    SELECT 'customer29@eventix.dev', 5, 'Excellent venue with beautiful scenery and a very polished finish.' UNION ALL
    SELECT 'customer30@eventix.dev', 4, 'A lovely and stylish place that made our gathering feel special and effortless.'
) AS seed
JOIN users u ON u.email = seed.email
JOIN bookings b ON b.user_id = u.id
WHERE b.venue_id = (
    CASE seed.email
        WHEN 'customer01@eventix.dev' THEN 4
        WHEN 'customer02@eventix.dev' THEN 5
        WHEN 'customer03@eventix.dev' THEN 6
        WHEN 'customer04@eventix.dev' THEN 7
        WHEN 'customer05@eventix.dev' THEN 8
        WHEN 'customer06@eventix.dev' THEN 9
        WHEN 'customer07@eventix.dev' THEN 11
        WHEN 'customer08@eventix.dev' THEN 13
        WHEN 'customer09@eventix.dev' THEN 16
        WHEN 'customer10@eventix.dev' THEN 17
        WHEN 'customer11@eventix.dev' THEN 18
        WHEN 'customer12@eventix.dev' THEN 19
        WHEN 'customer13@eventix.dev' THEN 4
        WHEN 'customer14@eventix.dev' THEN 5
        WHEN 'customer15@eventix.dev' THEN 6
        WHEN 'customer16@eventix.dev' THEN 7
        WHEN 'customer17@eventix.dev' THEN 8
        WHEN 'customer18@eventix.dev' THEN 9
        WHEN 'customer19@eventix.dev' THEN 11
        WHEN 'customer20@eventix.dev' THEN 13
        WHEN 'customer21@eventix.dev' THEN 16
        WHEN 'customer22@eventix.dev' THEN 17
        WHEN 'customer23@eventix.dev' THEN 18
        WHEN 'customer24@eventix.dev' THEN 19
        WHEN 'customer25@eventix.dev' THEN 4
        WHEN 'customer26@eventix.dev' THEN 5
        WHEN 'customer27@eventix.dev' THEN 6
        WHEN 'customer28@eventix.dev' THEN 7
        WHEN 'customer29@eventix.dev' THEN 8
        WHEN 'customer30@eventix.dev' THEN 9
    END
)
AND b.status = 'confirmed';
