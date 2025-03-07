





# EmailSpamDetection


## Description

the Email Spam Detection is a web-based application designed to detect and filter spam emails. It allows users to register, log in, compose emails, and view their inbox and spam folders. The system uses a simple keyword-based approach to identify potential spam messages. If a message contains any predefined spam keywords, it is flagged as spam and stored in the spam folder. Otherwise, the message is stored in the inbox.




## Features

1. **User Registration and Login:**
   - Users can register by providing their first name, last name, username, and password.
   - Passwords are securely hashed using `password_hash()` before being stored in the database.

2. **Spam Detection:**
   - The system detects spam by checking for predefined keywords in the email message.


3. **Compose Email:**
   - Logged-in users can compose and send emails to other users.
   - The system automatically checks the message for spam before sending it.

4. **Inbox and Spam Folder:**
   - Users can view their inbox to see received emails.

5. **Logout:**
   - Users can log out of the system, which destroys the session and redirects them to the login page.

6. **Responsive Design:**
   - The user interface is designed to be responsive and user-friendly, with a clean and modern layout.

## Demo

https://github.com/user-attachments/assets/845fc8bb-f625-4509-9396-b75ee97f314d

