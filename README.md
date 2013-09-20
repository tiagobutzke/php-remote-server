PHP REMOTE SERVER
=======

Library to connect and manage remote servers via PHP.

--

## PHP extensions
* SSH2
** Ubuntu Instalation: 
```shell
apt-get install libssh2-php
/etc/inid.d/apache2 restart
```
--

## How to use

### SSH protocol
```php
$remoteServer = new \RemoteServer\RemoteServer(
    new \RemoteServer\Ssh()
);

$remoteServer->connect('127.0.0.1', 'username', 'password', 'port');
$remoteServer->execute('ls -la');
var_dump($remoteServer->getResult());
var_dump($remoteServer->getArray());
```
### Telnet protocol
```php
$remoteServer = new \RemoteServer\RemoteServer(
    new \RemoteServer\Telnet()
);

$remoteServer->connect('127.0.0.1', 'username', 'password', 'port');
$remoteServer->execute('ls -la');
var_dump($remoteServer->getResult());
var_dump($remoteServer->getArray());
```

--
## Doubts
Email: tiago.butzke@gmail.com

Twitter: [@tiagobutzke](http://twitter.com/tiagobutzke "@tiagobutzke")
