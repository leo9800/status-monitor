# status-monitor
A PHP based Site-status monitor powered by [Uptime Robot](https://uptimerobot.com/)

### How to use

0. Register an Uptime Robot Account
0. Create at least 1 HTTP(s) monitor
0. Obtain the API key for this server (Detailed information introduced in next section)
0. paste your API key to the line 2 of `status.php`
0. upload `status.php` to your webroot

### How to obtain the API Key(s)

After Add monitor(s),

0. Click ***My Settings*** on the top of the page
0. Find the card named ***API Settings***
0. Click ***Show/hide it*** in ***Monitor-Specific API Keys***( _NOT_ the Main API Key)
0. Type in the name of monitor and select
0. A ***m-*** (not ***u-*** , which is the _Main API Key_) started API key would shown below

### Reference

cURL part:[https://uptimerobot.com/api](https://uptimerobot.com/api)
