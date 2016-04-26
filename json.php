<?php
/*
Unifi API Browser - Direct to JSON output for ingestion with sensu/dashing

This tool is for browsing data that is exposed through Ubiquiti's Unifi Controller API,
written in PHP, javascript and the Bootstrap CSS framework.

Please keep the following in mind:
- not all data collections/API endpoints are supported (yet), see the list below of
  the currently supported data collections/API endpoints
- currently only supports versions 4.x.x of the Unifi Controller software
- there is still work to be done to add/improve functionality and usability of this
  tool so suggestions/comments are welcome. Please use the github issue list or the
  Ubiquiti Community forums for this:
  https://community.ubnt.com/t5/UniFi-Wireless/Unifi-API-browser-tool-released/m-p/1392651

VERSION: 1.0.2

------------------------------------------------------------------------------------

The MIT License (MIT)

Copyright (c) 2016, Slooffmaster

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/
/*
load the settings file
*/
include('config.php');

/*
collect cURL version details for the info modal
*/
$curl_info      = curl_version();
$curl_version   = $curl_info['version'];

/*
Collect Variables from URL
$site_id = $_GET['site_id'];
$site_name = $_GET['site_name'];
$action = $_GET['action'];

Actions Available for Sites
list_clients
stat_allusers
stat_sessions
list_devices
list_wlan_groups
list_rogueaps
stat_hourly_site
stat_daily_site
stat_hourly_aps
list_health
list_events

Actions for Controller
list_sites
*/

$site_id = $_GET['site_id'];
#$site_name = $_GET['site_name'];
$action = $_GET['action'];

/*
load the Unifi API connection class and log in to the controller
- if an error occurs during the login process, an alert is displayed on the page
*/
require('phpapi/class.unifi.php');

$unifidata        = new unifiapi($controlleruser, $controllerpassword, $controllerurl, $site_id, $controllerversion);
$unifidata->debug = $debug;
$loginresults     = $unifidata->login();

/*
select the required call to the Unifi Controller API based on the selected action
*/
switch ($action) {
    case 'list_clients':
        $selection  = 'list online clients';
        $data       = $unifidata->list_clients();
        break;
    case 'stat_allusers':
        $selection  = 'stat all users';
        $data       = $unifidata->stat_allusers();
        break;
    case 'stat_auths':
        $selection  = 'stat active authorisations';
        $data       = $unifidata->stat_auths();
        break;
    case 'list_guests':
        $selection  = 'list guests';
        $data       = $unifidata->list_guests();
        break;
    case 'list_usergroups':
        $selection  = 'list usergroups';
        $data       = $unifidata->list_usergroups();
        break;
    case 'stat_hourly_site':
        $selection  = 'hourly site stats';
        $data       = $unifidata->stat_hourly_site();
        break;
    case 'stat_sysinfo':
        $selection  = 'sysinfo';
        $data       = $unifidata->stat_sysinfo();
        break;
    case 'stat_hourly_aps':
        $selection  = 'hourly ap stats';
        $data       = $unifidata->stat_hourly_aps();
        break;
    case 'stat_daily_site':
        $selection  = 'daily site stats';
        $data       = $unifidata->stat_daily_site();
        break;
    case 'list_devices':
        $selection  = 'list devices';
        $data       = $unifidata->list_aps();
        break;
    case 'list_wlan_groups':
        $selection  = 'list wlan groups';
        $data       = $unifidata->list_wlan_groups();
        break;
    case 'stat_sessions':
        $selection  = 'stat sessions';
        $data       = $unifidata->stat_sessions();
        break;
    case 'list_users':
        $selection  = 'list users';
        $data       = $unifidata->list_users();
        break;
    case 'list_rogueaps':
        $selection  = 'list rogue access points';
        $data       = $unifidata->list_rogueaps();
        break;
    case 'list_events':
        $selection  = 'list events';
        $data       = $unifidata->list_events();
        break;
    case 'list_alarms':
        $selection  = 'list alerts';
        $data       = $unifidata->list_alarms();
        break;
    case 'list_wlanconf':
        $selection  = 'list wlan config';
        $data       = $unifidata->list_wlanconf();
        break;
    case 'list_health':
        $selection  = 'site health metrics';
        $data       = $unifidata->list_health();
        break;
    case 'list_dashboard':
        $selection  = 'site dashboard metrics';
        $data       = $unifidata->list_dashboard();
        break;
    case 'list_settings':
        $selection  = 'list site settings';
        $data       = $unifidata->list_settings();
        break;
    case 'list_sites':
        $selection  = 'details of available sites';
        $data       = $sites;
        break;
    case 'list_extension':
        $selection  = 'list VoIP extensions';
        $data       = $unifidata->list_extension();
        break;
    case 'list_portconf':
        $selection  = 'list port configuration';
        $data       = $unifidata->list_portconf();
        break;
    case 'list_networkconf':
        $selection  = 'list network configuration';
        $data       = $unifidata->list_networkconf();
        break;
    case 'list_dynamicdns':
        $selection  = 'dynamic dns configuration';
        $data       = $unifidata->list_dynamicdns();
        break;
    case 'list_portforwarding':
        $selection  = 'list port forwarding rules';
        $data       = $unifidata->list_portforwarding();
        break;
    case 'list_portforward_stats':
        $selection  = 'list port forwarding stats';
        $data       = $unifidata->list_portforward_stats();
        break;
    case 'stat_voucher':
        $selection  = 'list hotspot vouchers';
        $data       = $unifidata->stat_voucher();
        break;
    case 'stat_payment':
        $selection  = 'list hotspot payments';
        $data       = $unifidata->stat_payment();
        break;
    case 'list_hotspotop':
        $selection  = 'list hotspot operators';
        $data       = $unifidata->list_hotspotop();
        break;
    case 'list_self':
        $selection  = 'self';
        $data       = $unifidata->list_self();
        break;
    default:
        break;
}

/*Output the JSON*/
header('Content-Type: application/json');
echo json_encode($data, JSON_PRETTY_PRINT);

$logout_results = $unifidata->logout();
