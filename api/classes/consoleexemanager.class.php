<?php

    /**
     * This class manages the execution of console scripts called by other classes.
     * The console scripts will be passed to this manager with all its parameters. After execution, the console mangaer will
     * return an Array containing two keys: "status: OK|FAIL|ERROR" and "output|error" with the error message.
     * Another use case is giving the ConsoleExeManager a single script and multiple set of parameters. Then telling the manager to execute parallel in different
     * processes or sequentially on the same process.
     */
    class ConsoleExeManager{

        /**
         * @property int MODE_SEQ_SAME_PRC - Sequential execution on the same process
         * @property int MODE_SEQ_DIFF_PRC - Sequential execution on a different process
         * @property int MODE_PAR_DIFF_PRCS - Parallel execution on different processes
         */
        const   MODE_SEQ_SAME_PRC = 0,
                MODE_SEQ_DIFF_PRC = 1,
                MODE_PAR_DIFF_PRCS = 2;


        private function __construct()
        {
            
        }

        /**
         * This function executes a script from the console folder outside the api folder
         * @param string $path - to the script as a reference from within ../console
         * @param array $parameters - array of string parameters to pass to the script. When more than one parameter is passed, then the script will be executed multiple times for each 
         * param.
         * @param int $mode - One of the modes `ConsoleExeManager::MODE_SEQ_SAME_PRC|MODE_SEQ_DIFF_PRC|MODE_PAR_DIFF_PRCS`
         */
        public function execute(string $path, array $parameters, int $mode){

        }



    }
?>