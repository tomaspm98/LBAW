-- QUERY TO RUN TO SETUP THE LBAW2311 SCHEMA AND CHANGE TO THAT SCHEMA
CREATE SCHEMA IF NOT EXISTS lbaw2311;
SET search_path TO lbaw2311;
SHOW search_path;

--QUERY TO RUN TO SEE THE TABLES IN THE LBAW2311 SCHEMA
SELECT * FROM information_schema.tables WHERE table_schema = 'lbaw2311';


------------------------ TESTS ------------------------

-- Test full text search
select * from question;
SELECT question_id, question_title, ts_rank(tsvectors, query) AS rank
FROM question, to_tsquery('english', 'Python') AS query
WHERE tsvectors @@ query
ORDER BY rank DESC;

--Test trigger to delete
SELECT * FROM member;
SELECT * FROM member WHERE user_id = 3;
DELETE FROM member WHERE user_id = 3;
SELECT * FROM member WHERE user_id = 3;
SELECT * FROM question WHERE content_author = -1;

-- Test trigger to give point for content
select * from member where user_id = 3;
INSERT INTO question (question_title, question_tag, content_author, content_text)
VALUES ('New Question Title', 1, 3, 'This is a new question.');
INSERT INTO answer (question_id, content_author, content_text)
VALUES (2, 3, 'This is a new answer.');
INSERT INTO comment (answer_id, content_author, content_text)
VALUES (2, 3, 'This is a new comment.');
select * from member where user_id = 3;

-- Test trigger to give point for answer
SELECT user_id, user_score FROM member WHERE user_id = 3;
-- Insert a new vote into the 'vote' table to trigger the trigger.
INSERT INTO vote (vote_author, upvote, entity_voted, vote_content_question, vote_content_answer, vote_content_comment)
VALUES (3, 'up', 'answer', NULL, 2, NULL);
-- Check the user's updated score after the trigger has been executed.
SELECT user_id, user_score FROM member WHERE user_id = 3;

-- Test trigger to check if user can answer his own question
INSERT INTO question (question_title, question_tag, content_author, content_text)
VALUES ('Sample Question', 1, 3, 'This is a sample question.');
INSERT INTO answer (question_id, content_author, content_text)
VALUES (1, 3, 'This is an answer to my own question.');
-- Attempt to Insert the Answer
-- Try to insert an answer to the question created in Step 1 (which is their own question).
-- This should trigger the 'member_answer_own_question' trigger and raise an exception.


-- Test trigger if user receives notification when someone answers his question
INSERT INTO question (question_title, question_tag, content_author, content_text)
VALUES ('Sample Question', 1, 3, 'This is a sample question.');
select * from question where content_author = 3;
INSERT INTO answer (question_id, content_author, content_text)
VALUES (3, 2, 'This is an answer to the sample question.');
-- Check Notifications
SELECT * FROM notification WHERE notification_user = 3;

-- Test trigger if user receives notification when someone comments on his answer
INSERT INTO answer (question_id, content_author, content_text)
VALUES (2, 3, 'This is an answer to a question.');
SELECT * FROM answer WHERE content_author = 3;
INSERT INTO comment (answer_id, content_author, content_text)
VALUES (7, 3, 'This is a comment on the answer.');
SELECT * FROM notification WHERE notification_user = 3;

-- Test trigger if user receives notification when he receives a badge
INSERT INTO userbadge (user_id, badge_id)
VALUES (3, 2);
-- Check Notifications
SELECT * FROM notification WHERE notification_user = 3;

--Test triggers 07 to 10
-- Test Trigger 07: User registration badge
INSERT INTO member (username, user_email, user_password, picture, user_birthdate, user_score)
VALUES ('newuser', 'newuser@example.com', 'newpassword', '/picture/avatar4.jpg', '2000-05-10', 0);

-- Check for the 'Welcome' badge in the userbadge table
SELECT * FROM userbadge WHERE user_id = (SELECT user_id FROM member WHERE username = 'newuser');

-- Test Trigger 08: Bronze badge
-- It is already tested with normal populate
select * from notification where notification_user = 1;
-- Trigger 09 and 10 have similar structures to Trigger 08, so they are not tested.