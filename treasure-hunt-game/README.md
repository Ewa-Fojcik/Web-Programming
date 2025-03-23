# JavaScript Treasure Hunt Game
This project is a simple grid-based treasure hunt game implemented using HTML, CSS, and vanilla JavaScript. The game consists of three stages: setup, play, and end. In the setup stage, the user can place treasures, obstacles, and a treasure hunter on a grid. During the play stage, the user controls the treasure hunter to collect treasures, avoid obstacles, and score points. The game ends when the user completes the game or when certain conditions are met.

## Features
### Setup Stage:

- Users can place treasures (with values between 5-8), obstacles, and a single treasure hunter on a 5x5 grid.

- Error handling for invalid inputs (e.g., placing objects on already occupied cells or using invalid characters).

- A button to proceed to the play stage once the treasure hunter is placed.

### Play Stage:

- The user controls the treasure hunter using the keys a, d, w, and s to move left, right, up, and down.

- The treasure hunter collects treasures, adds points to the score, and random obstacles are placed on the grid after each treasure is collected.

- Error handling for invalid moves (e.g., trying to move outside the grid or into obstacles).

- The play stage ends when there are no treasures left, the treasure hunter cannot move, or the user decides to end the game.

### End Stage:

- The program calculates the performance index based on the score and the number of rounds completed.

- A final score and performance index is displayed.

## How to Play
- Setup: Place treasures, obstacles, and the treasure hunter on the grid by clicking on cells and entering numbers (5-8 for treasures) or characters (o for obstacles, h for the treasure hunter).

- Play: Use the keys a, d, w, s to move the treasure hunter across the grid and collect treasures.

- End: The game ends when all treasures are collected, or the player ends the game manually. The performance index is displayed based on the score and rounds played.

## Technologies Used
- HTML5

- CSS3

- JavaScript (Vanilla)

## Additional Information
No external libraries or frameworks were used in this project.
  
The game is designed to be simple yet functional, allowing for potential future enhancements like difficulty levels, customizable grid sizes, and improved UI.
