--
-- Use a specific schema and set it as default - thingy.
--
DROP SCHEMA IF EXISTS lbaw2311 CASCADE;
CREATE SCHEMA IF NOT EXISTS lbaw2311;
SET search_path TO lbaw2311;

------------------------------------------------------------------------------------------------------------------------
-- Project: Q&A Website
-- Course: LBAW 2023/2024
-- Group: 11
------------------------------------------------------------------------------------------------------------------------
-- Authors:
-- António José Salazar Correia, up201804832@up.pt
-- Gonçalo Nuno Leitão Pinho da Costa, up202103336@up.pt
-- Tomás Pereira Maciel, up202006845@up.pt
-- Ricardo Miguel Matos Oliveira Peralta, up2206392@up.pt
------------------------------------------------------------------------------------------------------------------------





------------------------------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------------------------
-- SCHEMA FOR LBAW2311
------------------------------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------------------------

--DROP SCHEMA IF EXISTS lbaw2311 CASCADE;
--CREATE SCHEMA IF NOT EXISTS lbaw2311;
--SET search_path TO lbaw2311;
--SHOW search_path;

------------------------------------------------------------------------------------------------------------------------
-- DROP ALL FROM LBAW2311 SCHEMA
------------------------------------------------------------------------------------------------------------------------
-- DROP TABLES FROM LBAW2311 SCHEMA
DROP TABLE IF EXISTS admin;
DROP TABLE IF EXISTS moderator CASCADE;
DROP TABLE IF EXISTS userbadge;
DROP TABLE IF EXISTS questionnotification;
DROP TABLE IF EXISTS answernotification;
DROP TABLE IF EXISTS commentnotification;
DROP TABLE IF EXISTS badgenotification;
DROP TABLE IF EXISTS notification;
DROP TABLE IF EXISTS answer CASCADE;
DROP TABLE IF EXISTS question CASCADE;
DROP TABLE IF EXISTS comment CASCADE;
DROP TABLE IF EXISTS content CASCADE;
DROP TABLE IF EXISTS member CASCADE;
DROP TABLE IF EXISTS badge;
DROP TABLE IF EXISTS tag;
DROP TABLE IF EXISTS vote;
DROP TABLE IF EXISTS report;
DROP TABLE IF EXISTS userfollowquestion;
-- DROP TYPES FROM LBAW2311 SCHEMA
DROP TYPE IF EXISTS voteType;
DROP TYPE IF EXISTS entityType;
DROP TYPE IF EXISTS reportReasonType;
DROP TYPE IF EXISTS notificationType;


-- Drop triggers
DROP TRIGGER IF EXISTS award_user_point_vote ON vote;
DROP TRIGGER IF EXISTS award_user_point_question ON question;
DROP TRIGGER IF EXISTS award_user_point_answer ON answer;
DROP TRIGGER IF EXISTS award_user_point_comment ON comment;
DROP TRIGGER IF EXISTS member_deletion_trigger ON member;
DROP TRIGGER IF EXISTS question_search_update on question;
DROP TRIGGER IF EXISTS notification_answers on answer;
DROP TRIGGER IF EXISTS notification_comment on comment;
DROP TRIGGER IF EXISTS notification_badges on userbadge;
DROP TRIGGER IF EXISTS member_answer_own_question ON answer;
DROP TRIGGER IF EXISTS register_badge ON member;
DROP TRIGGER IF EXISTS bronze_badge ON member;
DROP TRIGGER IF EXISTS silver_badge ON member;
DROP TRIGGER IF EXISTS gold_badge ON member;
DROP FUNCTION IF EXISTS member_answer_own_question();
DROP FUNCTION IF EXISTS notification_comments();
DROP FUNCTION IF EXISTS notification_badges();
DROP FUNCTION IF EXISTS notification_answers();
DROP FUNCTION IF EXISTS member_answer_own_question();
DROP FUNCTION IF EXISTS award_user_point_vote();
DROP FUNCTION IF EXISTS award_user_point();
DROP FUNCTION IF EXISTS anonymize_content();
DROP FUNCTION IF EXISTS question_search_update();
DROP FUNCTION IF EXISTS register_badge();
DROP FUNCTION IF EXISTS bronze_badge();
DROP FUNCTION IF EXISTS silver_badge();
DROP FUNCTION IF EXISTS gold_badge();




------------------------------------------------------------------------------------------------------------------------
-- CREATE TYPES/DOMAINS FOR LBAW2311 SCHEMA
------------------------------------------------------------------------------------------------------------------------
CREATE TYPE voteType AS ENUM ('up', 'down', 'out');
CREATE TYPE entityType AS ENUM ('question', 'answer', 'comment');
CREATE TYPE reportReasonType AS ENUM ('spam', 'offensive', 'Rules Violation', 'Inappropriate tag');
CREATE TYPE notificationType AS ENUM ('question', 'answer', 'comment', 'badge');

------------------------------------------------------------------------------------------------------------------------
-- CREATE TABLES FOR LBAW2311 SCHEMA
------------------------------------------------------------------------------------------------------------------------
-- Create the User table (R01)
CREATE TABLE member (
    user_id SERIAL PRIMARY KEY,
    username VARCHAR(25) UNIQUE NOT NULL,
    user_email VARCHAR(25) UNIQUE NOT NULL,
    user_password VARCHAR(255) NOT NULL,
    picture VARCHAR(255),
    user_birthdate TIMESTAMP NOT NULL,
    user_creation_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    user_score INT DEFAULT 0,
	CONSTRAINT check_time CHECK (EXTRACT(YEAR FROM user_creation_date) - EXTRACT(YEAR FROM user_birthdate) > 12 )
);

-- Create the Tag table (R10)
CREATE TABLE tag (
    tag_id SERIAL PRIMARY KEY,
    tag_name VARCHAR(255) UNIQUE NOT NULL,
    tag_description VARCHAR(255)
);

-- Create the Admin table (R02)
CREATE TABLE admin (
    user_id INT PRIMARY KEY,
    FOREIGN KEY (user_id) REFERENCES member(user_id)
);

-- Create the Moderator table (R03)
CREATE TABLE moderator (
    user_id INT PRIMARY KEY,
    tag_id INT NOT NULL,
    assignment TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES member(user_id),
    FOREIGN KEY (tag_id) REFERENCES tag(tag_id)
);

-- Create the Badge table (R04)
CREATE TABLE badge (
    badge_id SERIAL PRIMARY KEY,
    badge_name VARCHAR(25) UNIQUE NOT NULL,
    badge_description VARCHAR(255) NOT NULL
);

-- Create the UserBadge table (R05)
CREATE TABLE userbadge (
    userbadge_id SERIAL PRIMARY KEY,
    user_id INT,
    badge_id INT,
    user_badge_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES member(user_id),
    FOREIGN KEY (badge_id) REFERENCES badge(badge_id)
);

-- Create the Notification table (R06)
CREATE TABLE notification (
    notification_id SERIAL PRIMARY KEY,
    notification_user INT,
    notification_content VARCHAR(255) NOT NULL,
    notification_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    notification_is_read BOOLEAN NOT NULL DEFAULT FALSE,
    notification_type notificationType NOT NULL,
    FOREIGN KEY (notification_user) REFERENCES member(user_id)
);

-- Create the Answer table (R08)
CREATE TABLE answer (
    answer_id SERIAL PRIMARY KEY,
    question_id INT,
    --Common to content
    content_author INT,
    content_creation_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    content_text TEXT NOT NULL,
    content_is_edited BOOLEAN NOT NULL DEFAULT FALSE,
    content_is_visible BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (content_author) REFERENCES member(user_id)
);

-- Create the Question table (R07)
CREATE TABLE question (
    question_id SERIAL PRIMARY KEY,
    question_title VARCHAR(255) NOT NULL,
    question_tag INT,
    correct_answer INT,
    --Common to content
    content_author INT,
    content_creation_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    content_text TEXT NOT NULL,
    content_is_edited BOOLEAN NOT NULL DEFAULT FALSE,
    content_is_visible BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (content_author) REFERENCES member(user_id)
);

-- Create a foreign key for question_tag in the question table
ALTER TABLE question
    ADD CONSTRAINT FK_Tag_Question
        FOREIGN KEY (question_tag) REFERENCES tag(tag_id);

-- Create a foreign key for correct_answer in the question table
ALTER TABLE question
    ADD CONSTRAINT FK_Correct_Answer
        FOREIGN KEY (correct_answer) REFERENCES answer(answer_id);

--
ALTER TABLE answer
    ADD CONSTRAINT fk_question
        FOREIGN KEY (question_id) REFERENCES question(question_id);

ALTER TABLE userbadge DROP CONSTRAINT userbadge_user_id_fkey;

ALTER TABLE userbadge
ADD CONSTRAINT userbadge_user_id_fkey
FOREIGN KEY (user_id)
REFERENCES member(user_id)
ON DELETE CASCADE;

ALTER TABLE notification
DROP CONSTRAINT notification_notification_user_fkey;

ALTER TABLE notification
ADD CONSTRAINT notification_notification_user_fkey
FOREIGN KEY (notification_user)
REFERENCES member(user_id)
ON DELETE CASCADE; 


-- Create the Comment table (R09)
CREATE TABLE comment (
    comment_id SERIAL PRIMARY KEY,
    answer_id INT,
    --Common to content
    content_author INT,
    content_creation_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    content_text TEXT NOT NULL,
    content_is_edited BOOLEAN NOT NULL DEFAULT FALSE,
    content_is_visible BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (content_author) REFERENCES member(user_id),
    FOREIGN KEY (answer_id) REFERENCES answer(answer_id)
);

-- Create the Vote table (R11)
CREATE TABLE vote (
    vote_id SERIAL PRIMARY KEY,
    vote_author INT,
    vote_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    upvote voteType NOT NULL,
    entity_voted entityType NOT NULL,
    vote_content_question INT,
    vote_content_answer INT,
    vote_content_comment INT,
    FOREIGN KEY (vote_author) REFERENCES member(user_id),
    FOREIGN KEY (vote_content_question) REFERENCES question(question_id),
    FOREIGN KEY (vote_content_answer) REFERENCES answer(answer_id),
    FOREIGN KEY (vote_content_comment) REFERENCES comment(comment_id)
);

-- Create the Report table (R12)
CREATE TABLE report (
    report_id SERIAL PRIMARY KEY,
    report_creator INT,
    report_handler INT,
    content_reported_question INT,
    content_reported_answer INT,
    content_reported_comment INT,
    report_reason reportReasonType NOT NULL,
    report_text VARCHAR(255),
    report_dealt BOOLEAN NOT NULL DEFAULT FALSE,
    report_accepted BOOLEAN,
    report_answer VARCHAR(255),
    FOREIGN KEY (report_creator) REFERENCES member(user_id),
    FOREIGN KEY (report_handler) REFERENCES moderator(user_id),
    FOREIGN KEY (content_reported_question) REFERENCES question(question_id),
    FOREIGN KEY (content_reported_answer) REFERENCES answer(answer_id),
    FOREIGN KEY (content_reported_comment) REFERENCES comment(comment_id)
);

-- Create the UserFollowQuestion table (R13)
CREATE TABLE userfollowquestion (
    user_id INT,
    question_id INT,
    follow BOOLEAN NOT NULL,
    FOREIGN KEY (user_id) REFERENCES member(user_id),
    FOREIGN KEY (question_id) REFERENCES question(question_id)
);


------------------------------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------------------------
-- INDEXES
------------------------------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------------------------
-- Performance indexes
CREATE INDEX idx_author_question ON question USING hash (content_author);
CREATE INDEX idx_answer_question ON answer USING hash (question_id);
CREATE INDEX idx_question_date ON question USING btree (content_creation_date);

-- Full-text Search Index
-- Create a ts_vector column for the question table
ALTER TABLE question
ADD COLUMN tsvectors TSVECTOR;
-- Create a function to update the ts_vector column
-- This function is called by the trigger below
CREATE FUNCTION question_search_update() RETURNS TRIGGER AS $$
BEGIN
-- Update the ts_vector column only if the question is inserted
 IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
         setweight(to_tsvector('english', NEW.question_title), 'A') ||
         setweight(to_tsvector('english', NEW.content_text), 'B')
        );
 END IF;
-- Update the ts_vector column only if the question title or content text is changed
 IF TG_OP = 'UPDATE' THEN
         IF (NEW.question_title <> OLD.question_title OR NEW.content_text <> OLD.content_text) THEN
           NEW.tsvectors = (
             setweight(to_tsvector('english', NEW.question_title), 'A') ||
             setweight(to_tsvector('english', NEW.content_text), 'B')
           );
         END IF;
    END IF;
RETURN NEW;
END $$
LANGUAGE plpgsql;

-- Trigger before insert or update on question table.
-- This trigger calls the function above to update the ts_vector column
CREATE TRIGGER question_search_update
 BEFORE INSERT OR UPDATE ON question
 FOR EACH ROW
 EXECUTE PROCEDURE question_search_update();

-- GIN index for ts_vectors.
-- This index is used to perform full-text search on the ts_vector column
CREATE INDEX search_idx ON question USING GIN (tsvectors);



------------------------------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------------------------
-- TRIGGERS AND FUNCTIONS
------------------------------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------------------------

-- TRIGGER 01 - Making data anonymous upon a user account deletion (BR12)

-- Delete user, his content appears as deleted user
CREATE FUNCTION anonymize_content()
RETURNS TRIGGER AS $$
BEGIN
    -- Update content authored by the deleted member to "anonymous"
    UPDATE question
    SET content_author = -1
    WHERE content_author = OLD.user_id;

    UPDATE answer
    SET content_author = -1
    WHERE content_author = OLD.user_id;
    
    UPDATE comment
    SET content_author = -1
    WHERE content_author = OLD.user_id;
    
    -- Update vote records by the deleted member to "anonymous"
    UPDATE vote
    SET vote_author = -1
    WHERE vote_author = OLD.user_id;
    
    -- Update report records by the deleted member to "anonymous"
    UPDATE report
    SET report_creator = -1
    WHERE report_creator = OLD.user_id;
    
    -- Update userfollowquestion records by the deleted member to "anonymous"
    DELETE FROM userfollowquestion
    WHERE user_id = OLD.user_id;

    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

-- Create the trigger for the member delete
CREATE TRIGGER member_deletion_trigger
BEFORE DELETE ON member
FOR EACH ROW
EXECUTE FUNCTION anonymize_content();

-- TRIGGER 02 - An user cannot answer his own question (BR05)

CREATE FUNCTION member_answer_own_question() RETURNS TRIGGER AS $$
BEGIN
  IF NEW.content_author = (SELECT question.content_author FROM question WHERE question.question_id= NEW.question_id) THEN
      RAISE EXCEPTION 'A member cant answer his own question';
  END IF;
  RETURN NEW;
END
$$ LANGUAGE 'plpgsql';

CREATE TRIGGER member_answer_own_question
    BEFORE INSERT OR UPDATE OF content_author, question_id ON answer
    FOR EACH ROW
      EXECUTE PROCEDURE member_answer_own_question();


-- TRIGGER 03 - An user receives a notification when someone answers his question.

CREATE FUNCTION notification_answers() RETURNS TRIGGER AS $$
BEGIN
  IF NEW.question_id = (SELECT question.question_id from question where question.question_id = NEW.question_id) THEN
    INSERT INTO notification (notification_user, notification_content,notification_type)
    VALUES ((SELECT question.content_author FROM question WHERE question.question_id = NEW.question_id), ((SELECT username from answer INNER JOIN member ON content_author = member.user_id where answer.answer_id = NEW.answer_id) || ' answered your question ' || (SELECT question_title FROM question WHERE question.question_id = NEW.question_id)), 'answer');
  END IF;
  RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

CREATE TRIGGER notification_answers
AFTER INSERT ON answer
FOR EACH ROW
  EXECUTE PROCEDURE notification_answers();


-- TRIGGER 04 - An user receives a notification when someone comments his answer.

CREATE FUNCTION notification_comments() RETURNS TRIGGER AS $$
BEGIN
  IF NEW.answer_id = (SELECT answer.answer_id from answer where answer.answer_id = NEW.answer_id) THEN
    INSERT INTO notification (notification_user, notification_content,notification_type)
    VALUES ((SELECT answer.content_author FROM answer WHERE answer.answer_id = NEW.answer_id), ((SELECT username from comment INNER JOIN member ON content_author = member.user_id where comment.comment_id = NEW.comment_id) || ' commented your answer to question ' || (SELECT question_title FROM answer INNER JOIN comment ON answer.answer_id=comment.answer_id INNER JOIN question ON answer.question_id = question.question_id WHERE comment.comment_id = NEW.comment_id)), 'comment');
  END IF;
  RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

CREATE TRIGGER notification_comments
AFTER INSERT ON comment
FOR EACH ROW
  EXECUTE PROCEDURE notification_comments();


-- TRIGGER 05 - An user receives a notification when he wins a new badge.

CREATE FUNCTION notification_badges() RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO notification (notification_user, notification_content,notification_type)
    VALUES (NEW.user_id, 'You just won the badge ' || (SELECT badge_name FROM badge WHERE badge.badge_id = NEW.badge_id), 'badge');
  RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

CREATE TRIGGER notification_badges
AFTER INSERT ON userbadge
FOR EACH ROW
  EXECUTE PROCEDURE notification_badges();


-- TRIGGER 06 - An user gets one more point for every new comment, answer, question or vote.

CREATE FUNCTION award_user_point()
RETURNS TRIGGER AS $$
BEGIN
    -- Increment user's score by one for any action (question, answer, comment)
    UPDATE member
    SET user_score = user_score + 1
    WHERE user_id = NEW.content_author;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Create the trigger for different tables (question, answer, comment)
CREATE TRIGGER award_user_point_question
AFTER INSERT ON question
FOR EACH ROW
EXECUTE FUNCTION award_user_point();

CREATE TRIGGER award_user_point_answer
AFTER INSERT ON answer
FOR EACH ROW
EXECUTE FUNCTION award_user_point();

CREATE TRIGGER award_user_point_comment
AFTER INSERT ON comment
FOR EACH ROW
EXECUTE FUNCTION award_user_point();

-- Create a trigger to award users one point for each vote
CREATE FUNCTION award_user_point_vote()
RETURNS TRIGGER AS $$
BEGIN
    UPDATE member
    SET user_score = user_score + 1
    WHERE user_id = NEW.vote_author;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER award_user_point_vote
AFTER INSERT ON vote
FOR EACH ROW
EXECUTE FUNCTION award_user_point_vote();


-- TRIGGER 07 - An user gets a badge when he registers a new account in the system.

CREATE FUNCTION register_badge() RETURNS TRIGGER AS $$
BEGIN
     INSERT INTO userbadge (user_id, badge_id)
     VALUES (NEW.user_id, 11);
  RETURN NEW;
END
$$ LANGUAGE 'plpgsql';

CREATE TRIGGER register_badge
  AFTER INSERT ON member
  FOR EACH ROW
    EXECUTE PROCEDURE register_badge();

-- TRIGGER 08 - An user gets a badge when he obtains 10 points by interacting with the platform.

CREATE FUNCTION bronze_badge() RETURNS TRIGGER AS $$
BEGIN
    IF NEW.user_score = 10 THEN
      INSERT INTO userbadge (user_id, badge_id) VALUES
      (NEW.user_id, 1);
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

CREATE TRIGGER bronze_badge
AFTER INSERT OR UPDATE OF user_score ON member
FOR EACH ROW
    EXECUTE PROCEDURE bronze_badge();

-- TRIGGER 09 - An user gets a badge when he obtains 100 points by interacting with the platform.
CREATE FUNCTION silver_badge() RETURNS TRIGGER AS $$
BEGIN
    IF NEW.user_score = 100 THEN
      INSERT INTO userbadge (user_id, badge_id) VALUES
      (NEW.user_id, 2);
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

CREATE TRIGGER silver_badge
AFTER INSERT OR UPDATE OF user_score ON member
FOR EACH ROW
  EXECUTE PROCEDURE silver_badge();


-- TRIGGER 10 - An user gets a badge when he obtains 1000 points by interacting with the platform.

CREATE FUNCTION gold_badge() RETURNS TRIGGER AS $$
BEGIN
    IF NEW.user_score = 1000 THEN
      INSERT INTO userbadge (user_id, badge_id) VALUES
      (NEW.user_id, 3);
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

CREATE TRIGGER gold_badge
AFTER INSERT OR UPDATE OF user_score ON member
FOR EACH ROW
  EXECUTE PROCEDURE gold_badge();



------------------------------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------------------------
-- CREATE POPULATE FOR LBAW2311 SCHEMA
------------------------------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------------------------

------------------------------------------------------------------------------------------------------------------------
-- POPULATE TABLES FOR LBAW2311 SCHEMA
------------------------------------------------------------------------------------------------------------------------
-- Populate the Badge table (R04)
INSERT INTO badge (badge_name, badge_description)
VALUES ('Bronze', 'Bronze-level badge'), -- Assigned when you gained 10 points
       ('Silver', 'Silver-level badge'), -- Assigned when you gained 100 points
       ('Gold', 'Gold-level badge'), -- Assigned when you gained 1000 points
       ('Reliable', 'One of your answers was accepted as the correct answer'), -- Assigned when you have an answer accepted as the correct answer
       ('Notable Question', 'Your question was upvoted 100 times'), -- Assigned when you have a question with 100 upvotes
       ('Good Question', 'Your question was upvoted 25 times'), -- Assigned when you have a question with 25 upvotes
       ('Nice Question', 'Your question was upvoted 10 times'), -- Assigned when you have a question with 10 upvotes
       ('Notable Answer', 'Your answer was upvoted 100 times'), -- Assigned when you have an answer with 100 upvotes
       ('Good Answer', 'Your answer was upvoted 25 times'), -- Assigned when you have an answer with 25 upvotes
       ('Nice Answer', 'Your answer was upvoted 10 times'), -- Assigned when you have an answer with 10 upvotes
       ('Welcome', 'You have joined the community'); -- Assigned when you create your account

-- Populate the User table (R01)
--  The user_creation_date is automatically generated by the database

INSERT INTO member (user_id, username, user_email, user_password, picture, user_birthdate, user_score)
VALUES (-1, 'deleted', 'deleted@example.com', 'pass', '/picture/avatar1.jpg', '1990-01-15', 0 );

INSERT INTO member (username, user_email, user_password, picture, user_birthdate, user_score)
VALUES ('admin', 'admin@example.com', 'pass', '/picture/avatar1.jpg', '1990-01-15', 0 ),
       ('moderator', 'moderator@example.com', 'pass', '/picture/avatar2.jpg', '1990-01-15',  0 ),
       ('member1', 'member1@example.com', 'pass','/picture/avatar3.jpg', '1990-01-21', 0 );



-- Populate the Tag table (R15)
INSERT INTO tag (tag_name, tag_description)
VALUES ('programming', 'Programming-related questions'),
       ('database', 'Database management topics'),
       ('web', 'Web development questions'),
       ('networking', 'Networking-related questions'),
       ('security', 'Security-related questions'),
       ('hardware', 'Hardware-related questions'),
       ('software', 'Software-related questions'),
       ('mobile', 'Mobile development questions'),
       ('game', 'Game development questions'),
       ('other', 'Other questions');

-- Populate the Admin table (R02)
INSERT INTO admin (user_id)
VALUES (1);

-- Populate the Moderator table (R03)
-- The assignment timestamp is automatically generated by the database
INSERT INTO moderator (user_id, tag_id)
VALUES (2, 1);


-- Populate the UserBadge table (R05)
-- The user_badge_date is automatically generated by the database
INSERT INTO userbadge (user_id, badge_id)
VALUES (1, 1),  -- Welcome badge for admin
       (2, 1),  -- Welcome badge for moderator
       (3, 1),  -- Welcome badge for member1
       (1, 4);   -- Reliable badge for admin


-- NOTIFICATION IS INITIALLY EMPTY AS THESE ARE THE DEFAULTS FOR TEST PURPOSES
-- AFTER THE FEATURES ARE IMPLEMENTED IN THE WEBSITE, THE NOTIFICATIONS WILL BE
-- CREATED BY THE TRIGGERS AND POPULATED ON ITS OWN

-- Populate the Question table (R12)
-- The content_creation_date is automatically generated by the database
-- The content_is_edited is automatically set to false by the database
-- The content_is_visible is automatically set to true by the database
INSERT INTO question (question_title, question_tag, correct_answer, content_author, content_text)
VALUES ('How to program in Python?', 1, NULL, 3, 'I want to learn Python programming. Any advice is helpful.'),             -- Question from member1
       ('Database design tips', 2, NULL, 2, 'I need advice on using docker with a database. Any help is appreciated.');  -- Question from moderator

-- Populate the Answer table (R13)
-- The content_creation_date is automatically generated by the database
-- The content_is_edited is automatically set to false by the database
-- The content_is_visible is automatically set to true by the database
INSERT INTO answer (question_id, content_author, content_text)
VALUES (1, 1, 'Start with Python basics. Learn data types, variables, and control structures.'), -- Answer to question 'How to program in Python?' from admin
       (1, 2, 'Practice Python syntax by writing small programs. Use online resources.'),        -- Answer to question 'How to program in Python?' from moderator
       (1, 1, 'Explore Python libraries like NumPy and Pandas for data analysis.'),              -- Answer to question 'How to program in Python?' from admin
       (2, 1, 'Ensure a clear data model. Normalize tables for efficient data storage.'),        -- Answer to question 'Database design tips' from admin
       (2, 3, 'Use a database management system like MySQL or PostgreSQL.'),                     -- Answer to question 'Database design tips' from member1
       (2, 1, 'Consider indexes for faster queries. Plan for data backup and recovery.');        -- Answer to question 'Database design tips' from admin

-- Insert correct answer into question 1
UPDATE question
SET correct_answer = 3
WHERE question_id = 1;

-- Populate the Comment table (R14)
-- The content_creation_date is automatically generated by the database
-- The content_is_edited is automatically set to false by the database
-- The content_is_visible is automatically set to true by the database
INSERT INTO comment (answer_id, content_author, content_text)
VALUES (1, 3, 'Great advice! Thanks for sharing.'), -- Comment on answer 3  of question 1 from member1
       (2, 3, 'Thanks for the information. But do you have anything more specific?'), -- Comment on answer 4 of question 1 from member1
       (3, 1, 'You''re welcome! Keep coding!'), -- Comment on answer 4 of question 1 from admin
       (4, 3, 'Normalization is a key concept in DB design.'), -- Comment on answer 6 of question 2 from member1
       (5, 1, 'Consider PostgreSQL for database management. It is what I use.'), -- Comment on answer 7 of question 2 from admin
       (6, 3, 'Data backup is crucial for disaster recovery.'); -- Comment on answer 8 of question 2 from member1

-- Populate the Vote table (R16)
-- The vote_date is automatically generated by the database
INSERT INTO vote (vote_author, upvote, entity_voted, vote_content_question, vote_content_answer, vote_content_comment)
VALUES (2, 'up', 'answer',NULL ,3, NULL ), -- Upvote answer 3 (belongs to admin) of question 1 from moderator
       (1, 'up', 'answer',NULL, 6, NULL), -- Upvote answer 7 (belongs to member1) of question 2 from admin
       (1, 'down', 'question', 2, NULL, NULL), -- Downvote question 2 (belongs to moderator) from admin
       (3, 'up', 'question', 2, NULL, NULL ), -- Upvote question 2 (belongs to moderator) from member1
       (3, 'up', 'answer', NULL , 3, NULL); -- Upvote answer 3 (belongs to admin) from member1

-- Populate the Report table (R17)
-- The report_dealt is automatically set to false by the database
INSERT INTO report (report_creator, report_handler, content_reported_question, 
            content_reported_answer, content_reported_comment , report_reason, report_text)
VALUES (3, NULL, NULL, 4, NULL, 'spam', 'Inappropriate content'), -- Report on answer 4 from member1
       (1, NULL, 2, NULL, NULL, 'offensive', 'Offensive language'); -- Report on question 2 from admin

-- THESE REPORTS ARE TO BE ASSIGNED A HANDLER AND BE REFUSED
-- FOR TEST PURPOSES AFTER THE FEATURES ARE IMPLEMENTED

-- Populate the UserFollowQuestion table (R18)
INSERT INTO userfollowquestion (user_id, question_id, follow)
VALUES (1, 1, true),  -- Follow question 1 from admin. He wants to be notified of any new activity
       (2, 2, false), -- Unfollow question 2 from moderator. It's his own question but he does not want any more notifications about it
       (3, 1, true),  -- Follow question 1 from member1. A user must follow his own questions by default
       (3, 2, true);  -- Follow question 2 from member1. He wants to be notified of any new activity


