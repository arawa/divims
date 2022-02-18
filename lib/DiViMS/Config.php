<?php
namespace DiViMS;

Class Config {

    /**
     * Configuration array
     * @var array 
     **/
    private $config;

    /**
     * Logger object
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(string $project, \Psr\Log\LoggerInterface $logger) {
        // Retrieve project configuration file
        // Get base directory ('/app')
        $base_directory = '/' . explode('/', $_SERVER['PHP_SELF'])[1];
        $project_directory = "$base_directory/config/project/$project";
        if (!file_exists("$project_directory/config.php")) {
            echo "Error : Unknown project directory or missing config file. Please select an existing project.";
            exit(1);
        }

        include  $project_directory . '/config.php';
        $config['project'] = $project;
        $config['project_directory'] = $project_directory;
        $project_config = $config;

        // Retrieve default configuration file
        include "$base_directory/config/config.defaults.php";
        $config['base_directory'] = $base_directory;
        $default_config = $config;

        // Merge both files
        $this->config = array_merge($default_config, $project_config);

        $this->logger = $logger;
    }

    public function get($item) {
        if (isset($this->config[$item])) {
            return $this->config[$item];
        } else {
            $this->logger->warning("Trying to get missing configuration item", ['item' => $item]);
            return null;
        }
    }

    public function set($item, $value) {
        if (isset($this->config[$item])) {
            $this->logger->info("Change configuration item value.", ['item' => $item, 'value' => $value]);
        } else {
            $this->logger->warning("Add new configuration item.", ['item' => $item, 'value' => $value]);
        }
        $this->config[$item] = $value;
        return true;
    }

}