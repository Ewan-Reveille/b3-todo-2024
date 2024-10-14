# b3-todo-2024
b3-todo-2024

To-Do List

Description :
This web application is a simple To-Do List built using HTML, PHP, and styled with Tailwind CSS. It allows users to add and remove tasks, with tasks being stored temporarily in session memory. No database or persistence is used, so the list is reset whenever the browser is closed or the session expires.

Features :
Add a task to the To-Do List.
Remove a task from the To-Do List.
Non-persistent list: The list is cleared when the browser is closed or the session expires.
Prerequisites
You need a server capable of running PHP (e.g., XAMPP, MAMP, WAMP, or a LAMP stack) to run this project.

Installation and Usage :
1. Clone the repository
-bash
-Copier le code
-git clone https://github.com/your-username/your-project.git

2. Open the project
-Navigate to the project folder
-bash
-Copier le code
-cd your-project
-Make sure you're running a local server (e.g., via XAMPP or MAMP), and open the index.php file in your browser.

3. Add and Remove Tasks
-Type a new task in the input bar.
-Click the Add button to add the task to the list.
-Click the Delete button next to each task to remove it from the list.

Project Structure :
.
├── index.html          # Main HTML file
├── index.php          # Main PHP file with session-based task management
└── README.md          # This file

File Explanation :
index.php: Contains the HTML structure, PHP logic for adding and removing tasks, and session management for temporary storage.
Technologies Used
HTML: For the page structure.
Tailwind CSS: For styling (via CDN).
PHP: For task addition and removal logic using sessions.
Git: Used for version control and tracking changes in the project.

Credits :
Developed by Eliot.