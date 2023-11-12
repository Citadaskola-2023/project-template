### Project

You can use this template by running the following command:
```shell
docker run --rm -it \
  --volume $PWD:/app \
  --user $(id -u):$(id -g) \
  composer create-project citadaskola-2023/project-template <project_name>
```

### How to get it started?
1. Dependencies already are installed with `composer create-project`
2. Start Project container (using [dunglas/frankenphp](https://github.com/dunglas/frankenphp) PHP image)
    ```shell
    docker run -v $PWD:/app \
        -p 80:80 -p 443:443 \
        --n
        dunglas/frankenphp
    ```
3. Go to https://localhost, and enjoy!

### Future expansions
1. In order to use MySQL, libraries more has to be added - [ref](https://github.com/dunglas/frankenphp/blob/main/docs/docker.md#how-to-install-more-php-extensions)

### Licence
MIT licence
* https://choosealicense.com/licenses/mit/
* https://spdx.org/licenses/