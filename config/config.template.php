<?php

$config = array(
  // OVH credentials, validity: unlimited, permissions : GET,POST,PUT,DELETE /domain/zone/{dns-zone}/*
  'ovh_application_key' => '',
  'ovh_application_secret' => '',
  'ovh_consumer_key' => '',
  'ovh_endpoint' => 'ovh-eu',

  // Scaleway credentials
  'scw_zone' => "fr-par-1",
  'scw_auth_token' => '',
  'scw_organization_id' => '',
  'scw_project_id' => '',

  'zabbix_api_url' => 'https://zabbix.example.com/api_jsonrpc.php',
  'zabbix_username' => '',
  'zabbix_password' => '',


  // Log level used by the application
  'log_level' => \Monolog\Logger::INFO,

  //Number of servers in the pool
  'pool_size' => 1,
  //Number of maximum participants on the whole pool
  'pool_capacity' => 10000,

  //Bigbluebutton version minor number e.g 2.3
  'bbb_version' => '',

  /*
   * Cloning configuration
   */
  //Max number of parallel processes to clone servers
  //'clone_max_workers' => 5,

  // Hoster
  //'hoster_api' => "SCW", //e.g. SCW
  
  //Prefix of the image to be cloned at Scaleway
  //Used to retrieve the id of the image to clone from
  'clone_image_name' => "",

  //Old data
  // Domain FQDN of the machine that will be cloned : "old" own
  // Example : bbb-w1.example.com
  'clone_old_domain' => '',
  'clone_old_external_ipv4' => '', //e.g. 132.11.12.13
  'clone_old_external_ipv6' => '',
  'clone_old_internal_ipv4' => '',

  // API Secret of the BigBlueButton machine to be cloned
  'clone_bbb_secret' => '',

  // Clone uses a wildcard certificate for HTTPS ? (true/false)
  'clone_use_wildcard_cert' => true,
  // New server name
  // Put an 'X' as a placeholder for the clone number
  // Example : company-p-bbb-wX
  'clone_hostname_template' => '',
  // New BigBlueButton domain name
  // Put an 'X' as a placeholder for the clone number
  // Example : bbb-wX.example.com
  'clone_domain_template' => '',
  //Commercial type of the newly created SCW instance
  'clone_commercial_type' => '', // e.g. GP1-M
  //In case we're cloning and reusing IPs from previous machines (specs upgrade)
  'clone_reuse_public_ip' => false,
  'clone_old_commercial_type' => '', //e.g GP1-S
  
  // Domain name for DNS entries
  //'dns_zone' => 'arawa.fr',
  //'subdomain' => 'infra',
  'clone_dns_create_entry' => true,
  'clone_dns_create_ipv6' => false,
  // DNS API provider : only OVH as for now
  'clone_dns_entry_api' => 'OVH', 
  'clone_dns_entry_zone' => 'arawa.fr',
  'clone_dns_entry_subdomain' => 'ext',
  // Create a CNAME type entry instead of A
  'clone_dns_create_cname' => false,

  // FQDN domaine name of the scalelite server
  // Example : scalelite.example.com
  'scalelite_host' => '',
  // Should we add the cloned server to Scalelite's inventory and enable it
  'clone_add_to_scalelite' => true,
  'clone_enable_in_scalelite' => true,

  // Name of the private SSH key file
  'ssh_rsa' => '/assets/id_rsa',
  'ssh_port' => 22,
  'ssh_user' => 'root',

  //Poll max number of parallel workers
  //'poll_max_workers' => 100,

  /*
   * Capacity adaptation
   */
  // Read-only ical stream with course scheduling in ICS format : HTTPS link
  'ical_stream' => '',
  // Default ICS file in case stream is unavailable. Located in project directory.
  //'ical_default_file' => '',
);

?>
