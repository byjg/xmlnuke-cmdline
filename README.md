# XMLNuke Command Line Project

Enables run an existing XMLNuke module from the command line without
to make changes in your module.

## Install

Project Level

```bash
composer require "byjg/xmlnuke-cmdline=dev-master"
```

Entire System

```bash
composer global require "byjg/xmlnuke-cmdline=dev-master"
```


## Usage

The syntax is:

```bash
runxmlnuke.php SCRIPT PARAMETERS
```

for example:

```
runxmlnuke.php xmlnuke <FOLDER_APP> module=Lesson1.Sample1 raw=json xpath=//mediaitem
```

The parameter SCRIPT can be:

* **xmlnuke** - Run a typical XMLNuke module with all parameters
* **ws** - Run a class that implements a SOAP interface. See more [here](/byjg/xmlnuke/wiki/Create-a-SOAP-Service).
* **daemon** - Create a Linux Daemon Service. More details below. 
* **job** - Same interface from Daemon script, but run once.


## Running a SOAP Service

The SOAP service in command line only work invoking the web method through a GET request. The command line will be something like:

```
runxmlnuke.php ws <FOLDER_APP> ws=/Lesson1.SOAP.SampleWebService svc=getEcho "text=Some Text"
```

## Create Daemon/Services with XMLNuke

A Service is a program that runs infinitely.
You can create a service in XMLNuke by implementing a class with a method `execute()`.
This method, does not need to create a Loop. XMLNuke will do a loop for you.

Example:

```php
namespace Lesson1\Classes;

class Service
{
	protected $loop = 0;

	public function execute()
	{
		$this->loop++;
		echo $this->loop ."\n";

		return true;
	}

}
```

This method must return `true` to continue the service or `false` to stop it. To run this daemon:

```
sudo runxmlnuke.php daemon service=Lesson1.Classes.Service &
```

The service will run silently, but will create TWO log files:
* **/var/log/xmlnuke.daemon/Lesson1.Classes.Service.log** - All output from the service
* **/var/log/xmlnuke.daemon/Lesson1.Classes.Service.error.log** - All errors generated by the service.

You want you can use the same interface of the Daemon service but run one time only by selecting the script 'Job'.

### Create a init service

You can create a service in the Linux system just copying this file inside the folder `/etc/init`


Example: **sample.conf**

```
description "Sample Daemon"
author      "JG"

# used to be: start on startup
# until we found some mounts weren't ready yet while booting:
start on started mountall
stop on shutdown

# Automatically Respawn:
respawn
respawn limit 99 5

exec /opt/xmlnuke/utils/cmdline/runxmlnuke.sh daemon <FOLDER_YOUR_APP> service=Lesson1.Classes.Service
```

Now you can start or stop the service using:

```
service sample start
```

or

```
service sample stop
```

## Runinng REST Class in Command Line

XMLNuke REST class inherit from BaseService. These classes understanding the http methods GET, POST, PUT and DELETE and execute the proper method. The parameters action and id are considered also. 

Below a typical REST call in cmdLine:

```
runxmlnuke.php xmlnuke <FOLDER_YOUR_APP> module=Lesson1.Classes.Service REQUEST_METHOD=GET id=1 action=user
```


# Putting all together

* **Service**: https://github.com/byjg/xmlnuke-lesson1/blob/master/lib/Lesson1/Classes/Service.class.php
* **Init Conf**: https://github.com/byjg/xmlnuke-lesson1/blob/master/conf/sample.conf
