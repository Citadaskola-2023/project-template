### Project

This is project template for basic programming, to see something on browser or cli, can be used as a stepping stone (starting or resetting) to learn about OOP concepts, Backend development and web development in general.

:warning: Keep in mind - It is on purpose, very stupid simple, **lacks many features for production**, but those are left as a plan to be discovered later in the classroom. 

Overkill features
* Autoloading (PSR-4) - already supported by composer, skipping burden of 
  * require all necessary files one by one in other files
  * using [spl_autoload_register](https://www.php.net/manual/en/function.spl-autoload-register.php)
  
Missing features
* Basic Router (including PSR-7)
* Containers (PSR-11)
* Other PSR interfaces (PSR-3 Logging, PSR-6 Caching, PSR-14 Event/Listeners)
* Something FE related (maybe [Mithril.js](https://mithril.js.org/))

Okay features
* More or less well known structure
* Has examples for env variables
* Pointing to use Pest for testing


### How to get it started?
1. You can use this template by running the following command:
   ```shell
   docker run --rm -it \
     --volume $PWD:/app \
     --user $(id -u):$(id -g) \
     composer create-project citadaskola-2023/project-template <project_name>
   ```
2. Dependencies already are installed with `composer create-project`
3. Start Project container (using [dunglas/frankenphp](https://github.com/dunglas/frankenphp) PHP image)
    ```shell
    docker run -v $PWD:/app \
        -p 80:80 -p 443:443 \
        dunglas/frankenphp
    ```
4. Go to https://localhost, and enjoy!

### Future expansions
1. In order to use MySQL, libraries more has to be added to fresh Dockerimage [ref](https://github.com/dunglas/frankenphp/blob/main/docs/docker.md#how-to-install-more-php-extensions)

### Licence
MIT licence
* https://choosealicense.com/licenses/mit/
* https://spdx.org/licenses/
