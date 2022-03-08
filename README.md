<h1 align="center" style="text-align:center;">
  Hyperf Serve
</h1>

## About Hyperf Serve

Hyperf Serve is a hyperf-based third-party extension package, with it, you can easily reload or stop the hyperf server, and code hot loading.

## How to Use

### Install 

```
composer require hyperf/serve
```
### Start Hyperf Server
```
php ./bin/hyperf.php start
```
### Reload Hyperf Server
```
php ./bin/hyperf.php serve:reload
```
### Stop Hyperf Server
```
php ./bin/hyperf.php serve:stop
```
### Code Hot Loading (Available on all platforms MAC/WINDOWS/LINUX/FREEBSD)
```
php ./bin/hyperf.php vendor:publish hyperf/serve
chmod +x ./bin/watch
./bin/watch
```
### Or Use Composer for code hot loading
#### write code to your composer.json
```
    "scripts": {
        "watch": "./bin/watch"
    }
```
#### Use it
```
composer watch
```

#### Note
Because the default command called by the watch command is `php ./bin/hyperf.php serve:reload`, so the `php` path need add to the environment variable, or you can write your watch command like 
```
./bin/watch --command="your command here"
```
By the way, if you are using docker development environment, you can write
```
./bin/watch --command="docker exec -d mydocker php /opt/www/bin/hyperf.php serve:reload"
```

### Notice
In the development environment, opcache needs to be closed, and the docker development environment also needs to be closed.
for docker development environment, you can add code `echo "opcache.enable_cli=Off"; \` to your Dockerfile
```
        ...
        echo "upload_max_filesize=128M"; \
        echo "post_max_size=128M"; \
        echo "memory_limit=1G"; \
        echo "date.timezone=${TIMEZONE}"; \
        echo "opcache.enable_cli=Off"; \
        ...
```

## Security Vulnerabilities

If you discover a security vulnerability within hyperf serve, please send an e-mail to Pian Zhou [pianzhou2021@163.com](pianzhou2021@163.com). All security vulnerabilities will be promptly addressed.

## License

The hyperf serve is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).