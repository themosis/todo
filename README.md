Tasks Application
-----------------

This repository is an example of a simple tasks management application build using WordPress and the Themosis framework.

Feel free to download the repo and install it locally for testing and exploring the APIs used from the Themosis framework. Follow the steps below in order to install this application on your computer.

## Installation

1. Download this repository on your machine.
2. Open a Console or Terminal and navigate to the root of this repository.
3. Run `composer install` in order to install WordPress, the Themosis framework and other dependencies.
4. Fill in the `env.local.php` file with your local environment credentials.
5. Set your local hostname or local environment variable in the `config/environment.php` file. For more information regarding installation, check also the [Themosis framework documentation](http://framework.themosis.com/docs/installation/) for detailed explanations.
6. Set the `htdocs` folder as your webserver public directory.
7. Open your browser and navigate to your local server address (specified in your `env.local.php` file).
8. Install WordPress
9. Log in the administration.
10. Under `appearance->themes`, activate the Themosis theme available.
11. Set a permalink structure other than default.
12. Visit the home page of your application. Enjoy!

---

### Development

The framework was created by [Julien Lambé](http://www.themosis.com/), who continues to lead the development.

### Contributing to Themosis

All issues and pull requests should be filled on the [themosis/framework](https://github.com/themosis/framework/issues) repository.

### License

The Themosis framework is open-source software licensed under [GPL-2+ license](http://www.gnu.org/licenses/gpl-2.0.html).