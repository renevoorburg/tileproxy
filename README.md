# tileproxy

A caching proxy for geo tiles, as used by [Vici.org](https://vici.org).

The core for this service is in the webserver configuration:

    location ~ ^/(.*)$ {
        try_files $uri /tiles/$1 @tiles;
    }

    location @tiles {
	    fastcgi_pass   php:9000;
        fastcgi_param  SCRIPT_FILENAME  /var/www/domain.host.tiles/src/controller.php;
	    fastcgi_param  QUERY_STRING    q=$uri&$args;
        include        fastcgi_params;
    }

The `try_files` directive will return the image from disk, and only when it is not there, `controller.php` is called.

The controller uses the configuration in `config/tileproxy.json`. See the example `tileproxy.json_example`. 
The controller wil try to download the tile from the provided source, send it to the user and store in on disk. 
Next time the same tile is requested, it will be simply served by the webserver.

In the setup used by Vici.org, the tiles are not in the webroot, but in a subdirectory named `tiles`, which is essentially a remote mount. 
This directory is defined in `"baseDir"` in `config/tileproxy.json`. 
In nginx, this configuration is supported by the `/tiles/$1` parameter for `try_files`.




