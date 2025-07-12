// script.js

// Function to fetch and display teams
function fetchAndDisplayTeams() {
    fetch('https://student.csc.liv.ac.uk/~sgefojci/v1/teams')
        .then(response => response.json())
        .then(teams => {
            const teamsList = document.getElementById('teams-list');
            teamsList.innerHTML = ''; // Clear any existing list
            teams.forEach(team => {
                const li = document.createElement('li');
                li.innerHTML = `
            <span>${team.name} (Avg Age: ${team.avg_age})</span>
            <button onclick="deleteTeam(${team.id})">Delete</button>
          `;
                li.addEventListener('click', () => fetchAndDisplayPlayers(team.id, team.name));
                teamsList.appendChild(li);
            });
        })
        .catch(error => console.error('Error fetching teams:', error));
}

// Function to fetch and display players for a selected team
function fetchAndDisplayPlayers(teamId, teamName) {
    fetch(`https://student.csc.liv.ac.uk/~sgefojci/v1/teams/${teamId}/players`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(players => {
            const playerList = document.getElementById('player-list');
            playerList.innerHTML = ''; // Clear previous players
            document.getElementById('team-name').textContent = teamName;

            if (players && players.length > 0) {
                players.forEach(player => {
                    const li = document.createElement('li');
                    li.innerHTML = `
              <span>${player.given_names} ${player.surname} (${player.nationality}, ${player.date_of_birth})</span>
              <div>
                <button class="edit" onclick="editPlayer(${teamId}, ${player.id})">Edit</button>
                <button class="delete" onclick="deletePlayer(${teamId}, ${player.id})">Delete</button>
              </div>
            `;
                    playerList.appendChild(li);
                });
            } else {
                playerList.innerHTML = '<li>No players found for this team.</li>';
            }

            document.getElementById('player-details-container').classList.add('show');
            document.getElementById('add-player-form-container').classList.remove('show');
        })
        .catch(error => {
            console.error('Error fetching players:', error);
            alert('Could not retrieve players. Please try again.');
        });
}

// Function to add a new player to the selected team
function addPlayer(teamId) {
    const form = document.getElementById('add-player-form');
    const formData = new FormData(form);
    const playerData = Object.fromEntries(formData.entries());

    fetch(`https://student.csc.liv.ac.uk/~sgefojci/v1/teams/${teamId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(playerData)
    })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw new Error(err.error || 'Could not add player'); });
            }
            return response.json();
        })
        .then(() => {
            fetchAndDisplayPlayers(teamId, document.getElementById('add-player-team-name').textContent);
            form.reset();
            document.getElementById('add-player-form-container').classList.remove('show');
            document.getElementById('player-details-container').classList.add('show');
        })
        .catch(error => {
            console.error('Error adding player:', error);
            alert(error.message);
        });
}

function editPlayer(teamId, playerId) {
    // In a real application, you'd likely fetch the player's data
    // and populate a form for editing.  For simplicity, we'll just log it.
    console.log(`Editing player ${playerId} for team ${teamId}`);
    alert(`Editing functionality not fully implemented in this example.  See console for details.`);
}

function deletePlayer(teamId, playerId) {
    if (confirm("Are you sure you want to delete this player?")) {
        fetch(`https://student.csc.liv.ac.uk/~sgefojci/v1/teams/${teamId}/${playerId}`, {
            method: 'DELETE'
        })
            .then(response => {
                if (response.status === 204) {
                    fetchAndDisplayPlayers(teamId, document.getElementById('team-name').textContent);
                } else if (response.status === 404) {
                    alert('Player not found.');
                } else {
                    throw new Error('Could not delete player.');
                }
            })
            .catch(error => {
                console.error('Error deleting player:', error);
                alert(error.message);
            });
    }
}

function deleteTeam(teamId) {
    if (confirm("Are you sure you want to delete this team and all its players?")) {
        //  No DELETE /teams endpoint is provided in the PHP code you gave.
        //  You'd need to add that to index.php to make this work.
        alert("Delete Team functionality is not implemented on the server-side.");
        return;
        fetch(`https://student.csc.liv.ac.uk/~sgefojci/v1/teams/${teamId}`, {
            method: 'DELETE'
        })
            .then(response => {
                if (response.status === 204) {
                    fetchAndDisplayTeams(); // Refresh the team list
                    document.getElementById('player-details-container').classList.remove('show'); // Hide player details
                } else if (response.status === 404) {
                    alert('Team not found.');
                } else {
                    throw new Error('Could not delete team.');
                }
            })
            .catch(error => {
                console.error('Error deleting team:', error);
                alert(error.message);
            });
    }
}

// ---  Event Listeners and Initialization ---

document.addEventListener('DOMContentLoaded', () => {
    fetchAndDisplayTeams(); // Initial load of teams

    const addPlayerButton = document.getElementById('add-player-button');
    addPlayerButton.addEventListener('click', () => {
        document.getElementById('add-player-form-container').classList.add('show');
        document.getElementById('player-details-container').classList.remove('show');
    });

    const addPlayerForm = document.getElementById('add-player-form');
    addPlayerForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const teamId = new URLSearchParams(window.location.search).get('teamId') ||
            document.getElementById('team-id').value;  // Hidden field
        if (teamId) {
            addPlayer(teamId);
        } else {
            alert("Team ID is missing.  Cannot add player.");
        }

    });
});