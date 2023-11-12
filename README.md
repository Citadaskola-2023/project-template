### Project

### How to get it started?
1. Install dependencies
```shell
docker run --rm -it \                         
  --volume $PWD:/app \
  --user $(id -u):$(id -g) \
  composer install
```

2. Start Project container (using [dunglas/frankenphp](https://github.com/dunglas/frankenphp) PHP image)
```shell
docker run -v $PWD:/app \
    -p 80:80 -p 443:443 \
    dunglas/frankenphp
```

3. Go to https://localhost, and enjoy!

### Licence
MIT licence
* https://choosealicense.com/licenses/mit/
* https://spdx.org/licenses/