<?php

$config = [
    /*
     * General
    */
    // Poll max number of workers
    'poll_max_workers' => 100,
    // Max number of parallel processes to clone servers
    'clone_max_workers' => 5,
    // Whether to use routed or natted IP (NAT used to be the default at Scaleway)
    'clone_enable_routed_ip' => false,
    // SSH commands timeout in seconds
    'ssh_timeout' => 10,
    'hoster_api' => 'SCW',
    // Bare-metal servers
    'bare_metal_servers_count' => 0,

    /*
     * Capacity adaptation
     */
    // Common
    // Adaptation policy. Possible value are (schedule|load|both)
    'capacity_adaptation_policy' => 'both',
    // Number of minutes between two controller cron runs
    'controller_run_frequency' => 5,
    // Duration in minutes above which meetings will be forcibly ended
    'meetings_max_duration' => 600,
    // Duration in minutes above which send a warning for recording still processing
    'recordings_max_processing_duration' => 300,
    // Paths used to check if recordings transfers succeeded
    'recordings_path_source' => '/var/bigbluebutton/published/presentation',
    'recordings_path_target' => '/mnt/scalelite-recordings/var/bigbluebutton/published/presentation',

    'maintenance_file_suffix' => '_ServersInMaintenance',

    // Max uptime in seconds above which a server is forcibly recycled
    // This is to prevent errors that might occur after a long uptime
    'server_max_recycling_uptime' => 60*60*24*7,

    // SSL certifcate checks message delays (in number of days before expiration)
    'ssl_certificate_validity_days_warning' => 14,
    'ssl_certificate_validity_days_error' => 7,
    'ssl_certificate_validity_days_alert' => 1, // Wake up administrators

    // Adaptation from schedule
    'ical_cached_file_suffix' => '_adaptationCalendar',

    // Adaptation from load
    // Duration in seconds before discarding data in past load data file
    'load_adaptation_data_file_suffix' => '_loadAdaptationData',
    // Maximum number of participants a server can handle
    'load_adaptation_server_participants_capacity' => 250,
    // Maximum number of meetings a server can handle
    'load_adaptation_server_meetings_capacity' => 15,
    // Minimum pourcentage of the pool that must be active
    'load_adaptation_active_servers_minimum_ratio' => 0.01,
    // Multiply current participants count by following factors to define next capacity
    'load_adaptation_participants_capacity_factor_1' => 2,
    'load_adaptation_participants_capacity_factor_2' => 3,
    'load_adaptation_meetings_capacity_factor_1' => 2,
    'load_adaptation_meetings_capacity_factor_2' => 3,
    // Load variation ratio separating factor_1 from factor_2
    'load_adaptation_participants_variation_ratio_threshold' => 1.2,
    'load_adaptation_meetings_variation_ratio_threshold' => 1.2,

];

?>