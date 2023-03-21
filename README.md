Positron Queue Listener
This project is a Laravel-based application that listens to Positron Queues, processes messages containing vehicle position data, and stores the data in a database.

Features
Connects to a STOMP server and subscribes to specific queues
Processes incoming messages and extracts vehicle position data (vehicle plate, latitude, and longitude)
Stores the processed data in a database, updating existing records if the vehicle plate is already present
Requirements
PHP 7.3 or higher
Laravel 8.x or higher
Composer for managing PHP dependencies
A STOMP server to connect to and receive messages from
A database server (e.g., MySQL) to store the processed vehicle position data
