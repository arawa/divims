<?php
namespace DiViMS;

Class SSH {

    /**
     * Base ssh command
     * @var string
     */
    private $base_ssh_command;
    
    /**
     * Configuration object
     * @var \DiViMS\Config
     */
    private $config;

    /**
     * Logger object
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * STDOUT of SSH command
     * @var string
     */
    private $output;

    /**
     * Number of SSH tries
     * @var int
     */
    private $tries;
 
    /**
     * SSH command return value
     * @var int
     */
    private $return_value; 

    public function __construct(array $params, \DiViMS\Config $config, \Psr\Log\LoggerInterface $logger) {

        $this->config = $config;
        $this->logger = $logger;

        if (!isset($params['host'])) {
            $this->logger->error('Missing parameter "host" for SSH command');
            return false;
        }
        $host = $params['host'];
        $user = $params['user'] ?? $this->config->get('ssh_user');
        $port = $params['port'] ?? $this->config->get('ssh_port');

        $rsa = $params['rsa'] ?? $this->config->get('project_directory') . '/' . $this->config->get('ssh_rsa');

        $this->base_ssh_command = "ssh -i $rsa -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null -o ConnectTimeout=2 -p$port $user@$host";
    }

    public function exec(string $command, array $params = []) {

        $max_tries = $params['max_tries'] ?? 1;
        $debug = $params['debug'] ?? false;
        $timeout = $params['timeout'] ?? $this->config->get('ssh_timeout');;
		$sleep_time =  $params['sleep_time'] ?? 1;

        $stderr = ($debug == false) ? "2>/dev/null" : "";

        $count=0;
        while (true)
        {
            $count++;
            exec("timeout $timeout ". $this->base_ssh_command . " $command $stderr", $output, $return_value);
            $this->logger->debug("Executing SSH command", ['command' => $this->base_ssh_command . " $command $stderr", "return_val" => $return_value, "try" => $count]);
            $this->output = implode("\n", $output);
            $this->tries = $count;
            $this->return_value = $return_value;
            if ($return_value == 0)
            {
                return true;
            } elseif ($count == $max_tries) {
                $this->logger->error("SSH command failed. Max tries reached.", ['command' => $this->base_ssh_command . " $command $stderr", "return_val" => $return_value, "try" => $count]);
                return false;
            }
            $this->logger->warning("SSH command failed. Retrying in $sleep_time seconds.", ['command' => $this->base_ssh_command . " $command $stderr", "return_val" => $return_value, "output" => $this->output, "try" => $count]);
            sleep($sleep_time);
        }

    }

    public function getOutput() {
        return $this->output;
    }

    public function getTries() {
        return $this->tries;
    }

    public function getReturnValue() {
        return $this->return_value;
    }
}