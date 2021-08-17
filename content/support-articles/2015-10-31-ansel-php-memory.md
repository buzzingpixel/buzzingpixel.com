---
title: "Ansel: Making sure PHP has enough memory"
slug: ansel-php-memory
date: 2015-10-31 7:00 PM
---

Ansel is a very powerful image manipulator. But manipulating images takes a lot of memory. And in particular, manipulating PNG images takes even more memory. The bigger the image, the more memory will be required.

If you're getting PHP errors or a "white screen of death" when submitting entries with Ansel, the most likely culprit is not enough memory assigned for PHP to use.

The minimum recommended memory limit is 128 megabytes. If you can give PHP more, you’ll be that much less likely to run into issues when someone tries to upload and crop a very large image down the road.

128 is bare minimum. I find that I run into the least issues with servers configured for at least 512M of memory available to PHP. If you have more, you should give it more. For BuzzingPixel servers, I never run PHP with less than a 512M limit. And for servers with more RAM, I allocate even more to PHP. For instance, if the server has 2 gigs of RAM, I allocate 1 gig to PHP.

Here are some ways to increase PHP's allowed memory usage. You may need to use one or more of them.

## php.ini

If you have access to your server's "php.ini" configuration file, look for the "memory_limit" key and change the value to at least 128M.

## .htaccess

If your server uses Apache (the large majority of servers do these days) and PHP is running as an Apache module, you may be able to increase the php memory limit by placing "php_value memory_limit 128M;" in your .htaccess file.

## php-fpm.conf

If your server is powered by Nginx (as opposed to Apache), or if your Apache configuration is using php-fpm (not common), you may need to find and edit your "php-fpm.conf" file and add "php_admin_value[memory_limit] = 512M". Some common locations for your "php-fpm.conf" file are:

/etc/php/fpm/php-fpm.conf
/etc/php5/fpm/php-fpm.conf
/etc/php7/fpm/php-fpm.conf

Not Enough Swap

If your server is not configured with enough (or any) swap and it runs out of memory (despite your memory limit settings in PHP), you will definitely experience trouble. For starters, here is some information on checking and working with your swap configuration from Digital Ocean:

[How To Add Swap on Ubuntu 14.04](https://www.digitalocean.com/community/tutorials/how-to-add-swap-on-ubuntu-14-04)

If you are not a server admin, and/or don't have access, and/or don't feel comfortable with any of that, you may need to talk to your server admin if you are running into trouble.

More Questions?

None of these solutions worked for you but another one did? [Open a public issue](/support/public) and I'll consider adding it to this article.
