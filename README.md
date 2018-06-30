# wave-cms
Wave CMS is a minimalistic open-source Content Management System based on AngularJS and PHP. It implements some default functionnalities: an administration interface to edit site settings, create pages and articles, manage users and 2 working plugins (contact form and social icons). As you might have guessed, this is not an achieved project but it can already be used and improved by developers thanks to its plugin interface:
- You can use the JSON Database in your plugin.
- You can use the AngularJS $wave service to make requests to the Database or the Plugins.
- You can add Angular controllers, templates, routes, scripts, CSS to the admin and user interface directly using the PHP Object PU (Plugin Utilities).
- You can add menu links and widgets directly from your plugin.
- Your plugin can use modules written in PHP (cf. default plugins).
- Bootstrap, Font Awesome, AngularJS, jQuery, Summernote included.
- File upload function, captcha system.


I will write some more details about this codebase & a documentation for developers later. Feel free to contact me to add plugins or improvements. Theming hasn't been introduced yet.

## Set up
Put the files on your server and load the page "install.php", you will have to click on a button and the site will be ready.

## JSON Database

Once your plugin is loaded, you will be able to use the $db variable which contains a JDB object. JDB stands for JsonDB, the storage system used by the CMS. It uses a basic syntax for queries : **Folder:File:Key**

For instance, if you want to get the site name, you will call: `$db->get("wave:site_info:name");`
But if you want an Array of all site_info you just need to call: `$db->get("wave:site_info");`
Finally, if you want to get an Array of all the files in the JsonDB/wave folder, you will call: `$db->get("wave");` even if it is less frequent.

**The full documentation isn't ready yet!**

Benjamin Rathelot
