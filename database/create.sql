DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS admin;
DROP TABLE IF EXISTS moderator;
DROP TABLE IF EXISTS badge;
DROP TABLE IF EXISTS userbadge;
DROP TABLE IF EXISTS notification;
DROP TABLE IF EXISTS questionnotification;
DROP TABLE IF EXISTS answernotification;
DROP TABLE IF EXISTS commentnotification;
DROP TABLE IF EXISTS badgenotification;
DROP TABLE IF EXISTS content;
DROP TABLE IF EXISTS question;
DROP TABLE IF EXISTS answer;
DROP TABLE IF EXISTS comment;
DROP TABLE IF EXISTS tag;
DROP TABLE IF EXISTS vote;
DROP TABLE IF EXISTS report;
DROP TABLE IF EXISTS userfollowquestion;



-- Create the User table (R01)
CREATE TABLE user (
    user_id INT PRIMARY KEY,
    username VARCHAR(25) UNIQUE NOT NULL,
    user_email VARCHAR(25) UNIQUE NOT NULL,
    user_password VARCHAR(255) NOT NULL,
    picture VARCHAR(255),
    user_birthdate TIMESTAMP NOT NULL,
    user_creation_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    user_score INT DEFAULT 0, 
    CONSTRAINT user_birthdate ((user_creation_date - user_birthdate) > 12)
);

-- Create the Admin table (R02)
CREATE TABLE admin (
    user_id INT PRIMARY KEY,
    FOREIGN KEY (user_id) REFERENCES user(user_id)
);

-- Create the Moderator table (R03)
CREATE TABLE moderator (
    user_id INT PRIMARY KEY,
    tag_id INT NOT NULL,
    assignment TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(user_id),
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
    user_id INT PRIMARY KEY,
    badge_id INT PRIMARY KEY,
    user_badge_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(user_id),
    FOREIGN KEY (badge_id) REFERENCES badge(badge_id)
);

-- Create the Notification table (R06)
CREATE TABLE notification (
    notification_id INT PRIMARY KEY,
    notification_user INT,
    notification_content VARCHAR(255) NOT NULL,
    notification_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    notification_is_read BOOLEAN NOT NULL DEFAULT FALSE,
    FOREIGN KEY (notification_user) REFERENCES user(user_id)
);

-- Create the QuestionNotification table (R07)
CREATE TABLE questionnotification (
    notification_id INT,
    FOREIGN KEY (notification_id) REFERENCES notification(notification_id)
);

-- Create the AnswerNotification table (R08)
CREATE TABLE answernotification (
    notification_id INT,
    FOREIGN KEY (notification_id) REFERENCES notification(notification_id)
);

-- Create the CommentNotification table (R09)
CREATE TABLE commentnotification (
    notification_id INT,
    FOREIGN KEY (notification_id) REFERENCES notification(notification_id)
);

-- Create the BadgeNotification table (R10)
CREATE TABLE badgenotification (
    notification_id INT,
    FOREIGN KEY (notification_id) REFERENCES notification(notification_id)
);

-- Create the Content table (R11)
CREATE TABLE content (
    content_id INT PRIMARY KEY,
    content_author INT,
    content_creation_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    content_text VARCHAR(255) NOT NULL,
    content_is_edited BOOLEAN NOT NULL DEFAULT FALSE,
    content_is_visible BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (content_author) REFERENCES user(user_id)
);

-- Create the Question table (R12)
CREATE TABLE question (
    content_id INT,
    question_title VARCHAR(255) NOT NULL,
    question_tag INT,
    correct_answer INT,
    FOREIGN KEY (content_id) REFERENCES content(content_id),
    FOREIGN KEY (question_tag) REFERENCES tag(tag_id),
    FOREIGN KEY (correct_answer) REFERENCES answer(content_id)
);

-- Create the Answer table (R13)
CREATE TABLE answer (
    content_id INT PRIMARY KEY,
    question_id INT,
    FOREIGN KEY (content_id) REFERENCES content(content_id),
    FOREIGN KEY (question_id) REFERENCES question(content_id)
);

-- Create the Comment table (R14)
CREATE TABLE comment (
    content_id INT PRIMARY KEY,
    answer_id INT,
    FOREIGN KEY (content_id) REFERENCES content(content_id),
    FOREIGN KEY (answer_id) REFERENCES answer(content_id)
);

-- Create the Tag table (R15)
CREATE TABLE tag (
    tag_id INT PRIMARY KEY,
    tag_name VARCHAR(255) UNIQUE NOT NULL,
    tag_description VARCHAR(255)
);

-- Create the Vote table (R16)
CREATE TABLE vote (
    vote_id INT PRIMARY KEY,
    vote_author INT,
    vote_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    upvote VARCHAR(25) ,
    entity_voted VARCHAR(25),
    vote_content INT,
    FOREIGN KEY (vote_author) REFERENCES user(user_id),
    FOREIGN KEY (vote_content) REFERENCES content(content_id),
    CONSTRAINT upvote CHECK ((upvote = ANY (ARRAY ['up'::text, 'down'::text, 'out'::text]))),
    CONSTRAINT entity_voted CHECK ((entity_voted = ANY (ARRAY ['question'::text, 'answer'::text, 'comment'::text])))
);

-- Create the Report table (R17)
CREATE TABLE report (
    report_id INT PRIMARY KEY,
    report_creator INT,
    report_handler INT,
    content_reported INT,
    report_reason VARCHAR(40) NN,
    report_text VARCHAR(255),
    report_dealed BOOLEAN NOT NULL DEFAULT FALSE,
    report_accepted BOOLEAN,
    report_answer VARCHAR(255),
    FOREIGN KEY (report_creator) REFERENCES user(user_id),
    FOREIGN KEY (report_handler) REFERENCES moderator(user_id),
    FOREIGN KEY (content_reported) REFERENCES content(content_id),
    FOREIGN KEY (report_answer) REFERENCES answer(content_id),
    CONSTRAINT report_reason CHECK ((report_reason = ANY (ARRAY ['spam'::text, 'offensive'::text, 'Rules Violation'::text, 'Innapropriate tag'::text])))
);

-- Create the UserFollowQuestion table (R18)
CREATE TABLE userfollowquestion (
    user_id INT,
    question_id INT,
    follow BOOLEAN NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(user_id),
    FOREIGN KEY (question_id) REFERENCES question(content_id)
);
