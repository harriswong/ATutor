REPLACE INTO AT153_language_text VALUES 

('en', '_msgs', 'AT_ERROR_LOGIN_LOCKED','Maximum login attempts has been reached. Login has been temporarily disabled for 1 hour. Please <a href="./help/contact_support.php">contact FHA</a> if you forgot your login or password.',NOW(),'reached maximum login attempts. Now locked'),

('en','_msgs','AT_ERROR_LOGIN_LOCK_WARNING','Please <a href="./help/contact_support.php">contact FHA</a> if you forgot your login or password. You have one more attempt before the login page is disabled.',NOW(),'one more attempt before login lock'),

('en','_msgs','AT_ERROR_LOGIN_WARNING1','You have entered an unrecognized login and password combination. If you forgot your login or password, please <a href="./help/contact_support.php">contact FHA</a>. You have two more attempts before the login page is disabled.',NOW(),'two more attempt before login lock'),

('en','_msgs','AT_ERROR_REGISTRATION_LOCKED','Maximum registration attempts has been reached. Registration has been temporarily disabled for 1 hour. Please <a href="./help/contact_support.php">contact FHA</a> if you require assistance.',NOW(),'failed registration attempts. Now locked'),

('en','_msgs','AT_ERROR_REGISTRATION_LOCK_WARNING2','One more registration attempt remains before registration is temporarily disabled. Please <a href="./help/contact_support.php">contact FHA</a> if you require assistance.',NOW(),'second/final warning before registration lock.'),

('en','_msgs','AT_ERROR_REGISTRATION_LOCK_WARNING1','Registration information contains errors. Contact FHA if you require assistance. ',NOW(),'first warning before registration lock.'),

('en','_msgs','AT_ERROR_EMAIL_MISMATCH','Email addresses do not match. <a href="./help/contact_support.php">Contact FHA</a> if you require assistance.',NOW(),'If email addresses do not match.'),

('en','_msgs','AT_ERROR_ONLY_ONE_CHALLENGE_TEST','Only one Challenge Test is allowed per course.',NOW(),'If user tries to specify more than one challenge test.'),

('en','_msgs','AT_ERROR_SECRET_ERROR','You did not correctly enter the numbers as they appear in the Image Validation field.',NOW(),'If user enters wrong capcha.'),

('en','_msgs','AT_ERROR_EMAIL_FHA_MISMATCH','The email address specified does not appear to be a valid Fraser Health email address.',NOW(),''),

('en','_msgs','AT_ERROR_TEST_24HOURS', 'You cannot take a Challenge Test or Final Quiz more than once every 24 hours.', NOW(), ''),

('en','_msgs','AT_ERROR_REGISTER_MASTER_USED', 'The Employee Number and Date of Birth combination you provided is either being used or is incorrect.', NOW(), ''),

('en','_msgs','AT_ERROR_EMPLOYEE_NUMBER_NOT_FOUND', 'The Employee Number <b>%s</b> could not be found in the Master Student List.', NOW(), ''),

('en','_msgs','AT_FEEDBACK_TEST_SAVED', 'Your test/survey results have been saved and appear below. %s', NOW(), ''),

('en','_msgs','AT_ERROR_ALT_EMAIL_CONFIRM','You must confirm your Alternate Email address by entering it again.', NOW(),'If alternate email address2 empty.'),

('en','_msgs','AT_ERROR_ALT_EMAIL_MISMATCH','Alternate Email addresses do not match.', NOW(),'If alternate email addresses do not match.'),

('en' ,'_msgs', 'AT_ERROR_FHA_TEST', 'You need to be enrolled in this course to access this area. Please click on the Enroll Me link above.', NOW(), ''),

('en' ,'_template', 'employee_number', 'Employee Number', NOW(), ''),

('en' ,'_template', 'image_validation', 'Image Validation', NOW(), ''),

('en' ,'_template', 'image_validation_text', 'In this image (<img src="secret.php" alt="" />) there is a number displayed. <br />Please type this number into the following field.', NOW(), ''),

('en' ,'_template', 'image_validation_text2', '&middot; This helps ensure a live person is registering on this system.', NOW(), ''),

('en' ,'_template', 'choose_login_name', 'Choose a Login Name', NOW(), ''),

('en' ,'_template', 'choose_password', 'Choose a Password', NOW(), ''),

('en' ,'_template', 'password_again', 'Type the Password Again', NOW(), ''),

('en' ,'_template', 'email_again', 'Email Address Again', NOW(), ''),

('en' ,'_template', 'alt_email', 'Alternate Email Address', NOW(), ''),

('en' ,'_template', 'alt_email_again', 'Alternate Email Address Again', NOW(), ''),

('en' ,'_template', 'challenge_test', 'Challenge Test', NOW(), ''),

('en' ,'_template', 'reports', 'Reports', NOW(), ''),

('en' ,'_msgs', 'AT_FEEDBACK_REG_CONFIRM', '<p>Thank you for registering.</p> <p>We are going to be sending you an e-mail shortly. Please follow the instructions in the e-mail on how to confirm your account. You will need to confirm your account before you can login.</p> <p>If you don\'t receive an e-mail at all in the next hour or two, it may be because there was a typo in the address. In that case, please contact us via the <a href="./help/contact_support.php">Help link</a>.</p>', NOW(), ''),

('en' ,'_msgs', 'AT_FEEDBACK_REG_THANKS', '<p>Thank you for registering.</p> <p>You may now log into your account. If you need help, please contact us via the <a href="./help/contact_support.php">Help link</a>.</p>', NOW(), ''),

('en' ,'_template', 'overwrite_master', 'If an existing account is using this employee number, overwrite association with new account.', NOW(), ''),

('en' ,'_msgs', 'AT_ERROR_CREATE_MASTER_USED', 'The Employee Number you have entered already belongs to another user. If you wish to over-write this association with the new account, use the over-write checkbox.', NOW(), ''),

('en' ,'_msgs', 'AT_ERROR_NOT_CONFIRMED', 'Your account\'s email address has not yet been confirmed. Please check your email account for a confirmation message. Please contact us if you do not receive it.', NOW(), ''),

('en' ,'_msgs', 'AT_ERROR_LOGIN_CHARS', 'Your Login Name must only contain letters, numbers, underscores (_\'s).', NOW(), ''),

('en' ,'_template', 'email_confirmation_message', 'Greetings!\n\nRegistration Confirmation:\n\nThis is an automatic e-mail reminder from the FHA Online Learning System.  Please do not reply to this message.\n\nYou have registered for an account on %1$s.\n\nYour login name is %3$s.\n\n Please finish the registration process by confirming your email address by using the following link: %2$s .\n\nIf the link does not open your browser automatically, please copy and paste the link into your Internet browser\'s address bar.\n\nThanks.\n\nOnline Learning Initiatives\nFraser Health Authority', NOW(), ''),

('en' ,'_template', 'password_request2', 'Greetings!\n\nForgotten Password:\n\nThis is an automatic e-mail reminder from the FHA Online Learning System.  Please do not reply to this message.\n\nIf you have forgotten your password, please visit the address below to change it:\n\n%4$s\n\nIf the link does not open your browser automatically, please copy and paste the link into your Internet browser\'s address bar.\n\nYour login name is %1$s.\n\nPlease note that this link will only be active for %3$s days. If it has expired, please visit http://upgradefhaol.primesignal.com/docs/ and click on "E-mail Reminder" again.\n\nThanks.\n\nOnline Learning Initiatives\nFraser Health Authority', NOW(), '')

;
