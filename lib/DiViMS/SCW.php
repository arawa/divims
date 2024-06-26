<?php

namespace DiViMS;

use Exception;

class SCW {

  /**
   * API URL Example : https://api.scaleway.com/instance/v1/zones/
   * @var string
   */
  private $curl_base_url = "https://api.scaleway.com/instance/v1/zones/";

  /**
   * API zone, Example  : fr-par-1
   * @var string
   */
  private $zone = null;

  /**
   * API Auth Token
   * @var string
   */
  private $auth_token = null;

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
   * Construct a new wrapper instance
   *
   * @param $params array Array of optional parameters ['zone' => ('fr-par-1'|'fr-par-2'), 'auth_token' => 'auth token secret of your application']
   *
   * @throws Exceptions\InvalidParameterException if one parameter is missing or with bad value
   */
  public function __construct(array $params = [], \DiViMS\Config $config, \Psr\Log\LoggerInterface $logger) {

    $this->config = $config;
    $this->logger = $logger;

    $zone = $params['zone'] ?? $this->config->get('scw_zone');
    $auth_token = $params['auth_token'] ?? $this->config->get('scw_auth_token');

    $this->curl_base_url = $this->curl_base_url . $zone;
    $this->auth_token = $auth_token;

  }


  private function get(string $serviceURL, array $params = []) {
    # An HTTP GET request example (https://alvinalexander.com/php/php-curl-examples-curl_setopt-json-rest-web-service/)

    
    $endpoint = $this->curl_base_url . $serviceURL;
    $ch = curl_init($endpoint);
    if (!empty($params)) {
        $url = $endpoint . '?' . http_build_query($params);
    } else {
        $url = $endpoint;
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'X-Auth-Token: ' . $this->auth_token,
      'Content-type: application/json'
    ));

    //https://stackoverflow.com/questions/9183178/can-php-curl-retrieve-response-headers-and-body-in-a-single-request/25118032#25118032
    $headers = [];
    curl_setopt($ch, CURLOPT_HEADERFUNCTION,
      // this function is called by curl for each header received
      function($curl, $header) use (&$headers)
      {
        $len = strlen($header);
        $header = explode(':', $header, 2);
        if (count($header) < 2) // ignore invalid headers
          return $len;

        $headers[strtolower(trim($header[0]))][] = trim($header[1]);

        return $len;
      }
    );


    $this->logger->debug("Performing SCW API GET Request", ["url" => $url]);
    $data = curl_exec($ch);
    curl_close($ch);

    if ($data !== false) {
      $result = json_decode($data, true);

      if (is_array($result)) {
        if (isset($headers['x-total-count'])) {
          $result = array_merge($result, ['total_count' => intval($headers['x-total-count'][0])]);
          $this->logger->debug("SCW API GET request total count : {$headers['x-total-count'][0]}");
        }
        return $result;
      } else {
        $this->logger->warning('Response error at SCW API GET request: data can not be converted to array.', ['url' => $url, 'response_data' => $data]);
        return false;
      }
    } else {
      $this->logger->warning('Curl error at SCW API GET request', ['url' => $url, 'response_data' => $data]);
      return false;
    }


  }

  private function post(string $serviceURL, array $post_data) {
    # data needs to be POSTed to the Play url as JSON.
    # (some code from http://www.lornajane.net/posts/2011/posting-json-data-with-php-curl)
    //$data = array("id" => "$id", "symbol" => "$symbol", "companyName" => "$companyName");
    $data_string = json_encode($post_data);

    $endpoint = $this->curl_base_url . $serviceURL;
    $ch = curl_init($endpoint);
    $url = $endpoint;

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'X-Auth-Token: ' . $this->auth_token,
      'Content-type: application/json'
    ));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

    $response_data = curl_exec($ch);
    curl_close($ch);

    if ($response_data !== false) {
      $result = json_decode($response_data, true);

      if (is_array($result)) {
        return $result;
      } else {
        $this->logger->warning('Response error at SCW API POST request: data can not be converted to array.', compact('url', 'response_data'));
        return false;
      }
    } else {
      $this->logger->warning('Curl error at SCW API POST request', compact('url', 'data_string', 'response_data'));
      return false;
    }


    return json_decode($response_data, true);

  }

  private function patch(string $serviceURL, array $post_data) {
    # data needs to be POSTed to the Play url as JSON.
    # (some code from http://www.lornajane.net/posts/2011/posting-json-data-with-php-curl)
    //$data = array("id" => "$id", "symbol" => "$symbol", "companyName" => "$companyName");
    $data_string = json_encode($post_data);

    $endpoint = $this->curl_base_url . $serviceURL;
    $ch = curl_init($endpoint);
    $url = $endpoint;

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'X-Auth-Token: ' . $this->auth_token,
      'Content-type: application/json'
    ));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    $data = curl_exec($ch);
    curl_close($ch);

    return json_decode($data, true);

  }

  public function getImages(array $params) {
    return $this->get("/images/", $params);
  }

  /**
   * List all servers with pattern matching
   * !! WARNING not an exact match !! 'name=server1' returns "server1" AND "server100"...
   * For an exact match use getServerByName
   * Docs : https://developers.scaleway.com/en/products/instance/api/#get-2c1c6f
   * @param array Array of query parameters
   **/
  public function getServers(array $params) {
    $page = 1;
    $per_page = 100;
    $max_pages = intval(ceil($this->config->get('pool_size') / $per_page));
    $default_params = ['project' => $this->config->get('scw_project_id'), 'per_page' => $per_page, 'page' => $page];
    $params = array_merge($params, $default_params);

    $result = $this->get("/servers/", $params);

    // Get all paged results at once
    if (isset($result['total_count']) && $result['total_count'] > $per_page) {
      $total_count = $result['total_count'];
      $count = count($result['servers']);
      $servers = $result['servers'];

      while ($count < $total_count and $page < $max_pages) {
        $page++;
        $params = array_merge($params, ['page' => $page]);
        $result = $this->get("/servers/", $params);
        if (isset($result['servers'])) {
          $count += count($result['servers']);
          $servers = array_merge($servers, $result['servers']);
        }
      }
      return ['servers' => $servers];
    } else {
      return $result;
    }
  }

  public function getServerByID($server_id) {
    return $this->get("/servers/$server_id");
  }

  public function getIP($address) {
    return $this->get("/ips/$address");
  }
  
  /**
   * List one single server exactly matching server name
   * @param string $server_name the server name to search for
   **/
  public function getServerByName(string $server_name) {

    // Retrieve all servers matching (starting with) name
    $result = $this->getServers(['name' => $server_name]);

    // select exact match
    if (isset($result['servers'])) {
      foreach($result['servers'] as $server) {
        if ($server['name'] == $server_name) return ['servers' => [$server]];
      }
    }

    return null;

  }


  public function updateServer(string $server_id, array $post_data) {
    return $this->post("/servers/$server_id", $post_data);
  }

  public function createServer(array $post_data) {
    return $this->post("/servers/", $post_data);
  }

  public function reserveIP(array $post_data) {
    return $this->post("/ips/", $post_data);
  }

  public function updateIP(string $ip_id, array $post_data) {
    return $this->patch("/ips/$ip_id", $post_data);
  }
  
  public function getServerAction(string $server_id) {
    return $this->get("/servers/$server_id/action");
  }
 
  /**
   * Perform action on server
   * @param string $server_id The server hoster id
   * @param array $post_data ['action' => (terminate|poweron|poweroff|reboot)]
   **/
  public function actOnServer(string $server_id, array $post_data) {

    /*
      Example response
      {
        "task": {
            "id": "8dc7ef90-7bda-499c-a296-f19723459e67",
            "description": "server_reboot",
            "status": "pending",
            "href_from": "\/servers\/1c061f17-2ac0-4979-a8a4-63b27c9873da\/action",
            "href_result": "\/servers\/1c061f17-2ac0-4979-a8a4-63b27c9873da",
            "started_at": "2021-06-25T10:11:17.013768+00:00",
            "terminated_at": null
        }
      }
    */
    $tries = 0; 
    $max_tries = 3;
    while (true) {
      $tries++;
      try {
        $response = $this->post("/servers/$server_id/action", $post_data);
        if (isset($response['task']) && $response['task']['status'] == 'pending') {
            $this->logger->debug("Success : Action pending", ['action' => $post_data['action'], 'server_id' => $server_id, 'try' => $tries, 'hoster_response' => json_encode($response, JSON_PRETTY_PRINT)]);
            return true;
        } elseif ($tries == $max_tries) {
            $this->logger->error("Hoster API action failed after $max_tries tries. Abandoning.", ['action' => $post_data['action'], 'server_id' => $server_id, 'hoster_response' => json_encode($response, JSON_PRETTY_PRINT)]);
            return false;
        } else {
            $this->logger->warning("Hoster API action failed. Retrying in 1 second.", ['action' => $post_data['action'], 'try' => $tries, 'server_id' => $server_id, 'hoster_response' => json_encode($response, JSON_PRETTY_PRINT)]);
            sleep(1);
        } 
      } catch(\Exception $e) {
        $this->logger->error("Scaleway API error: " . $e->getMessage());
        return false;
      }              
    }
  }

}
