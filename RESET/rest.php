<?php
// index.php
// Main REST API router for COMP519 Assignment 4
// Developed in accordance with COMP519 Coding Standards

require_once 'database.php';
require_once 'team.php';
require_once 'player.php';

// Set content type header for all responses
header('Content-Type: application/json; charset=utf-8');

// Instantiate database connection
$db = new Database();
$team = new Team($db);
$player = new Player($db);

// Get HTTP method and requested path
$method = $_SERVER['REQUEST_METHOD'];
$path = isset($_SERVER['PATH_INFO']) ? trim($_SERVER['PATH_INFO'], '/') : '';
$parts = explode('/', $path);

// Utility function to send JSON responses
function send_response($data, $status = 200) {
    http_response_code($status);
    if ($data !== null) {
        echo json_encode($data);
    }
    exit();
}

// Utility function to parse incoming JSON
function get_json_input() {
    $input = file_get_contents('php://input');
    if (strlen($input) > 0) {
        return json_decode($input, true);
    }
    return array();
}

// Route handling
switch ($parts[0]) {

    case 'teams':

        if ($method == 'GET' && count($parts) == 1) {
            // GET /teams
            $teams = $team->getAllTeams();
            send_response($teams);
        }

        elseif (isset($parts[1]) && is_numeric($parts[1])) {
            $teamId = (int) $parts[1];

            if (count($parts) == 2 && $method == 'GET') {
                // Not needed: we only list players inside teams
                send_response(array('error' => 'Invalid request'), 400);
            }

            elseif (count($parts) == 3 && $parts[2] == 'players') {

                if ($method == 'GET') {
                    // GET /teams/{teamId}/players
                    $players = $player->getPlayersByTeam($teamId);
                    send_response($players);
                }

                elseif ($method == 'POST') {
                    // POST /teams/{teamId}/players
                    $data = get_json_input();

                    if (isset($data['surname'], $data['given_names'], $data['nationality'], $data['date_of_birth'])) {
                        $success = $player->addPlayer($teamId, $data);
                        if ($success) {
                            send_response(array('message' => 'Player created'), 201);
                        } else {
                            send_response(array('error' => 'Failed to create player'), 400);
                        }
                    } else {
                        send_response(array('error' => 'Missing required fields'), 400);
                    }
                }

                else {
                    send_response(array('error' => 'Method Not Allowed'), 405);
                }

            } elseif (count($parts) == 4) {
                // operations on specific player
                $playerId = (int) $parts[3];

                if ($method == 'GET') {
                    // GET /teams/{teamId}/players/{playerId}
                    $result = $player->getPlayer($teamId, $playerId);
                    if ($result) {
                        send_response($result);
                    } else {
                        send_response(array('error' => 'Player not found'), 404);
                    }
                }

                elseif ($method == 'PATCH') {
                    // PATCH /teams/{teamId}/players/{playerId}
                    $data = get_json_input();
                    if (!empty($data)) {
                        $success = $player->updatePlayer($teamId, $playerId, $data);
                        if ($success) {
                            send_response(array('message' => 'Player updated'));
                        } else {
                            send_response(array('error' => 'Failed to update player'), 400);
                        }
                    } else {
                        send_response(array('error' => 'No data provided'), 400);
                    }
                }

                elseif ($method == 'DELETE') {
                    // DELETE /teams/{teamId}/players/{playerId}
                    $success = $player->deletePlayer($teamId, $playerId);
                    if ($success) {
                        send_response(null, 204);
                    } else {
                        send_response(array('error' => 'Failed to delete player'), 400);
                    }
                }

                else {
                    send_response(array('error' => 'Method Not Allowed'), 405);
                }
            }
        }

        else {
            send_response(array('error' => 'Bad Request'), 400);
        }

        break;

    default:
        send_response(array('error' => 'Resource Not Found'), 404);
}
?>
