--show search_path;
--create schema lbaw2311;
--set search_path to lbaw2311;

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
DROP TABLE IF EXISTS comment;
DROP TABLE IF EXISTS content CASCADE;
DROP TABLE IF EXISTS member CASCADE;
DROP TABLE IF EXISTS badge;

DROP TABLE IF EXISTS tag;
DROP TABLE IF EXISTS vote;
DROP TABLE IF EXISTS report;
DROP TABLE IF EXISTS userfollowquestion;
DROP TYPE IF EXISTS voteType;
DROP TYPE IF EXISTS entityType;
DROP TYPE IF EXISTS reportReasonType;


CREATE TYPE voteType AS ENUM ('up', 'down', 'out');
CREATE TYPE entityType AS ENUM ('question', 'answer', 'comment');
CREATE TYPE reportReasonType AS ENUM ('spam', 'offensive', 'Rules Violation', 'Innapropriate tag');
CREATE TYPE notificationType AS ENUM ('question', 'answer', 'comment', 'badge');


-- Create the User table (R01)
CREATE TABLE member (
    user_id INT PRIMARY KEY,
    username VARCHAR(25) UNIQUE NOT NULL,
    user_email VARCHAR(25) UNIQUE NOT NULL,
    user_password VARCHAR(255) NOT NULL,
    picture VARCHAR(255),
    user_birthdate TIMESTAMP NOT NULL,
    user_creation_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    user_score INT DEFAULT 0,
	CONSTRAINT check_time CHECK (EXTRACT(YEAR FROM user_birthdate)- EXTRACT(YEAR FROM user_creation_date)=12 )
);

-- Create the Tag table (R15)
CREATE TABLE tag (
    tag_id INT PRIMARY KEY,
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
    badge_id INT PRIMARY KEY,
    badge_name VARCHAR(25) UNIQUE NOT NULL,
    badge_description VARCHAR(255) NOT NULL
);

-- Create the UserBadge table (R05)
CREATE TABLE userbadge (
    userbadge_id INT PRIMARY KEY,
    user_id INT,
    badge_id INT,
    user_badge_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES member(user_id),
    FOREIGN KEY (badge_id) REFERENCES badge(badge_id)
);

-- Create the Notification table (R06)
CREATE TABLE notification (
    notification_id INT PRIMARY KEY,
    notification_user INT,
    notification_content VARCHAR(255) NOT NULL,
    notification_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    notification_is_read BOOLEAN NOT NULL DEFAULT FALSE,
    notification_type notificationType NOT NULL,
    FOREIGN KEY (notification_user) REFERENCES member(user_id)
);

-- Create the Content table (R11)
CREATE TABLE content (
    content_id INT PRIMARY KEY,
    content_author INT,
    content_creation_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    content_text VARCHAR(255) NOT NULL,
    content_is_edited BOOLEAN NOT NULL DEFAULT FALSE,
    content_is_visible BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (content_author) REFERENCES member(user_id)
);

-- Create the Answer table (R13)
CREATE TABLE answer (
                        content_id INT PRIMARY KEY,
                        question_id INT
);

-- Create the Question table (R12)
CREATE TABLE question (
                          content_id INT PRIMARY KEY,
                          question_title VARCHAR(255) NOT NULL,
                          question_tag INT,
                          correct_answer INT
);


-- Create a foreign key for content_id in the question table
ALTER TABLE question
    ADD CONSTRAINT FK_Content_Question
        FOREIGN KEY (content_id) REFERENCES content(content_id);

-- Create a foreign key for question_tag in the question table
ALTER TABLE question
    ADD CONSTRAINT FK_Tag_Question
        FOREIGN KEY (question_tag) REFERENCES tag(tag_id);

-- Create a foreign key for correct_answer in the question table
ALTER TABLE question
    ADD CONSTRAINT FK_Correct_Answer
        FOREIGN KEY (correct_answer) REFERENCES answer(content_id);

-- Add foreign keys to the Answer table
ALTER TABLE answer
    ADD CONSTRAINT fk_content
        FOREIGN KEY (content_id) REFERENCES content(content_id);

ALTER TABLE answer
    ADD CONSTRAINT fk_question
        FOREIGN KEY (question_id) REFERENCES question(content_id);


-- Create the Comment table (R14)
CREATE TABLE comment (
    content_id INT PRIMARY KEY,
    answer_id INT,
    FOREIGN KEY (content_id) REFERENCES content(content_id),
    FOREIGN KEY (answer_id) REFERENCES answer(content_id)
);



-- Create the Vote table (R16)
CREATE TABLE vote (
    vote_id INT PRIMARY KEY,
    vote_author INT,
    vote_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    upvote voteType NOT NULL,
    entity_voted entityType NOT NULL,
    vote_content INT,
    FOREIGN KEY (vote_author) REFERENCES member(user_id),
    FOREIGN KEY (vote_content) REFERENCES content(content_id)
);

-- Create the Report table (R17)
CREATE TABLE report (
    report_id INT PRIMARY KEY,
    report_creator INT,
    report_handler INT,
    content_reported INT,
    report_reason reportReasonType NOT NULL,
    report_text VARCHAR(255),
    report_dealed BOOLEAN NOT NULL DEFAULT FALSE,
    report_accepted BOOLEAN,
    report_answer VARCHAR(255),
    FOREIGN KEY (report_creator) REFERENCES member(user_id),
    FOREIGN KEY (report_handler) REFERENCES moderator(user_id),
    FOREIGN KEY (content_reported) REFERENCES content(content_id)
    --FOREIGN KEY (report_answer) REFERENCES answer(content_id)
);

-- Create the UserFollowQuestion table (R18)
CREATE TABLE userfollowquestion (
    user_id INT,
    question_id INT,
    follow BOOLEAN NOT NULL,
    FOREIGN KEY (user_id) REFERENCES member(user_id),
    FOREIGN KEY (question_id) REFERENCES question(content_id)
);
