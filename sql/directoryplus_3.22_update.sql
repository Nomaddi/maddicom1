UPDATE `language` SET `translated` = 'Please submit the form again.' WHERE `template` = 'register' AND `var_name` = 'txt_submit_again';

DELETE FROM `language` WHERE var_name='baseurl';
