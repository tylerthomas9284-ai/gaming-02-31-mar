<?php require __DIR__ . '/iqnpdc.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classic Solitaire - Play Free Online Klondike Solitaire</title>
    <meta name="description" content="Play Classic Solitaire online for free. Smooth gameplay, mobile-friendly, no intrusive ads.">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="nav-container">
            <a href="index.html" class="logo">
                <svg class="logo-icon" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                </svg>
                <span>Classic Solitaire</span>
            </a>
            
            <ul class="nav-menu">
                <li><a href="index.html" class="nav-link active">Home</a></li>
                <li><a href="about.html" class="nav-link">About Us</a></li>
                <li><a href="contact.html" class="nav-link">Contact Us</a></li>
            </ul>
        </nav>
    </header>

    <!-- Hero Section - Game Zone -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1>Play Classic Solitaire Online</h1>
                <p class="hero-subtitle">Free Klondike Solitaire game with smooth performance and mobile compatibility</p>
            </div>
            
            <!-- Solitaire Game -->
            <div id="solitaire-game" class="game-container">
                <!-- Game Controls -->
                <div class="game-controls">
                    <div class="controls-left">
                        <button onclick="newGame()" class="btn btn-primary">
                            <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 4v6h6M23 20v-6h-6"/>
                                <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"/>
                            </svg>
                            New Game
                        </button>
                        <button onclick="undo()" class="btn btn-secondary">Undo</button>
                        <button onclick="hint()" class="btn btn-secondary">
                            <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                            </svg>
                            Hint
                        </button>
                        <button onclick="toggleFullscreen()" class="btn btn-secondary">
                            <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/>
                            </svg>
                            Fullscreen
                        </button>
                    </div>
                    
                    <div class="game-stats">
                        <div class="stat">
                            <div class="stat-label">Time</div>
                            <div class="stat-value" id="timer">0:00</div>
                        </div>
                        <div class="stat">
                            <div class="stat-label">Score</div>
                            <div class="stat-value" id="score">0</div>
                        </div>
                    </div>
                </div>

                <!-- Game Board -->
                <div class="game-board">
                    <!-- Stock and Foundations -->
                    <div class="board-top">
                        <div class="board-left">
                            <div id="stock" class="stock-pile" onclick="drawFromStock()"></div>
                            <div id="waste" class="waste-pile"></div>
                        </div>
                        
                        <div class="foundations">
                            <div id="foundation-hearts" class="foundation-pile" data-suit="hearts"></div>
                            <div id="foundation-diamonds" class="foundation-pile" data-suit="diamonds"></div>
                            <div id="foundation-clubs" class="foundation-pile" data-suit="clubs"></div>
                            <div id="foundation-spades" class="foundation-pile" data-suit="spades"></div>
                        </div>
                    </div>

                    <!-- Tableau -->
                    <div class="tableau">
                        <div id="tableau-0" class="tableau-column" data-column="0"></div>
                        <div id="tableau-1" class="tableau-column" data-column="1"></div>
                        <div id="tableau-2" class="tableau-column" data-column="2"></div>
                        <div id="tableau-3" class="tableau-column" data-column="3"></div>
                        <div id="tableau-4" class="tableau-column" data-column="4"></div>
                        <div id="tableau-5" class="tableau-column" data-column="5"></div>
                        <div id="tableau-6" class="tableau-column" data-column="6"></div>
                    </div>
                </div>

                <!-- Win Modal -->
                <div id="win-modal" class="modal" style="display: none;">
                    <div class="modal-content">
                        <svg class="trophy-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6 2h12v5.5c0 3-2.46 5.5-5.5 5.5S7 10.5 7 7.5V2zm0 0H4a2 2 0 0 0-2 2v1a2 2 0 0 0 2 2h2m12-5h2a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-2m-7 13v-3m0 3h6m-6 0H6m6-3a4 4 0 0 1-4-4v-1h8v1a4 4 0 0 1-4 4z"/>
                        </svg>
                        <h2>Congratulations!</h2>
                        <p id="win-message">You won!</p>
                        <button onclick="closeWinModal(); newGame();" class="btn btn-primary">Play Again</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How to Play -->
    <section class="section section-white">
        <div class="container">
            <h2 class="section-title">How to Play</h2>
            
            <div class="grid grid-4">
                <div class="how-to-card">
                    <div class="icon-circle">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="7" width="20" height="14" rx="2"/>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                        </svg>
                    </div>
                    <h3>Deal</h3>
                    <p>Seven columns are dealt with the first column containing one card, the second two cards, and so on. The top card in each column is face-up.</p>
                </div>
                
                <div class="how-to-card">
                    <div class="icon-circle">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                        </svg>
                    </div>
                    <h3>Build</h3>
                    <p>Move cards in descending order (King to Ace) and alternate colors (red and black). You can move sequences of cards together.</p>
                </div>
                
                <div class="how-to-card">
                    <div class="icon-circle">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6 2h12v5.5c0 3-2.46 5.5-5.5 5.5S7 10.5 7 7.5V2z"/>
                        </svg>
                    </div>
                    <h3>Foundation</h3>
                    <p>Move Aces to the foundation piles at the top. Build up each foundation by suit from Ace to King.</p>
                </div>
                
                <div class="how-to-card">
                    <div class="icon-circle">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <h3>Win</h3>
                    <p>The game is won when all cards are moved to the four foundation piles, organized by suit from Ace to King.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Allowed Moves -->
    <section class="section section-gray">
        <div class="container">
            <h2 class="section-title">Allowed Moves</h2>
            
            <div class="grid grid-2">
                <div class="moves-card">
                    <h3 class="moves-title moves-allowed">
                        <span class="check-icon">✓</span>
                        Allowed Moves
                    </h3>
                    <ul class="moves-list">
                        <li class="move-item move-allowed">
                            <span class="move-icon">✓</span>
                            <span>Place a red card on a black card (or vice versa) in descending order</span>
                        </li>
                        <li class="move-item move-allowed">
                            <span class="move-icon">✓</span>
                            <span>Move a King to an empty tableau column</span>
                        </li>
                        <li class="move-item move-allowed">
                            <span class="move-icon">✓</span>
                            <span>Move an Ace to an empty foundation pile</span>
                        </li>
                        <li class="move-item move-allowed">
                            <span class="move-icon">✓</span>
                            <span>Build foundation piles in ascending order by suit</span>
                        </li>
                        <li class="move-item move-allowed">
                            <span class="move-icon">✓</span>
                            <span>Move sequences of cards together if they are in proper order</span>
                        </li>
                        <li class="move-item move-allowed">
                            <span class="move-icon">✓</span>
                            <span>Turn over face-down cards when they become the top card</span>
                        </li>
                    </ul>
                </div>

                <div class="moves-card">
                    <h3 class="moves-title moves-not-allowed">
                        <span class="x-icon">✕</span>
                        Not Allowed
                    </h3>
                    <ul class="moves-list">
                        <li class="move-item move-not-allowed">
                            <span class="move-icon">✕</span>
                            <span>Place a card of the same color on top of each other in tableau</span>
                        </li>
                        <li class="move-item move-not-allowed">
                            <span class="move-icon">✕</span>
                            <span>Move cards from foundation piles back to tableau</span>
                        </li>
                        <li class="move-item move-not-allowed">
                            <span class="move-icon">✕</span>
                            <span>Place non-King cards in empty tableau columns</span>
                        </li>
                        <li class="move-item move-not-allowed">
                            <span class="move-icon">✕</span>
                            <span>Build foundation piles in descending order</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Rules -->
    <section class="section section-white">
        <div class="container container-narrow">
            <h2 class="section-title">Solitaire Rules</h2>
            
            <div class="rules-card">
                <div class="rule-section">
                    <h3>
                        <svg class="rule-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 16v-4M12 8h.01"/>
                        </svg>
                        Game Objective
                    </h3>
                    <p>The goal is to move all 52 cards to the four foundation piles, building each pile up by suit from Ace to King.</p>
                </div>

                <div class="rule-section">
                    <h3>Card Movement Rules</h3>
                    <ul class="rule-list">
                        <li><strong>Tableau:</strong> Cards must be placed in descending order (K, Q, J, 10, 9, 8, 7, 6, 5, 4, 3, 2, A) and alternate colors (red on black, black on red).</li>
                        <li><strong>Foundation:</strong> Cards must be placed in ascending order (A, 2, 3, 4, 5, 6, 7, 8, 9, 10, J, Q, K) and by suit.</li>
                        <li><strong>Empty Columns:</strong> Only Kings (or sequences starting with a King) can be moved to empty tableau columns.</li>
                        <li><strong>Stock Pile:</strong> Click the stock pile to reveal cards. When the stock is empty, click it again to reset.</li>
                    </ul>
                </div>

                <div class="rule-section">
                    <h3>Draw Modes</h3>
                    <ul class="rule-list">
                        <li><strong>Turn 1:</strong> Draw one card at a time from the stock pile (easier mode).</li>
                        <li><strong>Turn 3:</strong> Draw three cards at a time, with only the top card playable (harder mode).</li>
                    </ul>
                </div>

                <div class="rule-section">
                    <h3>Scoring</h3>
                    <ul class="rule-list">
                        <li>Move to foundation: <strong>+10 points</strong></li>
                        <li>Move between tableau columns: <strong>+5 points</strong></li>
                        <li>Undo: <strong>-10 points</strong></li>
                    </ul>
                </div>

                <div class="rule-section">
                    <h3>Winning the Game</h3>
                    <p>You win when all four foundation piles are complete, with each suit built from Ace to King. Try to complete the game in the shortest time possible with the highest score!</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog -->
    <section class="section section-gray">
        <div class="container">
            <div class="blog-header">
                <h2 class="section-title">Solitaire Blog</h2>
                <p class="blog-subtitle">Tips, strategies, and interesting facts about the world's most popular card game</p>
            </div>
            
            <div class="grid grid-3">
                <article class="blog-card">
                    <div class="blog-image">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                        </svg>
                    </div>
                    <div class="blog-content">
                        <span class="blog-category">Strategy</span>
                        <h3>3 Proven Strategies to Win Solitaire Every Time</h3>
                        <p>Master these expert techniques to improve your win rate and complete games faster. Learn the secrets that professional players use.</p>
                        <div class="blog-meta">
                            <span>📅 January 15, 2026</span>
                            <span>🕐 5 min read</span>
                        </div>
                    </div>
                </article>

                <article class="blog-card">
                    <div class="blog-image">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                        </svg>
                    </div>
                    <div class="blog-content">
                        <span class="blog-category">History</span>
                        <h3>The History of Solitaire: From Napoleon to Windows</h3>
                        <p>Discover the fascinating journey of Solitaire from its origins in 18th century Europe to becoming the most played computer game.</p>
                        <div class="blog-meta">
                            <span>📅 January 10, 2026</span>
                            <span>🕐 8 min read</span>
                        </div>
                    </div>
                </article>

                <article class="blog-card">
                    <div class="blog-image">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                        </svg>
                    </div>
                    <div class="blog-content">
                        <span class="blog-category">Health</span>
                        <h3>Why Playing Solitaire is Good for Brain Health</h3>
                        <p>Scientific research shows that playing Solitaire can improve memory, problem-solving skills, and reduce stress levels.</p>
                        <div class="blog-meta">
                            <span>📅 January 5, 2026</span>
                            <span>🕐 6 min read</span>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-col">
                    <div class="footer-brand">
                        <svg class="footer-icon" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                        </svg>
                        <span>Classic Solitaire</span>
                    </div>
                    <p>Free, high-quality classic card games with smooth performance and mobile compatibility.</p>
                </div>

                <div class="footer-col">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.html">Home</a></li>
                        <li><a href="about.html">About Us</a></li>
                        <li><a href="contact.html">Contact Us</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h3>Legal</h3>
                    <ul>
                        <li><a href="terms.html">Terms & Conditions</a></li>
                        <li><a href="privacy.html">Privacy Policy</a></li>
                        <li><a href="disclaimer.html">Disclaimer</a></li>
                    </ul>
                    <p class="disclaimer">This game is for entertainment purposes only and does not involve real money gambling.</p>
                </div>

                <div class="footer-col">
                    <h3>Contact</h3>
                    <ul class="contact-list">
                        <li>📞 +1-376-348-8733</li>
                        <li>✉️ <a href="mailto:play@solitaire.site">play@solitaire.site</a></li>
                        <li>📍 101 Johnson St, Brooklyn, NY 11201, USA</li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>© 2026 Classic Solitaire. All rights reserved.</p>
                <div class="social-links">
                    <span>Share your high score:</span>
                    <a href="https://facebook.com" target="_blank" class="social-icon" aria-label="Share on Facebook">f</a>
                    <a href="https://twitter.com" target="_blank" class="social-icon" aria-label="Share on Twitter">𝕏</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="solitaire.js"></script>
    <script src="main.js"></script>
</body>
</html>
