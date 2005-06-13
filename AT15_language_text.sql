INSERT INTO AT15_language_text VALUES 

('en', '_msgs', 'AT_ERROR_LOGIN_LOCKED','Maximum login attempts has been reached. Login has been temporarily disabled for 1 hour. Please <a href="./help/contact_support.php">contact FHA</a> if you forgot your login or password.',NOW(),'reached maximum login attempts. Now locked'),

('en','_msgs','AT_ERROR_LOGIN_LOCK_WARNING','Please <a href="./help/contact_support.php">contact FHA</a> if you forgot your login or password. One more login attempt remains before login is temporarily disabled.',NOW(),'one more attempt before login lock'),

('en','_msgs','AT_ERROR_REGISTRATION_LOCKED','Maximum registration attempts has been reached. Registration has been temporarily disabled for 1 hour. Please <a href="./help/contact_support.php">contact FHA</a> if you require assistance.',NOW(),'failed registration attempts. Now locked'),

('en','_msgs','AT_ERROR_REGISTRATION_LOCK_WARNING2','One more registration attempt remains before registration is temporarily disabled. Please <a href="./help/contact_support.php">contact FHA</a> if you require assistance.',NOW(),'second/final warning before registration lock.'),

('en','_msgs','AT_ERROR_REGISTRATION_LOCK_WARNING1','Registration information contains errors. <a href="./help/contact_support.php">Contact FHA</a> if you require assistance.',NOW(),'first warning before registration lock.'),

('en','_msgs','AT_ERROR_EMAIL_MISMATCH','Email addresses do not match. <a href="./help/contact_support.php">Contact FHA</a> if you require assistance.',NOW(),'If email addresses do not match.'),

('en','_msgs','AT_ERROR_ONLY_ONE_CHALLENGE_TEST','Only one Challenge Test is allowed per course.',NOW(),'If user tries to specify more than one challenge test.'),

('en','_msgs','AT_ERROR_SECRET_ERROR','You did not correctly enter the numbers as they appear in the Image Validation field.',NOW(),'If user enters wrong capcha.'),

('en','_msgs','AT_ERROR_EMAIL_FHA_MISMATCH','The email address specified does not appear to be a valid Fraser Health email address.',NOW(),''),
;