USE ITforum;

-- insert three users
INSERT INTO users (user_name, user_password, user_email, user_created_time)
  VALUE ("wangyexin", "1q2w3e4r", "wangyexin199541@163.com", NOW());
INSERT INTO users (user_name, user_password, user_email, user_created_time)
  VALUE ("wuzhengda", "111111111", "wuzhengda@163.com", NOW());
INSERT INTO users (user_name, user_password, user_email, user_created_time)
  VALUE ("songxinchao", "1234567890", "songxinchao@163.com", NOW());

-- insert an admin
INSERT INTO admin(admin_id)
  VALUE (3);

-- create a category
INSERT INTO categories(category_name, category_created_time, category_description)
  VALUE ("Algorithms", NOW(), "Discuss various algorthims!");

-- wuzhengda followed wangyexin
-- check if wangyexin's follower's number + 1 and wuzhengda's following number + 1
-- trigger: follow_number_after_insert
INSERT INTO follow(follower_id, following_id)
  VALUE (2, 1);

-- wuzhengda followed songxinchao
INSERT INTO follow(follower_id, following_id) VALUE (2, 3);

-- wuzhengda unfollowed songxinchao
-- check if wuzhengda's following number - 1 and songxinchao's follower number + 1
-- trigger: follow_number_after_delete
DELETE FROM follow WHERE follower_id = 2 AND following_id = 3;

-- wangyexin create a new thread
-- check if wangyexin's experience point has + 5.
-- trigger: experience_after_create_thread
INSERT INTO threads(thread_title, thread_created_time, thread_last_reply_time, thread_category_id,
                    thread_created_user_id)
  VALUE ("What is the best sort algorthims?", NOW(), NOW(), 1, 1);

-- wangyexin create a new post
-- check if wangyexin's experience point has + 1 and threads.last_reply_time changed to the new post's time
-- trigger: experience_and_last_reply_time_after_create_post
INSERT INTO posts(post_content, post_created_time, post_created_user_id, post_thread_id, post_reply_id)
VALUE ("quick sort!", NOW(), 1, 1, NULL);


-- wuzhengda likes this thread, and songxinchao dislikes this thread
-- check if this post's like number + 1 and dislike nuner + 1
-- trigger: thread_like_dislike_number_after_like
INSERT INTO like_thread(like_thread_user_id, like_thread_thread_id, like_thread_status) VALUE (2, 1, 1);
INSERT INTO like_thread(like_thread_user_id, like_thread_thread_id, like_thread_status) VALUE (3, 1, 0);

-- wuzhengda likes this post, and songxinchao dislikes this post
-- check if this post's like number + 1 and dislike nuner + 1
-- trigger: post_like_dislike_number_after_like
INSERT INTO like_post(like_post_user_id, like_post_post_id, like_post_status) VALUE (2, 1, 1);
INSERT INTO like_post(like_post_user_id, like_post_post_id, like_post_status) VALUE (3, 1, 0);

-- test function get_user_level
SELECT get_user_level(99);
SELECT get_user_level(100);
SELECT get_user_level(199);
SELECT get_user_level(200);


-- test function isAdmin
SELECT isAdmin(1);
SELECT isAdmin(2);
SELECT isAdmin(3);

-- test procedure promote_user_to_moderator
-- promote admin songxinchao, should return false
SET @result = NULL;
CALL promote_user_to_moderator(3, 1, NOW(), @result);
SELECT @result;

-- promote wangyexin in category 1, should return true
SET @result = NULL;
CALL promote_user_to_moderator(1, 1, NOW(), @result);
SELECT @result;

-- check if wangyexin is moderator under category 1, should return true
-- test function isModerator
SELECT isModerator(1, 1);

-- check if songxinchao is moderator under category 1, should return false
SELECT isModerator(3, 1);


-- promote wangyexin again, should return false
SET @result = NULL;
CALL promote_user_to_moderator(1, 1, NOW(), @result);
SELECT @result;

-- create another category
INSERT INTO categories(category_name, category_created_time, category_description)
  VALUE ("Machine learning", NOW(), "ML is the future!");

-- promote wangyexin under new category
SET @result = NULL;
CALL promote_user_to_moderator(1, 2, NOW(), @result);
SELECT @result;

-- test procedure ban_user_in_category
-- can't ban an admin. should return false
SET @result = NULL;
CALL ban_user_in_category(3, 1, 1, NOW(), '2019-04-19 20:30:17', @result);
SELECT @result;

-- can't ban an moderator, should return false
SET @result = NULL;
CALL ban_user_in_category(1, 1, 1, NOW(), '2019-04-19 20:30:17', @result);
SELECT @result;

-- can't ban a user if the moderator_id is not correct
SET @result = NULL;
CALL ban_user_in_category(2, 3, 1, NOW(), '2019-04-19 20:30:17', @result);
SELECT @result;

-- ban wuzhengda under category 1
SET @result = NULL;
CALL ban_user_in_category(2, 1, 1, NOW(), '2019-04-19 20:33:17', @result);
SELECT @result;




