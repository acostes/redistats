# Redistats #

Monitoring multiple redis instance. This web app allow you to visualize all your redis server status and make stats about the information you need, it's easily customizable via a configuration file. 

## How to install ##

Packages available Composer. Autoloading is PSR-0 compatible.
### Apache setup
```
NameVirtualHost *:80
<Directory "/path/to/redistats/public">
    AllowOverride All
    Order allow,deny
    Allow from all
</Directory>

<VirtualHost *:80>
   DocumentRoot /path/to/redistats/public
   Alias /api /path/to/redistats/api
</VirtualHost>
```

## How to use ##

## Dependencies ##
- PHP >= 5.3.0
- Redis >= 2.6

## Author ##

- Arnaud Costes ([twitter](http://twitter.com/acostes))

## License ##

The code for Redistats is distributed under the terms of the MIT license (see [LICENSE](LICENSE)).

[![Build Status](https://travis-ci.org/acostes/redistats.png?branch=master)](https://travis-ci.org/acostes/redistats)
