# Whisper

A secure, encrypted chat application that keeps your conversations private - because your secrets are safer with us than in your browser history.

## Features

- **End-to-End Encryption**: Your messages are encrypted before they even think about leaving your device
- **User Authentication**: Secure login system to keep the nosy neighbors out
- **Real-time Messaging**: Because waiting for messages is so 2010
- **Inbox Management**: Keep track of your conversations like a digital postmaster
- **Modern UI**: Dark theme by default (we're not savages like that)

## Getting Started

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- A web server (Apache/Nginx) - XAMPP works great!
- A sense of adventure (optional but recommended)

### Local installation

1. Clone this repository:
   ```bash
   git clone https://github.com/a9sk/whisper.git
   ```

2. Set up your database:
   - Create a new MySQL database
   - Import the schema from `database/schema.sql`
   - Update `config/db_connection.php` with your database credentials

3. Configure your web server:
   - Point your web server to the `public` directory
   - Make sure PHP has write permissions to the necessary directories

4. Start your web server and visit:
   ```
   http://localhost/whisper
   ```

## Configuration

The application can be configured through the following files:
- `config/db_connection.php`: Database settings
- `config/config.php`: Application settings
- `config/encryption.php`: Encryption settings (coming soon!)

## Tech Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **UI Framework**: Bootstrap 5
- **Icons**: Font Awesome
- **Security**: Custom encryption (coming soon!)

## Future Plans

- [ ] End-to-end encryption implementation
- [ ] Real-time message delivery
- [ ] File sharing capabilities
- [ ] Group chat functionality
- [ ] Message expiration
- [ ] User blocking system
- [ ] And much more! (We're not psychic, but we're working on it)

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
