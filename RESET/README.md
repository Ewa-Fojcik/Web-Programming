# RESTful API for Sports Team & Player Management

## Project Overview

This repository presents a comprehensive RESTful web service and a minimalist client-side interface for managing information about teams and players in a team-based sport. Developed using **PHP** and **MySQL**, the project demonstrates robust API design, secure database interactions, and client-side consumption of RESTful services using **JavaScript (Ajax)**.

## Key Features

### RESTful Web Service (Backend)

* **Core Functionality:** Provides and maintains information on sports teams and their players (e.g., team name, sport, average player age; player surname, given names, nationality, date of birth).
* **Data Persistence:** Information is stored and managed in a **MySQL database**, designed to accommodate an arbitrary number of teams and players.
* **API Endpoints:** Supports full CRUD (Create, Retrieve, Update, Delete) operations for player data, and retrieval for team data:
    * `GET /teams`: Retrieve all teams (sorted by name, includes player collection path).
    * `GET /teams/{teamId}/players`: Retrieve all players for a specific team.
    * `GET /teams/{teamId}/players/{playerId}`: Retrieve a specific player's information.
    * `POST /teams/{teamId}/players`: Add a new player to a specific team.
    * `DELETE /teams/{teamId}/players/{playerId}`: Delete an existing player.
    * `PUT /teams/{teamId}/players/{playerId}`: Update parts of a player's information.
* **Data Format:** All requests and responses for player/team data are handled in **JSON**.
* **Database Interaction:** Implemented using **PHP Data Objects (PDO)** for secure and parameterized queries, preventing SQL injection vulnerabilities.
* **Error Handling:** Gracefully handles requests for non-existing resources or invalid operations (e.g., attempting to add/update team-level information).
* **Character Encoding:** Supports UTF-8 encoding for team and player names.

### Client-Side Interface (Frontend)

* **API Client Simulation:** A web-based interface built with **HTML, CSS, and JavaScript** that mimics a simplified API client (like Postman).
* **HTTP Method Selection:** Drop-down menu to select HTTP methods (GET, POST, PUT, DELETE).
* **Resource Path Input:** Text field for entering API resource paths.
* **Request Body Editor:** Text area for composing JSON request bodies.
* **Dynamic Interaction:** Sends requests to the web service using **Ajax**.
* **Response Display:** Shows HTTP status codes and response bodies received from the API.
* **Clear Functionality:** Button to clear response display areas.
* **API Documentation:** Includes an embedded HTML description of the web service's functionality and usage.

## Technical Stack

* **Backend:** PHP (with PDO)
* **Database:** MySQL
* **Frontend:** HTML, CSS, JavaScript (Ajax)
* **Tools:** `uglifyjs-3` for JavaScript minification.

## Skills Demonstrated

* **RESTful API Development:** Designing and implementing a fully functional RESTful web service with CRUD operations.
* **Backend Web Development:** Strong proficiency in PHP for server-side logic, API creation, and database interaction.
* **Database Management:** Designing scalable MySQL databases and utilizing PDO for secure transactions.
* **Frontend Development:** Building interactive web interfaces with HTML, CSS, and JavaScript (Ajax) for consuming APIs.
* **Data Serialization:** Handling JSON data for both requests and responses.
* **API Security:** Implementing measures to safeguard against common web vulnerabilities.
* **Problem Solving:** Addressing complex requirements such as resource path handling, error responses, and data integrity.
* **Clean Code Practices:** Adherence to coding standards, including commenting and referencing external sources.
