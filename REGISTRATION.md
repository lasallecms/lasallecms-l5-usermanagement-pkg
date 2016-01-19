# Front-end Registration

There are two types of front-end registration: 
* regular
* with two factor authorization

## Regular Front-End Registration

When the config/auth "auth_user_id_for_created_by_for_frontend_user_registration" is false, the regular front-end registration is enabled. Validation occurs after the registration form is submitted. There is one round of validaton. Upon successful validation, the new record is INSERTed into the users table.

## Front-End Registration With Two Factor Authorization

When the config/auth "auth_user_id_for_created_by_for_frontend_user_registration" is true, a completely separate registration workflow is enabled. 

This workflow has two steps: inputting and validating registrant info; and, issuing and verifying the 2FA code sent to the registrant via SMS (text message). 

This workflow ensures (but cannot guarantee) that only bona fide registrants are INSERTed in the users database table. So, unlike the regular registration workflow, this workflow only INSERTs into the users database table at the conclusion of its registration process. All validations in this workflow must success in order fo INSERT (create) the users record. 

## Workflow Internals

Since these workflows are so different, there are two separate workflows in the code. The routes file has a conditional, so the same URLs apply regardless of workflow; but, the internal routes use the appropriate controller, views, etc. 