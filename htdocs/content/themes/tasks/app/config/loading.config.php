<?php

return array(

    /**
     * Mapping for all classes without a namespace.
     * The key is the class name and the value is the
     * absolute path to your class file.
     *
     * Watch your commas...
     */
    // Controllers
    'BaseController'        => themosis_path('app').'controllers'.DS.'BaseController.php',
    'TasksController'       => themosis_path('app').'controllers'.DS.'TasksController.php',

    // Models
    'PostModel'             => themosis_path('app').'models'.DS.'PostModel.php',
    'TasksModel'            => themosis_path('app').'models'.DS.'TasksModel.php'

    // Miscellaneous

);