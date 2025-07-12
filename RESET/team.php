<?php
// Team.php
// Class to manage Teams for COMP519 Assignment 4
// Developed in accordance with COMP519 Coding Standards

require_once 'Database.php';

class Team {

    private $pdo;

    /**
     * Constructor accepts a PDO database connection
     */
    public function __construct($db) {
        $this->pdo = $db->pdo;
    }

    /**
     * Retrieve all teams ordered by name
     * Each team also includes a link to its players resource
     *
     * @return array
     */
    public function getAllTeams() {
        $sql = "SELECT * FROM teams ORDER BY name ASC";
        $stmt = $this->pdo->query($sql);
        $teams = array();

        while ($row = $stmt->fetch()) {
            $team = array(
                'id' => (int) $row['id'],
                'name' => $row['name'],
                'avg_age' => (float) $row['avg_age'],
                '_links' => array(
                    'players' => array(
                        'href' => '/v1/teams/' . $row['id'] . '/players'
                    )
                )
            );
            $teams[] = $team;
        }
        return $teams;
    }
}
?>