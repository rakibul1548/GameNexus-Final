<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gamer Nexus - Your Ultimate Gaming Store</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            margin: 0;
            padding: 0;
            background-color: #1a1a1a;
            color: #e0e0e0;
        }
        .header {
            background: linear-gradient(90deg, #2a2a72, #009ffd);
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }
        .header h1 {
            margin: 0;
            font-size: 36px;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .featured-games {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
            background: #222;
            margin-bottom: 20px;
        }
        .featured-game {
            background: #2c2c2c;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s;
        }
        .featured-game:hover {
            transform: translateY(-5px);
        }
        .featured-game img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        .featured-game-content {
            padding: 15px;
        }
        .featured-game h3 {
            color: #00b7eb;
            margin: 0 0 10px;
        }
        .featured-game p {
            font-size: 14px;
            color: #b0b0b0;
            margin: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .products-section {
            background: #222;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .section-title {
            color: #00b7eb;
            font-size: 24px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #444;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        .product-card {
            background: #2c2c2c;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        .product-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #e74c3c;
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            z-index: 1;
        }
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #444;
        }
        .product-info {
            padding: 15px;
        }
        .product-title {
            font-size: 16px;
            color: #e0e0e0;
            margin: 0 0 10px;
            height: 40px;
            overflow: hidden;
        }
        .product-rating {
            color: #f1c40f;
            font-size: 14px;
            margin-bottom: 8px;
        }
        .rating-count {
            color: #888;
            font-size: 12px;
        }
        .product-price {
            margin-bottom: 15px;
        }
        .current-price {
            font-size: 18px;
            font-weight: bold;
            color: #fff;
        }
        .original-price {
            font-size: 14px;
            color: #888;
            text-decoration: line-through;
            margin-left: 8px;
        }
        .add-to-cart {
            width: 100%;
            background: #9b59b6;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }
        .add-to-cart:hover {
            background: #8e44ad;
        }
        .tips-button {
            background-color: #9b59b6;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 20px;
            transition: background-color 0.3s;
        }
        .tips-button:hover {
            background-color: #8e44ad;
        }
        .tips-form {
            display: none;
            background: #2c2c2c;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        .tips-form h2 {
            color: #00b7eb;
            margin-top: 0;
        }
        .tips-form label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
            color: #e0e0e0;
        }
        .tips-form input[type="text"],
        .tips-form select,
        .tips-form textarea,
        .tips-form input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #444;
            border-radius: 4px;
            background: #333;
            color: #e0e0e0;
        }
        .tips-form textarea {
            height: 120px;
            resize: vertical;
        }
        .tips-form input[type="file"] {
            margin-bottom: 10px;
            color: #e0e0e0;
        }
        .tips-form button {
            background-color: #e74c3c;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .tips-form button:hover {
            background-color: #c0392b;
        }
        .success-message {
            color: #2ecc71;
            font-weight: bold;
            display: none;
            margin-top: 10px;
        }
        .error-message {
            color: #e74c3c;
            font-weight: bold;
            display: none;
            margin-top: 10px;
        }
        @media (max-width: 600px) {
            .featured-games,
            .product-grid {
                grid-template-columns: 1fr;
            }
            .product-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>GameNexus</h1>
    </div>

    <!-- Featured Games Section -->
    <div class="featured-games">
        <div class="featured-game">
            <img src="https://gamingready.cz/wp-content/uploads/2017/12/video-post-1250x760.jpg" alt="Egypt and World">
            <div class="featured-game-content">
                <h3>Egypt and World</h3>
                <p>Explore ancient civilizations in this epic adventure game. Uncover hidden treasures and solve mysterious puzzles.</p>
            </div>
        </div>
        <div class="featured-game">
            <img src="https://th.bing.com/th/id/OIP.d2pn_YeOfC5tjlqLFBoXKwHaHa?cb=iwc2&w=626&h=626&rs=1&pid=ImgDetMain" alt="Return of the Cars">
            <div class="featured-game-content">
                <h3>Return of the Cars</h3>
                <p>The ultimate racing experience with hyper-realistic physics and stunning graphics. Compete in global tournaments.</p>
            </div>
        </div>
        <div class="featured-game">
            <img src="https://wallpapercave.com/wp/wp6022521.jpg" alt="Cyberpunk 2077">
            <div class="featured-game-content">
                <h3>Cyberpunk 2077</h3>
                <p>Night City awaits in this futuristic RPG. Experience the complete edition with all DLCs and enhancements.</p>
            </div>
        </div>
        <div class="featured-game">
            <img src="https://img.game8.co/3890254/04a85d63b99e4acba949877bb76c885f.png/show" alt="GTA 5">
            <div class="featured-game-content">
                <h3>GTA 5</h3>
                <p>The legendary open-world game gets a next-gen upgrade with improved visuals and expanded online content.</p>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Gaming Products Section -->
        <div class="products-section">
            <h2 class="section-title">Featured Gaming Products</h2>
            <div class="product-grid">
                <!-- Gaming Console 1 -->
                <div class="product-card">
                    <div class="product-badge">Hot</div>
                    <img src="https://cdn.wccftech.com/wp-content/uploads/2023/12/Sony-PS5-Pro-PlayStation-5-Pro.jpg" alt="PS5 Pro" class="product-image">
                    <div class="product-info">
                        <h3 class="product-title">PlayStation 5 Pro</h3>
                        <div class="product-rating">
                            ★★★★★ <span class="rating-count">(428)</span>
                        </div>
                        <div class="product-price">
                            <span class="current-price">$699.99</span>
                            <span class="original-price">$799.99</span>
                        </div>
                        <button class="add-to-cart">Add to Cart</button>
                    </div>
                </div>

                <!-- Gaming Console 2 -->
                <div class="product-card">
                    <img src="https://assets.vg247.com/current/2019/12/xbox-series-x-promo-image-1-1.jpg" alt="Xbox Series X" class="product-image">
                    <div class="product-info">
                        <h3 class="product-title">Xbox Series X</h3>
                        <div class="product-rating">
                            ★★★★☆ <span class="rating-count">(392)</span>
                        </div>
                        <div class="product-price">
                            <span class="current-price">$549.99</span>
                        </div>
                        <button class="add-to-cart">Add to Cart</button>
                    </div>
                </div>

                <!-- Gaming Laptop -->
                <div class="product-card">
                    <div class="product-badge">New</div>
                    <img src="https://aedkit.com/wp-content/uploads/2024/04/Alienware-m16-Gaming-Laptop-3.jpg" alt="Alienware m16" class="product-image">
                    <div class="product-info">
                        <h3 class="product-title">Alienware m16 Gaming Laptop</h3>
                        <div class="product-rating">
                            ★★★★★ <span class="rating-count">(156)</span>
                        </div>
                        <div class="product-price">
                            <span class="current-price">$2,499.99</span>
                            <span class="original-price">$2,799.99</span>
                        </div>
                        <button class="add-to-cart">Add to Cart</button>
                    </div>
                </div>

                <!-- Gaming Headset -->
                <div class="product-card">
                    <img src="https://th.bing.com/th/id/OIP.nMOHqSeOHrQ4wq2_NXzqYgHaHa?cb=iwc2&rs=1&pid=ImgDetMain" alt="SteelSeries Arctis Pro" class="product-image">
                    <div class="product-info">
                        <h3 class="product-title">SteelSeries Arctis Pro</h3>
                        <div class="product-rating">
                            ★★★★☆ <span class="rating-count">(874)</span>
                        </div>
                        <div class="product-price">
                            <span class="current-price">$179.99</span>
                            <span class="original-price">$199.99</span>
                        </div>
                        <button class="add-to-cart">Add to Cart</button>
                    </div>
                </div>

                <!-- Gaming Mouse -->
                <div class="product-card">
                    <div class="product-badge">Sale</div>
                    <img src="https://tpucdn.com/review/razer-deathadder-v3/images/title.jpg" alt="Razer DeathAdder V3" class="product-image">
                    <div class="product-info">
                        <h3 class="product-title">Razer DeathAdder V3</h3>
                        <div class="product-rating">
                            ★★★★★ <span class="rating-count">(1,024)</span>
                        </div>
                        <div class="product-price">
                            <span class="current-price">$69.99</span>
                            <span class="original-price">$89.99</span>
                        </div>
                        <button class="add-to-cart">Add to Cart</button>
                    </div>
                </div>

                <!-- Gaming Keyboard -->
                <div class="product-card">
                    <img src="https://th.bing.com/th/id/R.ba3af9165f44e86c46fbcde3935253ab?rik=mx4QyjX0ZxdetQ&pid=ImgRaw&r=0" alt="Corsair K100" class="product-image">
                    <div class="product-info">
                        <h3 class="product-title">Corsair K100 RGB</h3>
                        <div class="product-rating">
                            ★★★★☆ <span class="rating-count">(632)</span>
                        </div>
                        <div class="product-price">
                            <span class="current-price">$199.99</span>
                        </div>
                        <button class="add-to-cart">Add to Cart</button>
                    </div>
                </div>

                <!-- Gaming Chair -->
                <div class="product-card">
                    <div class="product-badge">Best Seller</div>
                    <img src="https://mensgear.b-cdn.net/wp-content/uploads/2021/07/Secretlab-TITAN-Evo-2022-2.png" alt="Secretlab Titan Evo" class="product-image">
                    <div class="product-info">
                        <h3 class="product-title">Secretlab Titan Evo 2023</h3>
                        <div class="product-rating">
                            ★★★★★ <span class="rating-count">(2,145)</span>
                        </div>
                        <div class="product-price">
                            <span class="current-price">$549.00</span>
                        </div>
                        <button class="add-to-cart">Add to Cart</button>
                    </div>
                </div>

                <!-- Gaming Monitor -->
                <div class="product-card">
                    <img src="https://www.mall.cz/i/52177060/1310/2000" alt="LG UltraGear 27GN950" class="product-image">
                    <div class="product-info">
                        <h3 class="product-title">LG UltraGear 27GN950</h3>
                        <div class="product-rating">
                            ★★★★★ <span class="rating-count">(789)</span>
                        </div>
                        <div class="product-price">
                            <span class="current-price">$899.99</span>
                            <span class="original-price">$999.99</span>
                        </div>
                        <button class="add-to-cart">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gaming Tips Button -->
        <button class="tips-button" onclick="toggleTipsForm()">Gaming Tips</button>

        <!-- Tips Submission Form -->
        <div class="tips-form" id="tipsForm">
            <h2>Submit Your Gaming Tips</h2>
            <form id="tipsFormData" enctype="multipart/form-data" method="POST" action="blog.php">
                <label for="tipTitle">Tip Title</label>
                <input type="text" id="tipTitle" name="tipTitle" required>

                <label for="authorName">Author Name</label>
                <input type="text" id="authorName" name="authorName" placeholder="Your name or alias">

                <label for="gameSelect">Select Game</label>
                <select id="gameSelect" name="gameSelect" required>
                    <option value="" disabled selected>Select a game</option>
                    <option value="Call of Duty">Call of Duty</option>
                    <option value="League of Legends">League of Legends</option>
                    <option value="Fortnite">Fortnite</option>
                    <option value="Minecraft">Minecraft</option>
                    <option value="Valorant">Valorant</option>
                    <option value="Egypt and World">Egypt and World</option>
                    <option value="Return of the Cars">Return of the Cars</option>
                    <option value="Cyberpunk 2077">Cyberpunk 2077</option>
                    <option value="GTA 5">GTA 5</option>
                </select>

                <label for="tipCategory">Tip Category</label>
                <select id="tipCategory" name="tipCategory">
                    <option value="" disabled selected>Select a category</option>
                    <option value="Beginner">Beginner</option>
                    <option value="Intermediate">Intermediate</option>
                    <option value="Advanced">Advanced</option>
                    <option value="Pro">Pro</option>
                </select>

                <label for="tips">Your Tips</label>
                <textarea id="tips" name="tips" required></textarea>

                <label for="readingTime">Estimated Reading Time (minutes)</label>
                <input type="number" id="readingTime" name="readingTime" min="1" placeholder="e.g., 5">

                <label for="media">Add Media (Image/Video)</label>
                <input type="file" id="media" name="media" accept="image/*,video/*">

                <button type="submit">Submit Tips</button>
            </form>
            <div class="success-message" id="successMessage">Tips submitted successfully!</div>
            <div class="error-message" id="errorMessage">Error submitting tips. Please try again.</div>
        </div>
    </div>

    <script>
        function toggleTipsForm() {
            const form = document.getElementById('tipsForm');
            form.style.display = form.style.display === 'block' ? 'none' : 'block';
        }

        // Show success or error message based on PHP outcome
        <?php if (isset($success)) { ?>
            document.getElementById('<?php echo $success ? "successMessage" : "errorMessage"; ?>').style.display = 'block';
            setTimeout(() => {
                document.getElementById('<?php echo $success ? "successMessage" : "errorMessage"; ?>').style.display = 'none';
            }, 3000);
        <?php } ?>
    </script>

    <?php
    include 'db_conn.php';

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tipTitle = $_POST['tipTitle'];
        $authorName = $_POST['authorName'] ?? '';
        $gameSelect = $_POST['gameSelect'];
        $tipCategory = $_POST['tipCategory'] ?? '';
        $tips = $_POST['tips'];
        $readingTime = !empty($_POST['readingTime']) ? (int)$_POST['readingTime'] : null;
        $mediaPath = '';

        // Handle file upload
        if (isset($_FILES['media']) && $_FILES['media']['error'] == 0) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $mediaPath = $uploadDir . basename($_FILES['media']['name']);
            if (!move_uploaded_file($_FILES['media']['tmp_name'], $mediaPath)) {
                $mediaPath = '';
            }
        }

        // Insert into database
        try {
            $stmt = $conn->prepare("INSERT INTO tips (tip_title, author_name, game, tip_category, tips, reading_time, media_path) VALUES (:tip_title, :author_name, :game, :tip_category, :tips, :reading_time, :media_path)");
            $stmt->bindParam(':tip_title', $tipTitle);
            $stmt->bindParam(':author_name', $authorName);
            $stmt->bindParam(':game', $gameSelect);
            $stmt->bindParam(':tip_category', $tipCategory);
            $stmt->bindParam(':tips', $tips);
            $stmt->bindParam(':reading_time', $readingTime, PDO::PARAM_INT);
            $stmt->bindParam(':media_path', $mediaPath);
            $stmt->execute();
            $success = true;
        } catch(PDOException $e) {
            $success = false;
            // For debugging, you can uncomment the next line
            // echo "Error: " . $e->getMessage();
        }
    }
    ?>
</body>
</html>