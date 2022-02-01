<?php

    /**
     * This class manages the execution of console scripts called by other classes.
     * The console scripts will be passed to this manager with all its parameters. After execution, the console mangaer will
     * return an Array containing two keys: "status: OK|FAIL|ERROR" and "output|error" with the error message.
     * Another use case is giving the ConsoleExeManager a single script and multiple set of parameters. Then telling the manager to execute parallel in different
     * processes or sequentially on the same process.
     */
    class ConsoleExeManager{

        const   MODE_SEQUENTIAL = 0,
                MODE_PARALLEL = 1;

        private function __construct()
        {
            
        }

        /**
         * This function executes a script from the console folder outside the api folder
         * @param string $path -
         * @param array $parameters - 
         * @param int $mode -
         */
        public function execute(string $path, array $parameters, int $mode){

        }



    }
?>