DROP DATABASE IF EXISTS whisper;

CREATE DATABASE whisper DEFAULT CHARACTER SET = utf8;

USE whisper;

CREATE TABLE users (
    id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    role ENUM('user', 'admin') DEFAULT 'user',
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) UNIQUE, -- most probably will not be implemented...
    hashed_password CHAR(60) NOT NULL, -- i am using bycrypt so char(60) should be good...
    active BOOLEAN DEFAULT 1, -- this also will not be implemented i think
    public_key TEXT NOT NULL,
    encrypted_private_key TEXT NOT NULL -- the user should be the only one knowing the actual unencrypted private key
) ENGINE = InnoDB;

CREATE TABLE messages (
    id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    sent_at_day DATE, -- even if null it is not a big deal i guess...
    sent_at_hour TIME, -- maybe i could use it to make the interface better but who cares
    -- hashed_message CHAR(60) NOT NULL,
    encrypted_content TEXT NOT NULL,
    sender BIGINT NOT NULL,
    receiver BIGINT NOT NULL,
    should_save BOOLEAN DEFAULT 0, -- maybe i could let users decide to make some messages permanent

    FOREIGN KEY (sender) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver) REFERENCES users(id) ON DELETE CASCADE
) ENGINE = InnoDB;

CREATE INDEX idx_sender ON messages(sender);
CREATE INDEX idx_receiver ON messages(receiver);