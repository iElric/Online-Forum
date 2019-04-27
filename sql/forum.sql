-- create the database forum
DROP DATABASE IF EXISTS ITforum;

CREATE DATABASE ITforum;

-- select the database
USE ITforum;

-- create the tables

/**
 This is the entity part of the database.
 */

CREATE TABLE users
(
  user_id               INT PRIMARY KEY AUTO_INCREMENT,
  user_name             VARCHAR(50) UNIQUE  NOT NULL,
  user_password         VARCHAR(255)        NOT NULL,
  -- 254 is the maximum length that an email address can be according to the RFC definitions
  user_email            VARCHAR(254) UNIQUE NOT NULL,
  user_following_number INT DEFAULT 0       NOT NULL,
  user_follower_number  INT DEFAULT 0       NOT NULL,
  -- use this to call function to check user level
  user_experience_point INT DEFAULT 0       NOT NULL,
  user_created_time     DATETIME            NOT NULL

);

CREATE TABLE moderators
(
  moderator_id                     INT PRIMARY KEY,
  moderator_manage_category_number INT DEFAULT 0 NOT NULL,
  CONSTRAINT moderators_fk_users FOREIGN KEY (moderator_id) REFERENCES users (user_id)
    ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE admin
(
  admin_id INT PRIMARY KEY,
  CONSTRAINT admin_fk_users FOREIGN KEY (admin_id) REFERENCES users (user_id)
    ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE categories
(
  category_id           INT PRIMARY KEY AUTO_INCREMENT,
  category_name         VARCHAR(50) UNIQUE NOT NULL,
  category_created_time DATETIME           NOT NULL,
  -- description can be null
  category_description  VARCHAR(255)
);

CREATE TABLE threads
(
  thread_id              INT PRIMARY KEY AUTO_INCREMENT,
  thread_title           VARCHAR(50)   NOT NULL,
  thread_created_time    DATETIME      NOT NULL,
  thread_last_reply_time DATETIME      NOT NULL,
  thread_category_id     INT           NOT NULL,
  thread_created_user_id INT           NOT NULL,
  thread_liked_number    INT DEFAULT 0 NOT NULL,
  thread_disliked_number INT DEFAULT 0 NOT NULL,
  CONSTRAINT threads_fk_categories FOREIGN KEY (thread_category_id) REFERENCES categories (category_id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT threads_fk_users FOREIGN KEY (thread_created_user_id) REFERENCES users (user_id)
    ON UPDATE CASCADE ON DELETE RESTRICT
);


CREATE TABLE posts
(
  post_id              INT PRIMARY KEY AUTO_INCREMENT,
  post_content         VARCHAR(1000) NOT NULL,
  post_created_time    DATETIME      NOT NULL,
  post_created_user_id INT           NOT NULL,
  post_thread_id       INT           NOT NULL,
  post_liked_number    INT DEFAULT 0 NOT NULL,
  post_disliked_number INT DEFAULT 0 NOT NULL,
  -- refer to the reply post id, if this post does not reply to others, use null
  post_reply_id        INT DEFAULT NULL,
  CONSTRAINT posts_fk_users FOREIGN KEY (post_created_user_id) REFERENCES users (user_id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT posts_fk_thread FOREIGN KEY (post_thread_id) REFERENCES threads (thread_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT posts_fk_posts FOREIGN KEY (post_reply_id) REFERENCES posts (post_id)
    ON UPDATE CASCADE ON DELETE SET NULL

);

/**
 This is the relation part of the database.
 */

CREATE TABLE message
(
  message_id      INT PRIMARY KEY AUTO_INCREMENT,
  message_from_id INT           NOT NULL,
  message_to_id   INT           NOT NULL,
  message_content VARCHAR(1000) NOT NULL,
  message_time    DATETIME      NOT NULL,
  CONSTRAINT messages_from_fk_users FOREIGN KEY (message_from_id) REFERENCES users (user_id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT messages_to_fk_users FOREIGN KEY (message_to_id) REFERENCES users (user_id)
    ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE follow
(
  -- the user who follow someone
  follower_id  INT NOT NULL,
  -- the user who is followed
  following_id INT NOT NULL,
  PRIMARY KEY (follower_id, following_id),
  CONSTRAINT follows_follower_fk_users FOREIGN KEY (follower_id) REFERENCES users (user_id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT follows_following_fk_users FOREIGN KEY (following_id) REFERENCES users (user_id)
    ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE manage
(
  manage_moderator_id INT      NOT NULL,
  manage_category_id  INT      NOT NULL,
  manage_start_time   DATETIME NOT NULL,
  PRIMARY KEY (manage_moderator_id, manage_category_id),
  CONSTRAINT manage_fk_moderators FOREIGN KEY (manage_moderator_id) REFERENCES moderators (moderator_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT manage_fk_category FOREIGN KEY (manage_category_id) REFERENCES categories (category_id)
    ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE like_thread
(
  like_thread_user_id   INT        NOT NULL,
  like_thread_thread_id INT        NOT NULL,
  PRIMARY KEY (like_thread_user_id, like_thread_thread_id),
  -- true means like, false means dislike
  like_thread_status    TINYINT(1) NOT NULL,
  CONSTRAINT like_thread_fk_users FOREIGN KEY (like_thread_user_id) REFERENCES users (user_id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT like_thread_fk_threads FOREIGN KEY (like_thread_thread_id) REFERENCES threads (thread_id)
    ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE like_post
(
  like_post_user_id INT        NOT NULL,
  like_post_post_id INT        NOT NULL,
  PRIMARY KEY (like_post_user_id, like_post_post_id),
  -- true means like, false means dislike
  like_post_status  TINYINT(1) NOT NULL,
  CONSTRAINT like_post_fk_users FOREIGN KEY (like_post_user_id) REFERENCES users (user_id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT like_post_fk_posts FOREIGN KEY (like_post_post_id) REFERENCES posts (post_id)
    ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE view
(
  view_user_id   INT      NOT NULL,
  view_thread_id INT      NOT NULL,
  view_time      DATETIME NOT NULL,
  PRIMARY KEY (view_user_id, view_thread_id, view_time),
  CONSTRAINT view_fk_users FOREIGN KEY (view_user_id) REFERENCES users (user_id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT view_fk_threads FOREIGN KEY (view_thread_id) REFERENCES threads (thread_id)
    ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE ban
(
  ban_user_id      INT      NOT NULL,
  -- need ban_fk_moderators to set null
  ban_moderator_id INT,
  ban_category_id  INT      NOT NULL,
  PRIMARY KEY (ban_user_id, ban_category_id),
  ban_start_time   DATETIME NOT NULL,
  ban_end_time     DATETIME NOT NULL,
  CONSTRAINT ban_fk_users FOREIGN KEY (ban_user_id) REFERENCES users (user_id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT ban_fk_moderators FOREIGN KEY (ban_moderator_id) REFERENCES moderators (moderator_id)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT ban_fk_categories FOREIGN KEY (ban_category_id) REFERENCES categories (category_id)
    ON UPDATE CASCADE ON DELETE RESTRICT
);

/**
  The trigger part.
 */

-- trigger for user_following_number and user_follower_number
DROP TRIGGER IF EXISTS follow_number_after_insert;
DELIMITER $$
CREATE TRIGGER follow_number_after_insert
  AFTER INSERT
  ON follow
  FOR EACH ROW
BEGIN
  UPDATE users SET users.user_following_number = users.user_following_number + 1 WHERE users.user_id = NEW.follower_id;
  UPDATE users SET users.user_follower_number = users.user_follower_number + 1 WHERE users.user_id = NEW.following_id;
END $$
DELIMITER ;

DROP TRIGGER IF EXISTS follow_number_after_delete;
DELIMITER $$
CREATE TRIGGER follow_number_after_delete
  AFTER DELETE
  ON follow
  FOR EACH ROW
BEGIN
  UPDATE users SET users.user_following_number = users.user_following_number - 1 WHERE user_id = OLD.follower_id;
  UPDATE users SET users.user_follower_number = users.user_follower_number - 1 WHERE user_id = OLD.following_id;
END $$
DELIMITER ;

-- trigger for user_experience point, deletion won't affect experience point
DROP TRIGGER IF EXISTS experience_after_create_thread;
DELIMITER $$
CREATE TRIGGER experience_after_create_thread
  AFTER INSERT
  ON threads
  FOR EACH ROW
BEGIN
  UPDATE users
  SET users.user_experience_point = users.user_experience_point + 5
  WHERE users.user_id = NEW.thread_created_user_id;
END $$
DELIMITER ;

-- trigger for thread_last_reply_time and experience_point
-- mysql 5.0 doesn't allow multiple trigger for same action, so merge them together.
DROP TRIGGER IF EXISTS experience_and_last_reply_time_after_create_post;
DELIMITER $$
CREATE TRIGGER experience_and_last_reply_time_after_create_post
  AFTER INSERT
  ON posts
  FOR EACH ROW
BEGIN
  UPDATE users
  SET users.user_experience_point = users.user_experience_point + 1
  WHERE users.user_id = NEW.post_created_user_id;
  UPDATE threads
  SET threads.thread_last_reply_time = NEW.post_created_time
  WHERE threads.thread_id = NEW.post_thread_id;

END $$
DELIMITER ;

-- trigger for like and dislike number of thread
DROP TRIGGER IF EXISTS thread_like_dislike_number_after_like;
DELIMITER $$
CREATE TRIGGER thread_like_dislike_number_after_like
  AFTER INSERT
  ON like_thread
  FOR EACH ROW
BEGIN
  IF NEW.like_thread_status IS TRUE THEN
    UPDATE threads SET threads.thread_liked_number = threads.thread_liked_number + 1;
  ELSE
    UPDATE threads SET threads.thread_disliked_number = threads.thread_disliked_number + 1;
  END IF;
END $$
DELIMITER ;


-- trigger for like and dislike number of post
DROP TRIGGER IF EXISTS post_like_dislike_number_after_like;
DELIMITER $$
CREATE TRIGGER post_like_dislike_number_after_like
  AFTER INSERT
  ON like_post
  FOR EACH ROW
BEGIN
  IF NEW.like_post_status IS TRUE THEN
    UPDATE posts SET posts.post_liked_number = posts.post_liked_number + 1;
  ELSE
    UPDATE posts SET posts.post_disliked_number = posts.post_disliked_number + 1;
  END IF;
END $$
DELIMITER ;

DROP TRIGGER IF EXISTS manage_category_number_after_insert;
DELIMITER $$
CREATE TRIGGER manage_category_number_after_insert
  AFTER INSERT
  ON manage
  FOR EACH ROW
BEGIN
  UPDATE moderators
  SET moderators.moderator_manage_category_number = moderators.moderator_manage_category_number + 1
  WHERE moderators.moderator_id = NEW.manage_moderator_id;

END $$
DELIMITER ;

/**
  The function part.
 */


-- check the user_level with given user_experience_point
DROP FUNCTION IF EXISTS get_user_level;
DELIMITER $$
CREATE FUNCTION get_user_level(experience_point INT) RETURNS INT
BEGIN
  RETURN experience_point DIV 100;
END $$
DELIMITER ;

-- check if the given user is moderator in given category
DROP FUNCTION IF EXISTS isModerator;
DELIMITER $$
CREATE FUNCTION isModerator(given_user_id INT, given_category_id INT) RETURNS TINYINT(1)
BEGIN
  DECLARE result TINYINT(1);
  SELECT EXISTS(SELECT 1
                FROM manage
                WHERE manage_moderator_id = given_user_id
                  AND manage_category_id = given_category_id
                LIMIT 1) INTO result;
  RETURN result;
END $$
DELIMITER ;

-- check if the given user is admin
DROP FUNCTION IF EXISTS isAdmin;
DELIMITER $$
CREATE FUNCTION isAdmin(given_user_id INT) RETURNS TINYINT(1)
BEGIN
  DECLARE result TINYINT(1);
  SELECT EXISTS(SELECT 1
                FROM admin
                WHERE admin_id = given_user_id
                LIMIT 1) INTO result;
  RETURN result;
END $$
DELIMITER ;

-- check if the given user is banned in given category
DROP FUNCTION IF EXISTS isBanned;
DELIMITER $$
CREATE FUNCTION isBanned(given_user_id INT, given_category_id INT) RETURNS TINYINT(1)
BEGIN
  DECLARE result TINYINT(1);
  SELECT EXISTS(SELECT 1
                FROM ban
                WHERE ban_user_id = given_user_id
                  AND ban_category_id = given_category_id
                LIMIT 1) INTO result;
  RETURN result;
END $$
DELIMITER ;


/**
  The procedure part.
 */

-- return true if the promotion succeeded, false otherwise
DROP PROCEDURE IF EXISTS promote_user_to_moderator;
DELIMITER $$
CREATE PROCEDURE promote_user_to_moderator(IN given_user_id INT, IN given_category_id INT,
                                           IN given_start_time DATETIME, OUT result TINYINT(1))
BEGIN
  -- if the user is already a moderator under this category or is already a admin, return false
  IF isModerator(given_user_id, given_category_id) OR isAdmin(given_user_id) THEN
    SELECT FALSE INTO result;
  ELSE
    -- notice the user can already in moderator table, use the insert ignore
    INSERT IGNORE INTO moderators (moderator_id) VALUE (given_user_id);
    INSERT INTO manage (manage_moderator_id, manage_category_id, manage_start_time)
      VALUE (given_user_id, given_category_id, given_start_time);
    SELECT TRUE INTO result;
  END IF;
END
$$
DELIMITER ;


-- return true if the ban succeeded, false otherwise
DROP PROCEDURE IF EXISTS ban_user_in_category;
DELIMITER $$
CREATE PROCEDURE ban_user_in_category(IN given_user_id INT, IN given_moderator_id INT, IN given_category_id INT,
                                      IN given_start_time DATETIME, IN given_end_time DATETIME, OUT result TINYINT(1))
BEGIN
  DECLARE currentEnd DATETIME DEFAULT NULL;
  -- can't ban admin and other moderator under this category, or if the moderator_id is not valid
  IF isAdmin(given_user_id) OR isModerator(given_user_id, given_category_id) OR
      !isModerator(given_moderator_id, given_category_id) THEN
    SELECT FALSE INTO result;
  ELSE
    SELECT ban_end_time INTO currentEnd
    FROM ban
    WHERE ban_user_id = given_user_id
      AND ban_category_id = given_category_id
    LIMIT 1;
    -- if has no ban right now, ban this user
    IF currentEnd IS NULL THEN
      INSERT INTO ban (ban_user_id, ban_moderator_id, ban_category_id, ban_start_time, ban_end_time)
        VALUE (given_user_id, given_moderator_id, given_category_id, given_start_time, given_end_time);
    ELSE
      -- modify ban time to the new one
      UPDATE ban
      SET ban_end_time = currentEnd
      WHERE ban_user_id = given_user_id
        AND ban_category_id = given_category_id
      LIMIT 1;
    END IF;
    SELECT TRUE INTO result;
  END IF;
END
$$
DELIMITER ;

/**
  The event part.
 */

set global event_scheduler = on;
-- delete the outdated ban record
DROP EVENT IF EXISTS delete_outdated_ban;
CREATE EVENT delete_outdated_ban ON SCHEDULE EVERY 1 MINUTE
  DO
  DELETE
  FROM ban
  WHERE ban_end_time < NOW();







