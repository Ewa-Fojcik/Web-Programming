document.getElementById("game-name").textContent = `Treasure Hunt`;
const game = document.getElementById("game");
const statsDiv = document.getElementById("stats");
const performanceIndexDiv = document.getElementById("performance-index");
const errorMessageDiv = document.getElementById("error-message");
const endSetupButton = document.getElementById("end-setup");
const endGameButton = document.getElementById("end-game-button");
const rules1Div = document.getElementById("rules-1");
const rules2Div = document.getElementById("rules-2");
const objectivesDiv = document.getElementById("objectives");

var x_dim = 5;
var y_dim = 5;
var stage = 0;
var round = 0;
var score = 0;
var hunterExists = false;
var selectedKey = null;
const grid = [];
let count_treasures = 0;
endGameButton.style.display = "none";
rules2Div.style.display = "none";

const obstacles = [
    "o1.png",
    "o2.png",
    "o3.png",
];

const treasureImages = {
    5: "coin5.png",
    6: "coin6.png",
    7: "coin7.png",
    8: "coin8.png"
};

function initializeGrid() {
    for (let j = 0; j < y_dim; j++) {
        let row = [];
        for (let i = 0; i < x_dim; i++) {
            row.push({
                treasure_val: 0,
                hunter: false,
                obstacle: false,
                id: `sq${i + j * x_dim + 1}`,
                obstacleImage: null
            });
        }
        grid.push(row);
    }

    game.style.display = "grid";
    game.style.gridTemplateColumns = `repeat(${x_dim}, 1fr)`;
    game.style.gridTemplateRows = `repeat(${y_dim}, 1fr)`;

    for (let j = 0; j < y_dim; j++) {
        for (let i = 0; i < x_dim; i++) {
            const sq = document.createElement("div");
            sq.id = grid[j][i]["id"];
            sq.className = "sq";
            game.appendChild(sq);
            sq.addEventListener("click", function () {
                selectedKey = grid[j][i]["id"];
                document.addEventListener("keypress", keyChoice);
            });
        }
    }
}

function keyChoice(event) {
    if (!selectedKey || stage !== 0) return;
    let cell;
    for (let j = 0; j < y_dim; j++) {
        for (let i = 0; i < x_dim; i++) {
            if (grid[j][i].id === selectedKey) {
                cell = grid[j][i];
                break;
            }
        }
        if (cell) break;
    }
    if (cell.treasure_val || cell.hunter || cell.obstacle) {
        displayErrorMessage("Cell is already occupied.");
        removeKeyChoiceListener();
        return;
    }
    let userInput = event.key;
    if (["5", "6", "7", "8"].includes(userInput)) {
        cell.treasure_val = parseInt(userInput);
        count_treasures++;
    } else if (["o", "O"].includes(userInput)) {
        cell.obstacle = true;
        const randomIndex = Math.floor(Math.random() * obstacles.length);
        cell.obstacleImage = obstacles[randomIndex];
    } else if (["h", "H"].includes(userInput)) {
        if (hunterExists) {
            displayErrorMessage("A treasure hunter already exists.");
            removeKeyChoiceListener();
            return;
        }
        cell.hunter = true;
        hunterExists = true;
    } else {
        displayErrorMessage("Invalid input. Please enter 5-8, 'o', or 'h'.");
        removeKeyChoiceListener();
        return;
    }
    updateGridVisuals();
    selectedKey = null;
    removeKeyChoiceListener();
    errorMessageDiv.textContent = "";
}

function removeKeyChoiceListener() {
    document.removeEventListener("keypress", keyChoice);
}

function updateGridVisuals() {
    for (let j = 0; j < y_dim; j++) {
        for (let i = 0; i < x_dim; i++) {
            const cell = document.getElementById(grid[j][i].id);
            cell.innerHTML = "";

            if (grid[j][i].treasure_val) {
                const treasureImg = document.createElement("img");
                treasureImg.src = treasureImages[grid[j][i].treasure_val];
                treasureImg.style.width = "100%";
                treasureImg.style.height = "100%";
                cell.appendChild(treasureImg);
            } else if (grid[j][i].obstacle) {
                const obstacleImg = document.createElement("img");
                obstacleImg.src = grid[j][i].obstacleImage;
                obstacleImg.style.width = "100%";
                obstacleImg.style.height = "100%";
                cell.appendChild(obstacleImg);
            } else if (grid[j][i].hunter) {
                const hunterImg = document.createElement("img");
                hunterImg.src = "hunter.png";
                hunterImg.style.width = "100%";
                hunterImg.style.height = "100%";
                cell.appendChild(hunterImg);
            } else {
                cell.textContent = "";
            }
        }
    }
}

function displayErrorMessage(message) {
    errorMessageDiv.textContent = message;
}

endSetupButton.addEventListener("click", () => {
    if (!hunterExists) {
        displayErrorMessage("Place the hunter first.");
        return;
    }
    stage = 1;
    errorMessageDiv.textContent = "";
    document.addEventListener("keydown", handleMovement);
    statsDiv.style.display = "block";
    endSetupButton.style.display = "none";
    rules1Div.style.display = "none";
    rules2Div.style.display = "block";

    updateGameStats();
    endGameButton.style.display = "block"

    if (count_treasures === 0) {
        endGame();
    }
});

function handleMovement(event) {
    if (stage !== 1) return;

    if (!["w", "a", "s", "d"].includes(event.key.toLowerCase())) {
        displayErrorMessage("Invalid key. Use W, A, S, or D to move.");
        return;
    }

    let hunterPos = findHunter();
    let newX = hunterPos.x;
    let newY = hunterPos.y;

    switch (event.key.toLowerCase()) {
        case "w": newY--; break;
        case "s": newY++; break;
        case "a": newX--; break;
        case "d": newX++; break;
    }

    if (newX < 0 || newX >= x_dim || newY < 0 || newY >= y_dim || grid[newY][newX].obstacle) {
        displayErrorMessage("Cannot move there.");
        return;
    }

    // Move the hunter
    grid[hunterPos.y][hunterPos.x].hunter = false;
    grid[newY][newX].hunter = true;

    if (grid[newY][newX].treasure_val) {
        score += grid[newY][newX].treasure_val;
        grid[newY][newX].treasure_val = 0;
        count_treasures--;
        addRandomObstacle();
    }

    round++;
    updateGridVisuals();
    updateGameStats();
    errorMessageDiv.textContent = "";

    if (checkEndGame()) {
        endGame();
    }
}

function findHunter() {
    for (let j = 0; j < y_dim; j++) {
        for (let i = 0; i < x_dim; i++) {
            if (grid[j][i].hunter) {
                return { x: i, y: j };
            }
        }
    }
    return null;
}

function addRandomObstacle() {
    let emptyCells = [];
    for (let j = 0; j < y_dim; j++) {
        for (let i = 0; i < x_dim; i++) {
            if (!grid[j][i].hunter && !grid[j][i].obstacle && !grid[j][i].treasure_val) {
                emptyCells.push({ x: i, y: j });
            }
        }
    }
    if (emptyCells.length > 0) {
        const randomCell = emptyCells[Math.floor(Math.random() * emptyCells.length)];
        grid[randomCell.y][randomCell.x].obstacle = true;
        const randomIndex = Math.floor(Math.random() * obstacles.length);
        grid[randomCell.y][randomCell.x].obstacleImage = obstacles[randomIndex];
        updateGridVisuals();
    }
}

function countTreasuresLeft() {
    let count5 = 0, count6 = 0, count7 = 0, count8 = 0;
    for (let j = 0; j < y_dim; j++) {
        for (let i = 0; i < x_dim; i++) {
            if (grid[j][i].treasure_val === 5) count5++;
            if (grid[j][i].treasure_val === 6) count6++;
            if (grid[j][i].treasure_val === 7) count7++;
            if (grid[j][i].treasure_val === 8) count8++;
        }
    }
    return { count5, count6, count7, count8 };
}

function updateGameStats() {
    const treasuresLeft = countTreasuresLeft();
    statsDiv.innerHTML = `
        <p>Score: ${score}</p>
        <p>Round: ${round}</p>
        <p>5s Left: ${treasuresLeft.count5}</p>
        <p>6s Left: ${treasuresLeft.count6}</p>
        <p>7s Left: ${treasuresLeft.count7}</p>
        <p>8s Left: ${treasuresLeft.count8}</p>
    `;
}

function checkEndGame() {
    if (count_treasures === 0) {
        return true;
    }

    const hunterPos = findHunter();
    if (!hunterPos) return false;

    const { x, y } = hunterPos;
    const directions = [
        { dx: -1, dy: 0 },
        { dx: 1, dy: 0 },
        { dx: 0, dy: -1 },
        { dx: 0, dy: 1 }
    ];

    let isSurrounded = true;
    for (const dir of directions) {
        const newX = x + dir.dx;
        const newY = y + dir.dy;

        if (newX >= 0 && newX < x_dim && newY >= 0 && newY < y_dim) {
            const cell = grid[newY][newX];
            if (!cell.obstacle && !cell.treasure_val) {
                isSurrounded = false;
                break;
            }
        }
    }

    return isSurrounded;
}

function endGame() {
    stage = 2;
    let performanceIndex = round === 0 ? 0 : (score / round).toFixed(2);

    game.style.display = "none";
    statsDiv.style.display = "none";
    endGameButton.style.display = "none";
    rules2Div.style.display = "none";
    objectivesDiv.style.display = "none";
    errorMessageDiv.style.display = "none";

    if (score == 0) {
        performanceIndexDiv.textContent = `Umm... Your performance index is 0 but I'm sure next time you'll do better!`;
    } else {
        performanceIndexDiv.textContent = `Congrats! Your perfomance index is: ${performanceIndex}`;
    }
}

endGameButton.addEventListener("click", endGame);

initializeGrid();