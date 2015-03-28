Rhyme AJAX
==============

An extension for Contao Open Source CMS that allows you to do simple AJAX calls on the front end.

Note: in order to handle legacy extensions that utilize the root ajax.php, you will need to add the following to your .htaccess file:

`#Rewrite legacy ajax.php scripts`

`RewriteRule ^ajax.php(.*) ajax/index.php [NC]`