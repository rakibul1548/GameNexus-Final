<?php
// No PHP logic needed since only the login link is updated
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameNexus</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="globle.css">
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Inline CSS for Chatbot Button and GameZone Chatbot -->
    <style>
        /* Ensure chatbot styles are specific to avoid overrides */
        .gamenexus-chatbot .chatbot-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #6e48aa, #9d50bb);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 5px 20px rgba(110, 72, 170, 0.4);
            z-index: 9999; /* High z-index to stay above other elements */
            transition: transform 0.3s ease;
        }

        .gamenexus-chatbot .chatbot-btn:hover {
            transform: scale(1.1);
        }

        .gamenexus-chatbot .chatbot-btn i {
            font-size: 1.5rem;
        }

        .gamenexus-chatbot .cart-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #f72585;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 12px;
        }

        /* Root variables for consistency */
        .gamenexus-chatbot {
            --primary: #6e48aa;
            --secondary: #9d50bb;
            --dark: #1a1a2e;
            --light: #f8f9fa;
            --success: #4cc9f0;
            --warning: #f8961e;
            --danger: #f72585;
        }

        .gamenexus-chatbot .chatbot-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 380px;
            height: 500px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 9998;
        }

        .gamenexus-chatbot .chatbot-container.active {
            transform: translateY(0);
            opacity: 1;
        }

        .gamenexus-chatbot .chatbot-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .gamenexus-chatbot .chatbot-header h3 {
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .gamenexus-chatbot .chatbot-header h3 i {
            font-size: 1.2rem;
        }

        .gamenexus-chatbot .close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
        }

        .gamenexus-chatbot .chatbot-body {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            background-color: var(--light);
        }

        .gamenexus-chatbot .message {
            margin-bottom: 15px;
            max-width: 80%;
            padding: 10px 15px;
            border-radius: 18px;
            line-height: 1.4;
            position: relative;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .gamenexus-chatbot .bot-message {
            background: white;
            color: #333;
            border-bottom-left-radius: 5px;
            align-self: flex-start;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .gamenexus-chatbot .user-message {
            background: var(--primary);
            color: white;
            border-bottom-right-radius: 5px;
            align-self: flex-end;
            margin-left: auto;
        }

        .gamenexus-chatbot .quick-replies {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }

        .gamenexus-chatbot .quick-reply {
            background: rgba(255, 255, 255, 0.986);
            border: 1px solid #ddd;
            border-radius: 20px;
            padding: 5px 12px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .gamenexus-chatbot .quick-reply:hover {
            background: #f0f0f0;
        }

        .gamenexus-chatbot .chatbot-footer {
            padding: 15px;
            border-top: 1px solid #eee;
            background: white;
        }

        .gamenexus-chatbot .chatbot-input {
            display: flex;
            gap: 10px;
        }

        .gamenexus-chatbot #user-input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 30px;
            outline: none;
            transition: border 0.3s ease;
        }

        .gamenexus-chatbot #user-input:focus {
            border-color: var(--primary);
        }

        .gamenexus-chatbot #send-btn {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .gamenexus-chatbot #send-btn:hover {
            background: var(--secondary);
        }

        .gamenexus-chatbot .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            margin-top: 10px;
        }

        .gamenexus-chatbot .product-image {
            height: 120px;
            background-size: cover;
            background-position: center;
        }

        .gamenexus-chatbot .product-info {
            padding: 10px;
        }

        .gamenexus-chatbot .product-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .gamenexus-chatbot .product-price {
            color: var(--primary);
            font-weight: bold;
            margin-bottom: 8px;
        }

        .gamenexus-chatbot .product-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.8rem;
            width: 100%;
            transition: background 0.3s ease;
        }

        .gamenexus-chatbot .product-btn:hover {
            background: var(--secondary);
        }

        .gamenexus-chatbot .product-btn.added {
            background: var(--success);
        }

        .gamenexus-chatbot .typing-indicator {
            display: flex;
            padding: 10px 15px;
            background: white;
            border-radius: 18px;
            width: fit-content;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
        }

        .gamenexus-chatbot .typing-dot {
            width: 8px;
            height: 8px;
            background: #ccc;
            border-radius: 50%;
            margin: 0 2px;
            animation: typingAnimation 1.4s infinite ease-in-out;
        }

        .gamenexus-chatbot .typing-dot:nth-child(1) { animation-delay: 0s; }
        .gamenexus-chatbot .typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .gamenexus-chatbot .typing-dot:nth-child(3) { animation-delay: 0.4s; }

        @keyframes typingAnimation {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-5px); }
        }

        .gamenexus-chatbot .system-message {
            background: #e9ecef;
            color: #495057;
            font-size: 0.8rem;
            text-align: center;
            padding: 8px;
            border-radius: 5px;
            margin: 10px 0;
        }

        .gamenexus-chatbot .cart-summary {
            background: white;
            border-radius: 10px;
            padding: 10px;
            margin-top: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .gamenexus-chatbot .cart-item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }

        .gamenexus-chatbot .cart-total {
            font-weight: bold;
            text-align: right;
            margin-top: 5px;
        }

        .gamenexus-chatbot .checkout-btn {
            background: var(--success);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            width: 100%;
            transition: background 0.3s ease;
        }

        .gamenexus-chatbot .checkout-btn:hover {
            background: #3aa8d8;
        }

        .gamenexus-chatbot .cart-empty {
            color: #777;
            text-align: center;
            padding: 10px;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .gamenexus-chatbot .chatbot-btn {
                width: 50px;
                height: 50px;
            }
            .gamenexus-chatbot .chatbot-btn i {
                font-size: 1.2rem;
            }
            .gamenexus-chatbot .chatbot-container {
                width: 95%;
                height: 80vh;
            }
        }
    </style>
</head>

<body>
    <header>
        <nav class="container">
            <div class="nav1 flex">
                <div class="icons flex">
                    <i class="fa-solid fa-magnifying-glass"></i> 
                    <i class="fa-brands fa-facebook-f"></i>
                    <i class="fa-brands fa-twitter"></i>
                    <i class="fa-brands fa-google-plus-g"></i>
                    <i class="fa-brands fa-youtube"></i>
                    <i class="fa-brands fa-instagram"></i>
                    <i class="fa-brands fa-twitch"></i>
                </div>
                <div class="lsbtn flex">
                    <button type="button"><a href="pages/login.php">LOGIN</a></button>
                    <button type="button"><a href="pages/Admin.php">ADMIN</a></button>
                    <button type="button"><a href="pages/signup.php">SIGN UP</a></button>
                </div>
            </div>
            <div class="mainnav flex">
                <div class="logo">
                    <h1><a href="/">GameNexus<span>A Gamers Store</span></a></h1>
                </div>
                <div class="navlist">
                    <ul class="flex">
                        <li><a href="pages/home.html">Home</a></li>
                        <li><a href="pages/aboutus.html">About</a></li>
                        <li><a href="pages/blog.php">Blog</a></li>
                        <li><a href="pages/catalogsystem.html">Games</a></li>
                        <li><a href="/">Esport</a></li>
                        <li><a href="pages/gamedemos.html">Demo<i class="fa-solid fa-angle-down"></i></a></li>
                        <li><a href="pages/checker.html">Checker</a></li>
                        <li><a href="pages/game&gear.html">Gear</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="headercont container flex">
            <div class="labels flex">
                <div class="flex">
                    <i class="fa-solid fa-tv"></i>
                    <h3 class="flex">
                        <a href="/">PLAYSTATION 4</a>
                        <span>,</span>
                        <a href="/">STEAM</a>
                    </h3>
                </div>
                <div class="flex">
                    <i class="fa-solid fa-tags"></i>
                    <h3 class="flex">
                        <a href="/">ACTION</a>
                        <span>,</span>
                        <a href="/">ADVENTURE</a>
                    </h3>
                </div>
            </div>
            <h1 id="header_title">EGYPT AND WORLD</h1>
            <p>Step into the heart of the ancient world with Egypt, a groundbreaking real-time strategy game 
                that transports you to the golden age of Egypt. From the fertile banks of the Nile to the
                harsh sands of the desert, every decision shapes your journey to greatness.</p>
            <div class="headbtn flex">
                <a href="pages/details.html" id="d_btn1"><button class="button type1"></button></a>
                <a href="pages/addcart.html" id="d_btn1"><button class="button type2"></button></a>
            </div>
        </div>
        <div class="dots flex">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
    </header>

    <main>
        <div class="threebox container flex">
            <div class="cbox">
                <img src="img/box-1-bg.jpg" alt="title">
                <div class="cboxde">
                    <h5>OUR ALL GAMES</h5>
                    <h2>GameNexus</h2>
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Alias harum ipsa modi soluta nam error
                        deleniti neque impedit earum omnis.</p>
                    <a href="pages/catalogsystem.html">All GAMES</a>
                </div>
            </div>
            <div class="cbox">
                <img src="img/box-2-bg.jpg" alt="title">
                <div class="cboxde">
                    <h5>OUR ALL GAMES</h5>
                    <h2>GameNexus</h2>
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Alias harum ipsa modi soluta nam error
                        deleniti neque impedit earum omnis.</p>
                    <a href="pages/catalogsystem.html">All GAMES</a>
                </div>
            </div>
            <div class="cbox">
                <img src="img/box-3-bg.jpg" alt="title">
                <div class="cboxde">
                    <h5>OUR ALL GAMES</h5>
                    <h2>GameNexus</h2>
                    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Alias harum ipsa modi soluta nam error
                        deleniti neque impedit earum omnis.</p>
                    <a href="pages/catalogsystem.html">All GAMES</a>
                </div>
            </div>
        </div>
        <div class="container filter flex">
            <button class="active">ALL</button>
            <button>PLAYSTATION 4</button>
            <button>UPLAY</button>
            <button>XBOX ONE</button>
            <button>ORIGIN</button>
            <button>STEAM</button>
        </div>
        <div class="gamecards container flex">
            <div class="card">
                <div class="cardimg">
                    <a href="pages/egypt&world.html">
                        <img src="img/egypt and world.jpg" alt="Egypt and World">
                    </a>
                    <div class="tegs">
                        <a href="/">ACTION</a>
                        <a href="/">ADVENTURE</a>
                        <i class="fa-solid fa-tags"></i>
                    </div>
                </div>
                <div class="cardinfo">
                    <h2>EGYPT AND WORLD</h2>
                    <div class="playteg flex">
                        <i class="fa-solid fa-tv"></i>
                        <h3 class="flex">
                            <a href="/">PLAYSTATION 4</a>
                            <span>,</span>
                            <a href="/">STEAM</a>
                        </h3>
                    </div>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ullam, sequi.</p>
                </div>
            </div>
            <div class="card">
                <div class="cardimg">
                    <a href="pages/spacewars.html">
                        <img src="img/spacewars.jpg" alt="Space Wars">
                    </a>
                    <div class="tegs">
                        <a href="/">ADVENTURE</a>
                        <i class="fa-solid fa-tags"></i>
                    </div>
                </div>
                <div class="cardinfo">
                    <h2>SPACE WARS</h2>
                    <div class="playteg flex">
                        <i class="fa-solid fa-tv"></i>
                        <h3 class="flex">
                            <a href="/">STEAM</a>
                            <span>,</span>
                            <a href="/">XBOX ONE</a>
                        </h3>
                    </div>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ullam, sequi.</p>
                </div>
            </div>
            <div class="card">
                <div class="cardimg">
                    <a href="pages/returncars.html">
                        <img src="img/return of the cars.jpg" alt="Return of the Cars">
                    </a>
                    <div class="tegs">
                        <a href="/">RACING</a>
                        <a href="/">ADVENTURE</a>
                        <i class="fa-solid fa-tags"></i>
                    </div>
                </div>
                <div class="cardinfo">
                    <h2>RETURN OF THE CARS</h2>
                    <div class="playteg flex">
                        <i class="fa-solid fa-tv"></i>
                        <h3 class="flex">
                            <a href="/">UPLAY</a>
                            <span>,</span>
                            <a href="/">XBOX ONE</a>
                        </h3>
                    </div>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ullam, sequi.</p>
                </div>
            </div>
            <div class="card">
                <div class="cardimg">
                    <a href="pages/cyberpunk.html">
                        <img src="img/lords-of-the-fallen-warrior.jpg" alt="Lords of the Fallen Warrior">
                    </a>
                    <div class="tegs">
                        <a href="/">ACTION</a>
                        <a href="/">ADVENTURE</a>
                        <i class="fa-solid fa-tags"></i>
                    </div>
                </div>
                <div class="cardinfo">
                    <h2>LORDS OF THE FALLEN WARRIOR</h2>
                    <div class="playteg flex">
                        <i class="fa-solid fa-tv"></i>
                        <h3 class="flex">
                            <a href="/">PLAYSTATION 4</a>
                            <span>,</span>
                            <a href="/">STEAM</a>
                        </h3>
                    </div>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ullam, sequi.</p>
                </div>
            </div>
            <div class="card">
                <div class="cardimg">
                    <a href="pages/cyberpunk.html">
                        <img src="img/Cyberpunk-2077.webp" alt="cyberpunk 77">
                    </a>
                    <div class="tegs">
                        <a href="/">ADVENTURE</a>
                        <i class="fa-solid fa-tags"></i>
                    </div>
                </div>
                <div class="cardinfo">
                    <h2>CYBERPUNK</h2>
                    <div class="playteg flex">
                        <i class="fa-solid fa-tv"></i>
                        <h3 class="flex">
                            <a href="/">PLAYSTATION 4</a>
                            <span>,</span>
                            <a href="/">STEAM</a>
                        </h3>
                    </div>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ullam, sequi.</p>
                </div>
            </div>
            <div class="card">
                <div class="cardimg">
                    <a href="pages/egypt&world.html">
                        <img src="img/the-witcher-1.jpg" alt="The Witcher">
                    </a>
                    <div class="tegs">
                        <a href="/">ADVENTURE</a>
                        <i class="fa-solid fa-tags"></i>
                    </div>
                </div>
                <div class="cardinfo">
                    <h2>THE WITCHER</h2>
                    <div class="playteg flex">
                        <i class="fa-solid fa-tv"></i>
                        <h3 class="flex">
                            <a href="/">PLAYSTATION 4</a>
                            <span>,</span>
                            <a href="/">ORIGIN</a>
                        </h3>
                    </div>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ullam, sequi.</p>
                </div>
            </div>
            <div class="card">
                <div class="cardimg">
                    <a href="pages/gta5.html">
                        <img src="img/gta-5.jpg" alt="GTA 5">
                    </a>
                    <div class="tegs">
                        <a href="/">ACTION</a>
                        <a href="/">RACING</a>
                        <i class="fa-solid fa-tags"></i>
                    </div>
                </div>
                <div class="cardinfo">
                    <h2>GTA 5</h2>
                    <div class="playteg flex">
                        <i class="fa-solid fa-tv"></i>
                        <h3 class="flex">
                            <a href="/">PLAYSTATION 4</a>
                            <span>,</span>
                            <a href="/">STEAM</a>
                        </h3>
                    </div>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ullam, sequi.</p>
                </div>
            </div>
            <div class="card">
                <div class="cardimg">
                    <a href="/">
                        <img src="img/uncharted-4.jpg" alt="Uncharted 4">
                    </a>
                    <div class="tegs">
                        <a href="/">ADVENTURE</a>
                        <i class="fa-solid fa-tags"></i>
                    </div>
                </div>
                <div class="cardinfo">
                    <h2>UNCHARTED 4: THIEF'S END</h2>
                    <div class="playteg flex">
                        <i class="fa-solid fa-tv"></i>
                        <h3 class="flex">
                            <a href="/">PLAYSTATION 4</a>
                            <span>,</span>
                            <a href="/">STEAM</a>
                        </h3>
                    </div>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ullam, sequi.</p>
                </div>
            </div>
        </div>
        <div class="allgamebtn flex container">
            <a href="pages/catalogsystem.html">
                <button class="my-button">ALL GAMES</button>
            </a>
        </div>
        <div class="gamesearch flex">
            <div class="container flex">
                <h2>GAME SEARCH</h2>
                <form action="submit" class="flex">
                    <input type="text" placeholder="Keyword">
                    <select name="platform">
                        <option value="">Platform</option>
                        <option value="Mac OS X">Mac OS X</option>
                        <option value="Microsoft Windows">Microsoft Windows</option>
                        <option value="Origin">Origin</option>
                        <option value="Playstation 4">Playstation 4</option>
                        <option value="Steam">Steam</option>
                    </select>
                    <select name="genre">
                        <option value="">Genre</option>
                        <option value="ACTION">ACTION</option>
                        <option value="ADVENTURE">ADVENTURE</option>
                        <option value="RACING">RACING</option>
                        <option value="SPORTS">SPORTS</option>
                        <option value="SIMULATION">SIMULATION</option>
                    </select>
                    <select name="language">
                        <option value="">Language</option>
                        <option value="English">English</option>
                        <option value="German">German</option>
                    </select>
                    <button type="submit">SEARCH</button>
                </form>
            </div>
        </div>
        <div class="newrelesed flex">
            <div class="container">
                <div class="newretitle">
                    <h2>Forza Horizon 5</h2>
                    <h2>Is Released!</h2>
                </div>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Vel, debitis dolorum nostrum laudantium odio
                    blanditiis repudiandae autem. Culpa cum numquam voluptates consequatur saepe similique? Ea
                    repudiandae quo culpa corporis officiis?</p>
                <div class="flex">
                    <a href="pages/details.html">Read More</a>
                    <a href="pages/addcart.html">Buy Now</a>
                </div>
            </div>
        </div>
        <div class="featuredgames container flex">
            <h2>Featured <span>Games</span></h2>
            <div class="fgamescards flex">
                <div class="fcard">
                    <img src="img/spacewars.jpg" alt="Space Wars">
                    <div class="fcarddetails">
                        <h2>Space Wars</h2>
                        <div class="tegs2 flex">
                            <div class="teg flex">
                                <i class="fa-solid fa-tv"></i>
                                <h3 class="flex">
                                    <a href="/">Steam</a>
                                    <a href="/">Xbox One</a>
                                </h3>
                            </div>
                            <div class="teg flex">
                                <i class="fa-solid fa-tags"></i>
                                <h3 class="flex">
                                    <a href="/">Adventure</a>
                                    <a href="/">FPS</a>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="fhovercont">
                        <h2>Space Wars</h2>
                    </div>
                </div>
                <div class="fcard">
                    <img src="img/Cyberpunk-2077.webp" alt="Cyberpunk 2077">
                    <div class="fcarddetails">
                        <h2>Cyberpunk</h2>
                        <div class="tegs2 flex">
                            <div class="teg flex">
                                <i class="fa-solid fa-tv"></i>
                                <h3 class="flex">
                                    <a href="/">Steam</a>
                                    <a href="/">Xbox One</a>
                                </h3>
                            </div>
                            <div class="teg flex">
                                <i class="fa-solid fa-tags"></i>
                                <h3 class="flex">
                                    <a href="/">Adventure</a>
                                    <a href="/">FPS</a>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="fhovercont">
                        <h2>Cyberpunk</h2>
                    </div>
                </div>
                <div class="fcard">
                    <img src="img/the-witcher-1.jpg" alt="The Witcher">
                    <div class="fcarddetails">
                        <h2>The Witcher 3</h2>
                        <div class="tegs2 flex">
                            <div class="teg flex">
                                <i class="fa-solid fa-tv"></i>
                                <h3 class="flex">
                                    <a href="/">Steam</a>
                                    <a href="/">Xbox One</a>
                                </h3>
                            </div>
                            <div class="teg flex">
                                <i class="fa-solid fa-tags"></i>
                                <h3 class="flex">
                                    <a href="/">Adventure</a>
                                    <a href="/">RPG</a>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="fhovercont">
                        <h2>The Witcher 3</h2>
                    </div>
                </div>
                <div class="fcard">
                    <img src="img/gta-5.jpg" alt="GTA 5">
                    <div class="fcarddetails">
                        <h2>GTA 5</h2>
                        <div class="tegs2 flex">
                            <div class="teg flex">
                                <i class="fa-solid fa-tv"></i>
                                <h3 class="flex">
                                    <a href="/">Steam</a>
                                    <a href="/">Xbox One</a>
                                </h3>
                            </div>
                            <div class="teg flex">
                                <i class="fa-solid fa-tags"></i>
                                <h3 class="flex">
                                    <a href="/">Action</a>
                                    <a href="/">Racing</a>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="fhovercont">
                        <h2>GTA 5</h2>
                    </div>
                </div>
                <div class="fcard">
                    <img src="img/uncharted-4.jpg" alt="Uncharted 4">
                    <div class="fcarddetails">
                        <h2>Uncharted 4</h2>
                        <div class="tegs2 flex">
                            <div class="teg flex">
                                <i class="fa-solid fa-tv"></i>
                                <h3 class="flex">
                                    <a href="/">Steam</a>
                                    <a href="/">Xbox One</a>
                                </h3>
                            </div>
                            <div class="teg flex">
                                <i class="fa-solid fa-tags"></i>
                                <h3 class="flex">
                                    <a href="/">Adventure</a>
                                    <a href="/">Action</a>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="fhovercont">
                        <h2>Uncharted 4</h2>
                    </div>
                </div>
                <div class="fcard">
                    <img src="img/return of the cars.jpg" alt="Return of the Cars">
                    <div class="fcarddetails">
                        <h2>Return of the Cars</h2>
                        <div class="tegs2 flex">
                            <div class="teg flex">
                                <i class="fa-solid fa-tv"></i>
                                <h3 class="flex">
                                    <a href="/">Steam</a>
                                    <a href="/">Xbox One</a>
                                </h3>
                            </div>
                            <div class="teg flex">
                                <i class="fa-solid fa-tags"></i>
                                <h3 class="flex">
                                    <a href="/">Racing</a>
                                    <a href="/">Adventure</a>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="fhovercont">
                        <h2>Return of the Cars</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="blogandnews flex">
            <div class="container">
                <h2>Blog <span>& News</span></h2>
                <div class="threecards flex">
                    <div class="tcard">
                        <img src="img/blognews1.jpg" alt="Blog News 1">
                        <div class="tcarddetails">
                            <h3>About Space and World</h3>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusantium, magnam?</p>
                            <div class="postby flex">
                                <div class="flex">
                                    <i class="fa-brands fa-centercode"></i>
                                    <h5>Coder</h5>
                                </div>
                                <div class="flex">
                                    <i class="fa-solid fa-folder-open"></i>
                                    <h5>Videos</h5>
                                </div>
                            </div>
                            <div class="posttime flex">
                                <i class="fa-regular fa-clock"></i>
                                <h5>September 11, 2023</h5>
                            </div>
                        </div>
                    </div>
                    <div class="tcard">
                        <img src="img/blognews2.jpg" alt="Blog News 2">
                        <div class="tcarddetails">
                            <h3>New Trailer is Released!</h3>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusantium, magnam?</p>
                            <div class="postby flex">
                                <div class="flex">
                                    <i class="fa-brands fa-centercode"></i>
                                    <h5>Coder</h5>
                                </div>
                                <div class="flex">
                                    <i class="fa-solid fa-folder-open"></i>
                                    <h5>Videos</h5>
                                </div>
                            </div>
                            <div class="posttime flex">
                                <i class="fa-regular fa-clock"></i>
                                <h5>September 09, 2023</h5>
                            </div>
                        </div>
                    </div>
                    <div class="tcard">
                        <img src="img/blognews3.jpg" alt="Blog News 3">
                        <div class="tcarddetails">
                            <h3>Price List of the Games</h3>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusantium, magnam?</p>
                            <div class="postby flex">
                                <div class="flex">
                                    <i class="fa-brands fa-centercode"></i>
                                    <h5>Coder</h5>
                                </div>
                                <div class="flex">
                                    <i class="fa-solid fa-folder-open"></i>
                                    <h5>Videos</h5>
                                </div>
                            </div>
                            <div class="posttime flex">
                                <i class="fa-regular fa-clock"></i>
                                <h5>September 05, 2023</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="newrelesed flex aboutus">
            <div class="container flex">
                <div class="aboutusdetails">
                    <div class="newretitle">
                        <h2>About the</h2>
                        <h2>GameNexus</h2>
                    </div>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Vel, debitis dolorum nostrum laudantium
                        odio blanditiis repudiandae autem. Culpa cum numquam voluptates consequatur saepe similique? Ea
                        repudiandae quo culpa corporis officiis?</p>
                    <div class="flex">
                        <a href="pages/aboutus.html">Read More</a>
                    </div>
                </div>
                <div class="aboutusimg">
                    <img src="img/Capture.PNG" alt="About GameNexus">
                </div>
            </div>
        </div>
        <div class="gameshop container flex">
            <h2>Game <span>Shop</span></h2>
            <div class="shopcards flex">
                <div class="scard">
                    <img src="img/cpu.png" alt="Gaming CPU">
                    <div class="scarddetails">
                        <h3>Gaming CPU</h3>
                        <h3>$350.00</h3>
                    </div>
                </div>
                <div class="scard">
                    <img src="img/headphone.jpg" alt="Gaming Headphone">
                    <div class="scarddetails">
                        <h3>Gaming Headphone</h3>
                        <h3>$350.00</h3>
                    </div>
                </div>
                <div class="scard">
                    <img src="img/mouse.png" alt="Gaming Mouse">
                    <div class="scarddetails">
                        <h3>Gaming Mouse</h3>
                        <h3>$352.00</h3>
                    </div>
                </div>
            </div>
            <div class="allgamebtn flex container" style="display: flex; justify-content: center;">
                <a href="pages/accessory.html">
                    <button class="my-button">ALL ACCESSORIES</button>
                </a>
            </div>
        </div>
        <div class="gamesearch flex">
            <div class="container flex">
                <h2>Newsletter</h2>
                <form action="submit" class="flex">
                    <input type="email" placeholder="Your email address" required>
                    <input type="text" placeholder="First Name">
                    <input type="text" placeholder="Last Name">
                    <button type="submit">SUBMIT</button>
                </form>
            </div>
        </div>
        <div class="socialicons flex container">
            <div class="social">
                <i class="fa-brands fa-facebook-f"></i>
                <h3>Facebook</h3>
            </div>
            <div class="social">
                <i class="fa-brands fa-twitter"></i>
                <h3>Twitter</h3>
            </div>
            <div class="social">
                <i class="fa-brands fa-google-plus-g"></i>
                <h3>Google Plus</h3>
            </div>
            <div class="social">
                <i class="fa-brands fa-youtube"></i>
                <h3>YouTube</h3>
            </div>
            <div class="social">
                <i class="fa-brands fa-instagram"></i>
                <h3>Instagram</h3>
            </div>
            <div class="social">
                <i class="fa-brands fa-twitch"></i>
                <h3>Twitch</h3>
            </div>
        </div>
    </main>

    <footer>
        <section class="footersec container flex">
            <div class="footerh2 flex">
                <h2>About <span>Us</span></h2>
                <h2>Latest <span>News</span></h2>
                <h2>Apps <span>& Platforms</span></h2>
            </div>
            <div class="flex footermenu">
                <div class="faboutus">
                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Repellat doloribus voluptates, possimus quos dolorem animi sit nulla cumque eaque ipsa.</p>
                    <div class="flex">
                        <ul class="flex">
                            <li><a href="/"><i class="fa-solid fa-caret-right"></i>Home</a></li>
                            <li><a href="pages/blog.php"><i class="fa-solid fa-caret-right"></i>Blog</a></li>
                            <li><a href="pages/catalogsystem.html"><i class="fa-solid fa-caret-right"></i>Games</a></li>
                            <li><a href="pages/aboutus.html"><i class="fa-solid fa-caret-right"></i>About</a></li>
                        </ul>
                        <ul class="flex">
                            <li><a href="/"><i class="fa-solid fa-caret-right"></i>Team</a></li>
                            <li><a href="/"><i class="fa-solid fa-caret-right"></i>Community</a></li>
                            <li><a href="/"><i class="fa-solid fa-caret-right"></i>Esport</a></li>
                            <li><a href="/"><i class="fa-solid fa-caret-right"></i>Contact</a></li>
                        </ul>
                    </div>
                </div>
                <div class="latestnews">
                    <div class="fnews flex">
                        <div class="fnewscard flex">
                            <img src="img/blognews1.jpg" alt="News 1">
                            <div class="fnewsdetails">
                                <h4>About Space and World</h4>
                                <p><i class="fa-regular fa-clock"></i><span>September 11, 2023</span></p>
                            </div>
                        </div>
                        <div class="fnewscard flex">
                            <img src="img/blognews2.jpg" alt="News 2">
                            <div class="fnewsdetails">
                                <h4>New Trailer Released</h4>
                                <p><i class="fa-regular fa-clock"></i><span>September 09, 2023</span></p>
                            </div>
                        </div>
                        <div class="fnewscard flex">
                            <img src="img/blognews3.jpg" alt="News 3">
                            <div class="fnewsdetails">
                                <h4>Game Price List</h4>
                                <p><i class="fa-regular fa-clock"></i><span>September 05, 2023</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="appsec">
                    <div class="platform flex">
                        <div class="apps flex">
                            <i class="fa-brands fa-apple"></i>
                            <div class="appde">
                                <h4>Buy now via</h4>
                                <h4>Apple Store</h4>
                            </div>
                        </div>
                        <div class="apps flex">
                            <i class="fa-brands fa-google-play"></i>
                            <div class="appde">
                                <h4>Buy now via</h4>
                                <h4>Google Play</h4>
                            </div>
                        </div>
                        <div class="apps flex">
                            <i class="fa-brands fa-steam"></i>
                            <div class="appde">
                                <h4>Buy now via</h4>
                                <h4>Steam</h4>
                            </div>
                        </div>
                        <div class="apps flex">
                            <i class="fa-brands fa-windows"></i>
                            <div class="appde">
                                <h4>Buy now via</h4>
                                <h4>Windows</h4>
                            </div>
                        </div>
                        <div class="apps flex">
                            <i class="fa-brands fa-amazon"></i>
                            <div class="appde">
                                <h4>Buy now via</h4>
                                <h4>Amazon</h4>
                            </div>
                        </div>
                        <div class="apps flex">
                            <i class="fa-brands fa-paypal"></i>
                            <div class="appde">
                                <h4>Buy now via</h4>
                                <h4>Bkash</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="copyrightsec flex">
            <div class="copyright container flex">
                <div class="flex">
                    <h2>GameNexus<span>A Gamers Store</span></h2>
                    <p>Copyright © 2023 Rakibul - All rights reserved.</p>
                </div>
                <ul class="flex">
                    <li><a href="/" class="factive">Home</a></li>
                    <li><a href="/">Help Center</a></li>
                    <li><a href="/">Contact</a></li>
                    <li><a href="/">Career</a></li>
                    <li><a href="/">Advertise</a></li>
                    <li><a href="/">Terms and Conditions</a></li>
                </ul>
            </div>
        </section>
    </footer>

    <!-- Floating Chatbot Button -->
    <div class="gamenexus-chatbot">
        <div class="chatbot-btn" id="chatbotBtn">
            <i class="fas fa-robot"></i>
            <span class="cart-count" id="cart-count" style="display: none;">0</span>
        </div>

        <!-- GameZone Chatbot Container -->
        <div class="chatbot-container" id="chatbot-container">
            <div class="chatbot-header">
                <h3><i class="fas fa-robot"></i> GameNexus Assistant</h3>
                <button class="close-btn" id="close-btn"><i class="fas fa-times"></i></button>
            </div>
            <div class="chatbot-body" id="chatbot-body">
                <!-- Messages will appear here -->
            </div>
            <div class="chatbot-footer">
                <div class="chatbot-input">
                    <input type="text" id="user-input" placeholder="Ask about games, hardware, or orders..." autocomplete="off">
                    <button id="send-btn"><i class="fas fa-paper-plane"></i></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Inline JavaScript for GameZone Chatbot -->
    <script>
        (function() {
            // Ensure no conflicts with other scripts
            console.log('GameNexus Chatbot initializing...');

            // DOM Elements
            const chatbotBtn = document.getElementById('chatbotBtn');
            const chatbotContainer = document.getElementById('chatbot-container');
            const closeBtn = document.getElementById('close-btn');
            const chatbotBody = document.getElementById('chatbot-body');
            const userInput = document.getElementById('user-input');
            const sendBtn = document.getElementById('send-btn');
            const cartCount = document.getElementById('cart-count');

            // Validate DOM elements
            if (!chatbotBtn || !chatbotContainer || !closeBtn || !chatbotBody || !userInput || !sendBtn || !cartCount) {
                console.error('Chatbot DOM elements missing!');
                return;
            }

            // Shopping cart
            let cart = [];

            // Game database (updated with GameNexus titles)
            const games = [
                { id: 1, name: "Egypt and World", price: 59.99, platform: ["PS4", "Steam"], genre: "Strategy", image: "img/egypt and world.jpg" },
                { id: 2, name: "Space Wars", price: 39.99, platform: ["Steam", "Xbox One"], genre: "Adventure", image: "img/spacewars.jpg" },
                { id: 3, name: "Cyberpunk 2077", price: 49.99, platform: ["PC", "PS5", "Xbox Series X"], genre: "RPG", image: "img/Cyberpunk-2077.webp" },
                { id: 4, name: "GTA 5", price: 29.99, platform: ["PS4", "Steam"], genre: "Action", image: "img/gta-5.jpg" },
                { id: 5, name: "Forza Horizon 5", price: 59.99, platform: ["Xbox Series X", "PC"], genre: "Racing", image: "https://image.api.playstation.com/vulcan/ap/rnd/202109/2300/X1QsrmlNExkpZPAWao1Mcg4R.jpg" },
                { id: 6, name: "Uncharted 4: Thief's End", price: 19.99, platform: ["PS4", "Steam"], genre: "Adventure", image: "img/uncharted-4.jpg" },
                { id: 7, name: "Return of the Cars", price: 49.99, platform: ["Uplay", "Xbox One"], genre: "Racing", image: "img/return of the cars.jpg" },
                { id: 8, name: "The Witcher 3", price: 39.99, platform: ["PS4", "Steam"], genre: "RPG", image: "img/the-witcher-1.jpg" }
            ];

            // Hardware database (normalized prices to USD, assuming 42000 BDT ≈ $350)
            const hardware = [
                { id: 101, name: "NVIDIA RTX 4090", price: 1599.99, type: "GPU", image: "https://m.media-amazon.com/images/I/61uHkIUaUIL._AC_SL1500_.jpg" },
                { id: 102, name: "PlayStation 5", price: 499.99, type: "Console", image: "https://m.media-amazon.com/images/I/51zLZbEVSTL._AC_SL1500_.jpg" },
                { id: 103, name: "Gaming CPU", price: 350.00, type: "CPU", image: "img/cpu.png" },
                { id: 104, name: "Gaming Headphone", price: 350.00, type: "Headset", image: "img/headphone.jpg" },
                { id: 105, name: "Gaming Mouse", price: 352.00, type: "Mouse", image: "img/mouse.png" }
            ];

            // Orders database (mock data)
            const orders = [
                { id: "ORD1001", status: "Shipped", items: ["Egypt and World (PS4)", "PlayStation Plus 12-Month"], total: 109.98, tracking: "UPS #1Z12345E0205271688" },
                { id: "ORD1002", status: "Processing", items: ["NVIDIA RTX 4090"], total: 1599.99, tracking: null }
            ];

            // Initialize chatbot
            let isChatbotOpen = false;

            // Toggle chatbot visibility
            chatbotBtn.addEventListener('click', () => {
                console.log('Chatbot button clicked');
                isChatbotOpen = !isChatbotOpen;
                chatbotContainer.classList.toggle('active', isChatbotOpen);

                if (isChatbotOpen && chatbotBody.children.length === 0) {
                    addBotMessage("Hi there! I'm GameNexus Assistant. How can I help you today? 😊", [
                        "Recommend me a game",
                        "Check my order status",
                        "What PC parts do I need?",
                        "View my cart"
                    ]);
                }
            });

            closeBtn.addEventListener('click', () => {
                console.log('Close button clicked');
                isChatbotOpen = false;
                chatbotContainer.classList.remove('active');
            });

            // Send message on button click
            sendBtn.addEventListener('click', sendMessage);

            // Send message on Enter key
            userInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });

            // Function to send a message
            function sendMessage() {
                const message = userInput.value.trim();
                if (message === '') return;

                console.log('Sending message:', message);
                addUserMessage(message);
                userInput.value = '';

                showTypingIndicator();

                setTimeout(() => {
                    removeTypingIndicator();
                    processUserMessage(message);
                }, 1000 + Math.random() * 2000);
            }

            // Function to show typing indicator
            function showTypingIndicator() {
                const typingIndicator = document.createElement('div');
                typingIndicator.className = 'typing-indicator';
                typingIndicator.innerHTML = `
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                `;
                chatbotBody.appendChild(typingIndicator);
                chatbotBody.scrollTop = chatbotBody.scrollHeight;
            }

            // Function to remove typing indicator
            function removeTypingIndicator() {
                const typingIndicators = document.querySelectorAll('.typing-indicator');
                typingIndicators.forEach(indicator => {
                    chatbotBody.removeChild(indicator);
                });
            }

            // Function to add a user message
            function addUserMessage(text) {
                const messageElement = document.createElement('div');
                messageElement.className = 'message user-message';
                messageElement.textContent = text;
                chatbotBody.appendChild(messageElement);
                chatbotBody.scrollTop = chatbotBody.scrollHeight;
            }

            // Function to add a bot message
            function addBotMessage(text, quickReplies = []) {
                const messageElement = document.createElement('div');
                messageElement.className = 'message bot-message';
                messageElement.innerHTML = text;

                if (quickReplies.length > 0) {
                    const quickRepliesContainer = document.createElement('div');
                    quickRepliesContainer.className = 'quick-replies';

                    quickReplies.forEach(reply => {
                        const quickReply = document.createElement('div');
                        quickReply.className = 'quick-reply';
                        quickReply.textContent = reply;
                        quickReply.addEventListener('click', () => {
                            addUserMessage(reply);
                            showTypingIndicator();
                            setTimeout(() => {
                                removeTypingIndicator();
                                processUserMessage(reply);
                            }, 1000 + Math.random() * 2000);
                        });

                        quickRepliesContainer.appendChild(quickReply);
                    });

                    messageElement.appendChild(quickRepliesContainer);
                }

                chatbotBody.appendChild(messageElement);
                chatbotBody.scrollTop = chatbotBody.scrollHeight;
            }

            // Function to add a product card
            function addProductCard(product) {
                const isInCart = cart.some(item => item.id === product.id);

                const productCard = document.createElement('div');
                productCard.className = 'product-card';
                productCard.innerHTML = `
                    <div class="product-image" style="background-image: url('${product.image}'); background-color: #f0f0f0;"></div>
                    <div class="product-info">
                        <div class="product-title">${product.name}</div>
                        <div class="product-price">$${product.price.toFixed(2)}</div>
                        <button class="product-btn ${isInCart ? 'added' : ''}" 
                                onclick="GameNexusChatbot.addToCart(${product.id}, this)">
                            ${isInCart ? '✓ Added to Cart' : 'Add to Cart'}
                        </button>
                    </div>
                `;

                const messageElement = document.createElement('div');
                messageElement.className = 'message bot-message';
                messageElement.appendChild(productCard);
                chatbotBody.appendChild(messageElement);
                chatbotBody.scrollTop = chatbotBody.scrollHeight;
            }

            // Function to add item to cart
            window.GameNexusChatbot = window.GameNexusChatbot || {};
            window.GameNexusChatbot.addToCart = function(productId, buttonElement) {
                const product = [...games, ...hardware].find(p => p.id === productId);

                if (!product) {
                    console.error('Product not found:', productId);
                    return;
                }

                const existingItemIndex = cart.findIndex(item => item.id === productId);

                if (existingItemIndex >= 0) {
                    cart.splice(existingItemIndex, 1);
                    if (buttonElement) {
                        buttonElement.classList.remove('added');
                        buttonElement.textContent = 'Add to Cart';
                    }
                    addSystemMessage(`Removed ${product.name} from your cart.`);
                } else {
                    cart.push({
                        id: product.id,
                        name: product.name,
                        price: product.price,
                        image: product.image,
                        quantity: 1
                    });
                    if (buttonElement) {
                        buttonElement.classList.add('added');
                        buttonElement.textContent = '✓ Added to Cart';
                    }
                    addSystemMessage(`Added ${product.name} to your cart!`);
                }

                updateCartCount();
            };

            // Function to update cart count
            function updateCartCount() {
                if (cart.length > 0) {
                    cartCount.textContent = cart.length;
                    cartCount.style.display = 'flex';
                } else {
                    cartCount.style.display = 'none';
                }
            }

            // Function to display cart contents
            function showCart() {
                if (cart.length === 0) {
                    addBotMessage("Your cart is currently empty. Can I help you find something?");
                    return;
                }

                let cartHTML = '<div class="cart-summary"><h4>Your Cart</h4>';

                let total = 0;
                cart.forEach(item => {
                    cartHTML += `
                        <div class="cart-item">
                            <span>${item.name}</span>
                            <span>$${item.price.toFixed(2)}</span>
                        </div>
                    `;
                    total += item.price;
                });

                cartHTML += `
                    <div class="cart-total">Total: $${total.toFixed(2)}</div>
                    <button class="checkout-btn" onclick="GameNexusChatbot.proceedToCheckout()">Proceed to Checkout</button>
                </div>
                `;

                addBotMessage(cartHTML);
            }

            // Function to proceed to checkout
            window.GameNexusChatbot.proceedToCheckout = function() {
                if (cart.length === 0) return;

                addSystemMessage("Redirecting to checkout...");

                setTimeout(() => {
                    // Redirect to GameNexus cart page with cart data
                    window.location.href = "pages/addcart.html?cart=" + encodeURIComponent(JSON.stringify(cart));
                    cart = [];
                    updateCartCount();
                }, 1500);
            };

            // Function to process user messages
            function processUserMessage(message) {
                const lowerMessage = message.toLowerCase();

                if (lowerMessage.includes('hi') || lowerMessage.includes('hello') || lowerMessage.includes('hey')) {
                    addBotMessage("Hello! Welcome to GameNexus! How can I assist you today? 🎮", [
                        "Looking for a new game",
                        "Need PC hardware advice",
                        "Check my order status",
                        "View my cart"
                    ]);
                    return;
                }

                if (lowerMessage.includes('cart') || lowerMessage.includes('basket') || lowerMessage.includes('view my cart')) {
                    showCart();
                    if (cart.length > 0) {
                        addBotMessage("Would you like to continue shopping or proceed to checkout?", [
                            "Show me more games",
                            "Show me hardware",
                            "Proceed to checkout"
                        ]);
                    }
                    return;
                }

                if (lowerMessage.includes('checkout') || lowerMessage.includes('proceed to checkout')) {
                    window.GameNexusChatbot.proceedToCheckout();
                    return;
                }

                if (lowerMessage.includes('game') || lowerMessage.includes('recommend') || lowerMessage.includes('suggest')) {
                    if (lowerMessage.includes('rpg')) {
                        const rpgGames = games.filter(game => game.genre === "RPG");
                        addBotMessage("Here are some great RPG games you might enjoy:");
                        rpgGames.forEach(game => addProductCard(game));
                        addBotMessage("Would you like recommendations for other genres?", [
                            "Action games",
                            "Adventure games",
                            "View my cart",
                            "No thanks"
                        ]);
                    } else if (lowerMessage.includes('action')) {
                        const actionGames = games.filter(game => game.genre === "Action");
                        addBotMessage("Check out these action-packed games:");
                        actionGames.forEach(game => addProductCard(game));
                    } else if (lowerMessage.includes('adventure')) {
                        const adventureGames = games.filter(game => game.genre === "Adventure");
                        addBotMessage("Here are some exciting adventure games:");
                        adventureGames.forEach(game => addProductCard(game));
                    } else if (lowerMessage.includes('strategy')) {
                        const strategyGames = games.filter(game => game.genre === "Strategy");
                        addBotMessage("Here are some strategy games for you:");
                        strategyGames.forEach(game => addProductCard(game));
                    } else if (lowerMessage.includes('racing')) {
                        const racingGames = games.filter(game => game.genre === "Racing");
                        addBotMessage("Here are some thrilling racing games:");
                        racingGames.forEach(game => addProductCard(game));
                    } else {
                        addBotMessage("What type of games are you interested in? 🎮", [
                            "Action games",
                            "Adventure games",
                            "Strategy games",
                            "Racing games",
                            "View my cart"
                        ]);
                    }
                    return;
                }

                if (lowerMessage.includes('hardware') || lowerMessage.includes('pc') || lowerMessage.includes('setup') || 
                    lowerMessage.includes('gpu') || lowerMessage.includes('cpu') || lowerMessage.includes('headset') || 
                    lowerMessage.includes('mouse')) {
                    
                    if (lowerMessage.includes('headset')) {
                        const headsets = hardware.filter(item => item.type === "Headset");
                        addBotMessage("Here are our top gaming headsets:");
                        headsets.forEach(item => addProductCard(item));
                    } else if (lowerMessage.includes('gpu') || lowerMessage.includes('graphics')) {
                        const gpus = hardware.filter(item => item.type === "GPU");
                        addBotMessage("Check out these powerful GPUs for gaming:");
                        gpus.forEach(item => addProductCard(item));
                    } else if (lowerMessage.includes('cpu')) {
                        const cpus = hardware.filter(item => item.type === "CPU");
                        addBotMessage("Here are some high-performance CPUs:");
                        cpus.forEach(item => addProductCard(item));
                    } else if (lowerMessage.includes('mouse')) {
                        const mice = hardware.filter(item => item.type === "Mouse");
                        addBotMessage("Here are some top gaming mice:");
                        mice.forEach(item => addProductCard(item));
                    } else {
                        addBotMessage("What hardware are you looking for? 💻", [
                            "Graphics cards",
                            "CPUs",
                            "Headsets",
                            "Mice",
                            "View my cart"
                        ]);
                    }
                    return;
                }

                if (lowerMessage.includes('order') || lowerMessage.includes('status') || lowerMessage.includes('track')) {
                    if (lowerMessage.includes('1001')) {
                        const order = orders.find(o => o.id === "ORD1001");
                        addBotMessage(`Order #${order.id} is ${order.status}.<br>Items: ${order.items.join(', ')}<br>Total: $${order.total.toFixed(2)}<br>Tracking: ${order.tracking}`);
                    } else if (lowerMessage.includes('1002')) {
                        const order = orders.find(o => o.id === "ORD1002");
                        addBotMessage(`Order #${order.id} is ${order.status}.<br>Items: ${order.items.join(', ')}<br>Total: $${order.total.toFixed(2)}`);
                    } else {
                        addBotMessage("I can check your order status. Please provide your order number or choose from recent orders:", [
                            "ORD1001",
                            "ORD1002",
                            "View my cart"
                        ]);
                    }
                    return;
                }

                if (lowerMessage.includes('run') || lowerMessage.includes('compatib') || lowerMessage.includes('requirement')) {
                    if (lowerMessage.includes('cyberpunk')) {
                        addBotMessage("To run Cyberpunk 2077 at recommended settings, you'll need:<br><br>" +
                            "• OS: Windows 10 64-bit<br>" +
                            "• CPU: Intel Core i7-4790 or AMD Ryzen 3 3200G<br>" +
                            "• RAM: 12GB<br>" +
                            "• GPU: GTX 1060 6GB / GTX 1660 Super or Radeon RX 590<br>" +
                            "• Storage: 70GB SSD<br><br>" +
                            "Would you like me to recommend hardware upgrades?", [
                                "Yes, show me GPUs",
                                "No thanks",
                                "View my cart"
                            ]);
                    } else if (lowerMessage.includes('egypt and world')) {
                        addBotMessage("Egypt and World system requirements:<br><br>" +
                            "Minimum:<br>" +
                            "• OS: Windows 10<br>" +
                            "• CPU: Intel Core i5-8400 or AMD Ryzen 3 3300X<br>" +
                            "• RAM: 8GB<br>" +
                            "• GPU: NVIDIA GeForce GTX 1050 or AMD Radeon RX 570<br><br>" +
                            "Recommended:<br>" +
                            "• GPU: NVIDIA GeForce GTX 1660 or AMD Radeon RX 580");
                    } else {
                        addBotMessage("I can check game compatibility. Which game are you wondering about?", [
                            "Cyberpunk 2077",
                            "Egypt and World",
                            "GTA 5",
                            "View my cart"
                        ]);
                    }
                    return;
                }

                addBotMessage("I'm not sure I understand. How can I help you with your gaming needs?", [
                    "Game recommendations",
                    "Hardware advice",
                    "Order status",
                    "View my cart"
                ]);
            }

            // Function to add system messages
            function addSystemMessage(text) {
                const messageElement = document.createElement('div');
                messageElement.className = 'system-message';
                messageElement.textContent = text;
                chatbotBody.appendChild(messageElement);
                chatbotBody.scrollTop = chatbotBody.scrollHeight;
            }

            console.log('GameNexus Chatbot initialized successfully');
        })();
    </script>

    <script src="script.js"></script>
</body>

</html>