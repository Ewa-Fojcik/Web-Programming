<?php
// Player.php
// Class to manage Players for COMP519 Assignment 4
// Developed in accordance with COMP519 Coding Standards

require_once 'Database.php';

class Player {

    private $pdo;

    /**
     * Constructor accepts a PDO database connection
     */
    public function __construct($db) {
        $this->pdo = $db->pdo;
    }

    /**
     * Retrieve all players for a given team ID
     *
     * @param int $teamId
     * @return array
     */
    public function getPlayersByTeam($teamId) {
        $sql = "SELECT id, surname, given_names, nationality, date_of_birth FROM players WHERE team_id = ? ORDER BY surname ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array($teamId));
        return $stmt->fetchAll();
    }

    /**
     * Retrieve a specific player by player ID and team ID
     *
     * @param int $teamId
     * @param int $playerId
     * @return array|false
     */
    public function getPlayer($teamId, $playerId) {
        $sql = "SELECT id, surname, given_names, nationality, date_of_birth FROM players WHERE id = ? AND team_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array($playerId, $teamId));
        return $stmt->fetch();
    }

    /**
     * Add a new player to a team
     *
     * @param int $teamId
     * @param array $data
     * @return bool
     */
    public function addPlayer($teamId, $data) {
        $sql = "INSERT INTO players (team_id, surname, given_names, nationality, date_of_birth) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $success = $stmt->execute(array(
            $teamId,
            $data['surname'],
            $data['given_names'],
            $data['nationality'],
            $data['date_of_birth']
        ));

        if ($success) {
            $this->updateAverageAge($teamId);
        }

        return $success;
    }

    /**
     * Update a player (partial updates allowed)
     *
     * @param int $teamId
     * @param int $playerId
     * @param array $data
     * @return bool
     */
    public function updatePlayer($teamId, $playerId, $data) {
        $fields = array();
        $values = array();

        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }

        if (empty($fields)) {
            return false;
        }

        $values[] = $playerId;
        $values[] = $teamId;

        $sql = "UPDATE players SET " . implode(", ", $fields) . " WHERE id = ? AND team_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $success = $stmt->execute($values);

        if ($success) {
            $this->updateAverageAge($teamId);
        }

        return $success;
    }

    /**
     * Delete a player from a team
     *
     * @param int $teamId
     * @param int $playerId
     * @return bool
     */
    public function deletePlayer($teamId, $playerId) {
        $sql = "DELETE FROM players WHERE id = ? AND team_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $success = $stmt->execute(array($playerId, $teamId));

        if ($success) {
            $this->updateAverageAge($teamId);
        }

        return $success;
    }

    /**
     * Update the average age of a team based on its players
     *
     * @param int $teamId
     */
    private function updateAverageAge($teamId) {
        $sql = "SELECT AVG(TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE())) AS avg_age FROM players WHERE team_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array($teamId));
        $row = $stmt->fetch();

        $avgAge = $row ? $row['avg_age'] : null;

        $update = "UPDATE teams SET avg_age = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($update);
        $stmt->execute(array($avgAge, $teamId));
    }

}
?>