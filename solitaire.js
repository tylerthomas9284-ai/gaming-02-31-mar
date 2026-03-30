// Solitaire Game Logic

const suits = ['hearts', 'diamonds', 'clubs', 'spades'];
const ranks = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];
const suitSymbols = {
    hearts: '♥',
    diamonds: '♦',
    clubs: '♣',
    spades: '♠'
};

let gameState = {
    tableau: [],
    foundation: { hearts: [], diamonds: [], clubs: [], spades: [] },
    stock: [],
    waste: []
};

let moveHistory = [];
let score = 0;
let time = 0;
let timerInterval = null;
let draggedCards = null;
let dragSource = null;

// Initialize game on page load
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('solitaire-game')) {
        newGame();
    }
});

function createDeck() {
    const deck = [];
    suits.forEach(suit => {
        ranks.forEach(rank => {
            deck.push({
                suit,
                rank,
                faceUp: false,
                id: `${suit}-${rank}`
            });
        });
    });
    return shuffleDeck(deck);
}

function shuffleDeck(deck) {
    const newDeck = [...deck];
    for (let i = newDeck.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [newDeck[i], newDeck[j]] = [newDeck[j], newDeck[i]];
    }
    return newDeck;
}

function newGame() {
    const deck = createDeck();
    gameState.tableau = [[], [], [], [], [], [], []];
    
    // Deal cards to tableau
    let deckIndex = 0;
    for (let col = 0; col < 7; col++) {
        for (let row = 0; row <= col; row++) {
            const card = deck[deckIndex++];
            if (row === col) card.faceUp = true;
            gameState.tableau[col].push(card);
        }
    }
    
    // Remaining cards go to stock
    gameState.stock = deck.slice(deckIndex);
    gameState.waste = [];
    gameState.foundation = { hearts: [], diamonds: [], clubs: [], spades: [] };
    
    moveHistory = [];
    score = 0;
    time = 0;
    
    updateDisplay();
    startTimer();
}

function startTimer() {
    if (timerInterval) clearInterval(timerInterval);
    
    timerInterval = setInterval(() => {
        time++;
        updateTimer();
    }, 1000);
}

function updateTimer() {
    const mins = Math.floor(time / 60);
    const secs = time % 60;
    document.getElementById('timer').textContent = `${mins}:${secs.toString().padStart(2, '0')}`;
}

function updateScore() {
    document.getElementById('score').textContent = score;
}

function updateDisplay() {
    updateStock();
    updateWaste();
    updateFoundations();
    updateTableau();
    updateScore();
    updateTimer();
}

function updateStock() {
    const stockEl = document.getElementById('stock');
    if (gameState.stock.length > 0) {
        stockEl.innerHTML = `
            <div style="text-align: center; color: white; font-size: 0.75rem;">
                <div style="font-size: 1.5rem; font-weight: bold;">${gameState.stock.length}</div>
                <div>cards</div>
            </div>
        `;
    } else {
        stockEl.innerHTML = `
            <div style="text-align: center; color: white; font-size: 0.75rem;">
                <svg style="width: 1.5rem; height: 1.5rem; margin: 0 auto 0.25rem;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 4v6h6M23 20v-6h-6"/>
                    <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"/>
                </svg>
                <div>Reset</div>
            </div>
        `;
    }
}

function updateWaste() {
    const wasteEl = document.getElementById('waste');
    wasteEl.innerHTML = '';
    
    if (gameState.waste.length > 0) {
        const topCard = gameState.waste[gameState.waste.length - 1];
        wasteEl.appendChild(createCardElement(topCard, 'waste', gameState.waste.length - 1));
    }
}

function updateFoundations() {
    suits.forEach(suit => {
        const foundationEl = document.getElementById(`foundation-${suit}`);
        const pile = gameState.foundation[suit];
        foundationEl.innerHTML = '';
        
        if (pile.length > 0) {
            const topCard = pile[pile.length - 1];
            foundationEl.appendChild(createCardElement(topCard, `foundation-${suit}`, pile.length - 1, false));
        } else {
            const color = isRed(suit) ? '#dc2626' : '#1f2937';
            foundationEl.innerHTML = `<div style="font-size: 2.5rem; opacity: 0.25; color: ${color};">${suitSymbols[suit]}</div>`;
        }
        
        setupDropZone(foundationEl, 'foundation', suit);
    });
}

function updateTableau() {
    for (let col = 0; col < 7; col++) {
        const columnEl = document.getElementById(`tableau-${col}`);
        columnEl.innerHTML = '';
        
        const column = gameState.tableau[col];
        
        if (column.length === 0) {
            columnEl.innerHTML = '<div style="width: 4rem; height: 6rem; border: 2px dashed white; border-radius: 0.5rem; opacity: 0.25;"></div>';
        } else {
            column.forEach((card, index) => {
                const cardEl = createCardElement(card, `tableau-${col}`, index, true);
                cardEl.style.top = `${index * 1.5}rem`;
                columnEl.appendChild(cardEl);
            });
        }
        
        setupDropZone(columnEl, 'tableau', col);
    }
}

function createCardElement(card, source, index, draggable = true) {
    const cardEl = document.createElement('div');
    cardEl.className = 'card';
    cardEl.dataset.source = source;
    cardEl.dataset.index = index;
    cardEl.dataset.cardId = card.id;
    
    if (!card.faceUp) {
        cardEl.classList.add('card-back');
        cardEl.innerHTML = '<div style="font-size: 1.5rem; opacity: 0.2;">♠</div>';
        if (source.startsWith('tableau')) {
            cardEl.style.cursor = 'pointer';
            cardEl.addEventListener('click', () => flipCard(source, index));
        }
    } else {
        const isRedCard = isRed(card.suit);
        cardEl.classList.add(isRedCard ? 'red' : 'black');
        cardEl.classList.add('card-front');
        cardEl.innerHTML = `
            <div class="card-top">
                <span class="card-rank">${card.rank}</span>
                <span class="card-suit">${suitSymbols[card.suit]}</span>
            </div>
            <div class="card-center">
                <span class="card-suit">${suitSymbols[card.suit]}</span>
            </div>
            <div class="card-bottom">
                <span class="card-suit">${suitSymbols[card.suit]}</span>
                <span class="card-rank">${card.rank}</span>
            </div>
        `;
        
        if (draggable && card.faceUp) {
            cardEl.draggable = true;
            cardEl.addEventListener('dragstart', (e) => handleDragStart(e, source, index));
            cardEl.addEventListener('dragend', handleDragEnd);
            
            // Double-click to auto-move to foundation
            cardEl.addEventListener('dblclick', () => autoMoveToFoundation(source, index));
        }
    }
    
    return cardEl;
}

function handleDragStart(e, source, index) {
    if (source === 'waste') {
        draggedCards = [gameState.waste[gameState.waste.length - 1]];
    } else if (source.startsWith('tableau')) {
        const col = parseInt(source.split('-')[1]);
        draggedCards = gameState.tableau[col].slice(index);
    } else {
        return;
    }
    
    dragSource = { type: source.split('-')[0], data: source };
    e.target.classList.add('dragging');
    e.dataTransfer.effectAllowed = 'move';
}

function handleDragEnd(e) {
    e.target.classList.remove('dragging');
}

function setupDropZone(element, type, data) {
    element.addEventListener('dragover', (e) => {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
    });
    
    element.addEventListener('dragenter', (e) => {
        e.preventDefault();
        element.classList.add('drop-target');
    });
    
    element.addEventListener('dragleave', (e) => {
        if (e.target === element) {
            element.classList.remove('drop-target');
        }
    });
    
    element.addEventListener('drop', (e) => {
        e.preventDefault();
        element.classList.remove('drop-target');
        
        if (!draggedCards || !dragSource) return;
        
        if (type === 'foundation') {
            handleFoundationDrop(data);
        } else if (type === 'tableau') {
            handleTableauDrop(data);
        }
        
        draggedCards = null;
        dragSource = null;
    });
}

function handleFoundationDrop(suit) {
    if (draggedCards.length !== 1) return;
    
    const card = draggedCards[0];
    const foundationPile = gameState.foundation[suit];
    
    if (canPlaceOnFoundation(card, foundationPile)) {
        saveState();
        
        // Remove from source
        if (dragSource.type === 'waste') {
            gameState.waste.pop();
        } else if (dragSource.type === 'tableau') {
            const col = parseInt(dragSource.data.split('-')[1]);
            gameState.tableau[col] = gameState.tableau[col].filter(c => c.id !== card.id);
            if (gameState.tableau[col].length > 0) {
                gameState.tableau[col][gameState.tableau[col].length - 1].faceUp = true;
            }
        }
        
        // Add to foundation
        gameState.foundation[suit].push(card);
        score += 10;
        
        updateDisplay();
        checkWin();
    }
}

function handleTableauDrop(targetCol) {
    const targetColumn = gameState.tableau[targetCol];
    
    if (canPlaceOnTableau(draggedCards, targetColumn)) {
        saveState();
        
        // Remove from source
        if (dragSource.type === 'waste') {
            gameState.waste.pop();
        } else if (dragSource.type === 'tableau') {
            const sourceCol = parseInt(dragSource.data.split('-')[1]);
            const firstCard = draggedCards[0];
            const index = gameState.tableau[sourceCol].findIndex(c => c.id === firstCard.id);
            gameState.tableau[sourceCol] = gameState.tableau[sourceCol].slice(0, index);
            if (gameState.tableau[sourceCol].length > 0) {
                gameState.tableau[sourceCol][gameState.tableau[sourceCol].length - 1].faceUp = true;
            }
        }
        
        // Add to target
        gameState.tableau[targetCol].push(...draggedCards);
        score += 5;
        
        updateDisplay();
    }
}

function flipCard(source, index) {
    const col = parseInt(source.split('-')[1]);
    const column = gameState.tableau[col];
    
    if (index === column.length - 1 && !column[index].faceUp) {
        saveState();
        column[index].faceUp = true;
        updateDisplay();
    }
}

function drawFromStock() {
    if (gameState.stock.length === 0) {
        // Reset stock from waste
        gameState.stock = gameState.waste.reverse().map(c => ({ ...c, faceUp: false }));
        gameState.waste = [];
    } else {
        // Draw card
        const card = gameState.stock.pop();
        card.faceUp = true;
        gameState.waste.push(card);
    }
    
    updateDisplay();
}

function autoMoveToFoundation(source, index) {
    let card;
    
    if (source === 'waste') {
        card = gameState.waste[gameState.waste.length - 1];
    } else if (source.startsWith('tableau')) {
        const col = parseInt(source.split('-')[1]);
        card = gameState.tableau[col][index];
        
        // Only allow auto-move if it's the last card in the column
        if (index !== gameState.tableau[col].length - 1) return;
    } else {
        return;
    }
    
    // Try each foundation
    for (const suit of suits) {
        if (canPlaceOnFoundation(card, gameState.foundation[suit])) {
            draggedCards = [card];
            dragSource = { type: source.split('-')[0], data: source };
            handleFoundationDrop(suit);
            return;
        }
    }
}

function canPlaceOnTableau(cards, targetColumn) {
    const card = cards[0];
    
    if (targetColumn.length === 0) {
        return card.rank === 'K';
    }
    
    const targetCard = targetColumn[targetColumn.length - 1];
    
    if (!targetCard.faceUp) return false;
    
    const isAlternatingColor = isRed(card.suit) !== isRed(targetCard.suit);
    const isDescending = getRankValue(card.rank) === getRankValue(targetCard.rank) - 1;
    
    return isAlternatingColor && isDescending;
}

function canPlaceOnFoundation(card, foundationPile) {
    if (foundationPile.length === 0) {
        return card.rank === 'A';
    }
    
    const topCard = foundationPile[foundationPile.length - 1];
    return (
        card.suit === topCard.suit &&
        getRankValue(card.rank) === getRankValue(topCard.rank) + 1
    );
}

function getRankValue(rank) {
    return ranks.indexOf(rank) + 1;
}

function isRed(suit) {
    return suit === 'hearts' || suit === 'diamonds';
}

function saveState() {
    moveHistory.push(JSON.parse(JSON.stringify(gameState)));
}

function undo() {
    if (moveHistory.length === 0) return;
    
    gameState = moveHistory.pop();
    score = Math.max(0, score - 10);
    updateDisplay();
}

function hint() {
    alert('Hint: Look for Kings to place in empty tableau columns, or Aces to start foundations!');
}

function toggleFullscreen() {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen().catch(err => {
            alert(`Error attempting to enable fullscreen: ${err.message}`);
        });
    } else {
        document.exitFullscreen();
    }
}

function checkWin() {
    const allComplete = suits.every(suit => gameState.foundation[suit].length === 13);
    
    if (allComplete) {
        clearInterval(timerInterval);
        showWinModal();
    }
}

function showWinModal() {
    const modal = document.getElementById('win-modal');
    const mins = Math.floor(time / 60);
    const secs = time % 60;
    const timeStr = `${mins}:${secs.toString().padStart(2, '0')}`;
    
    document.getElementById('win-message').textContent = 
        `You won in ${timeStr} with a score of ${score}!`;
    
    modal.style.display = 'flex';
}

function closeWinModal() {
    document.getElementById('win-modal').style.display = 'none';
}

// Close modal when clicking outside
document.addEventListener('click', (e) => {
    const modal = document.getElementById('win-modal');
    if (e.target === modal) {
        closeWinModal();
    }
});
