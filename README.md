Bundle for PHP REST Client for JasperReports Server
===================================================

Introduction
-------------
Using this library you can make requests and interact with the Jasper Reports Server through the REST API in native PHP. This allows you to more easily embed data from your report server, or perform administrative tasks on the server using PHP.

Requirements
-------------
To use this client, you will need:
- JasperReports Server (version >= 5.2)
- PHP (version >= 7.3, with cURL extension)
- Composer dependency manager <http://getcomposer.org/download> (Optional, but recommended)


Installation
-------------
Add the following to your composer.json file for your project, or run `php composer.phar reqiure jwizhippo/jasper-client-bundle v0.3.0` in the directory of your project

    {
	    "require": {
		    "wizhippo/jasper-client-bundle": "dev-master"
	    }
    }

Or alternatively, download this package from github, and run `php composer.phar install` in the directory containing composer.json to generate the autoloader, then require the autoloader using

    require_once "vendor/autoload.php"
	
Additionally, a distributed autoloader is included if oyu want to simply include it in an existing project, or do not want to bother with Composer.

	require_once "autoload.dist.php"

License
--------
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as
published by the Free Software Foundation, either version 3 of the
License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Lesser  General Public License for more details.

You should have received a copy of the GNU Lesser General Public  License
along with this program. If not, see <http://www.gnu.org/licenses/>.
