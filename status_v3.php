<?php
	//Paste all your API Keys to be used here
	$api_key = array(
		"paste_your_api_key_1",
		"paste_your_api_key_2",
		"paste_your_api_key_3",
		"paste_your_api_key_N"
	);
	//Count the amount of API Keys
	$api_key_amount = count($api_key);

	date_default_timezone_set('UTC');
	//Unix time stamp for now, today, yesterday, day before yesterday(0:00)
	$now = time();
	$today = strtotime(date('Y-m-d', time())." 00:00:00");
	$yesterday = $today - 86400;
	$twodayago = $today - 172800;
?>
<html>
<head>
	<title>服务器状态监控</title>
	<meta content="text/html" charset="UTF-8">
	<style type="text/css">
	.status thead {
		background-color: #23a1c0;
	}
	.status tbody tr:nth-child(odd) td {
		background-color: #9adced;
	}
	.status tbody tr:nth-child(even) td {
		background-color: #a6f3f7;
	}
	.status tbody tr {
		font-size: 1.4em;
		text-align: center;
	}
	.status thead tr {
		font-size: 1.2em;
		text-align: center;
	}
	p.title {
		text-align: center;
		text-shadow: 0px 0px 6px #8c8c8c;
		font-size: 3.5em;
		margin-top: 0.5em;
		margin-bottom: 3em;
	}
	p.powered_by{
		text-align: center;
		font-size: 1em;
		margin-bottom: 1.2em;
	}
	</style>
</head>
<body>
	<p class="title">服务器状态监控</p>
	<table class="status" align="center" rules="none" cellpadding="7.5%">
	<thead><tr>
		<th rowspan="2">当前状态</th>
		<th rowspan="2">名称</th>
		<th rowspan="2">URL</th>
		<th colspan="5">可用率</th>
	</tr><tr>
		<th>今天<br><?php echo date('M j', time()); ?></th>
		<th>昨天<br><?php echo date('M j', time() - 86400); ?></th>
		<th>前天<br><?php echo date('M j', time() - 172800); ?></th>
		<th>本周</th>
		<th>本月</th>
	</tr></thead>
	<tbody>
<?php
//For Each API Key
for($x=0;$x<$api_key_amount;$x++) {
	//Fetch data by cURL
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://api.uptimerobot.com/v2/getMonitors",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => "api_key=".$api_key[$x]."&format=json&custom_uptime_ranges=".$today."_".$now."-".$yesterday."_".$today."-".$twodayago."_".$yesterday."&custom_uptime_ratios=7-30",
		CURLOPT_HTTPHEADER => array(
			"cache-control: no-cache",
			"content-type: application/x-www-form-urlencoded"
		),
	));
	
	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);

	//Decode fetched JSON
	$decoded = json_decode($response);
	//Convert uptime data into arrays
	$uptime_ranges = explode("-", $decoded->monitors[0]->custom_uptime_ranges);
	$uptime_ratios = explode("-", $decoded->monitors[0]->custom_uptime_ratio);

	//Create table row
	echo "<tr>
	<td>",str_replace(array("0","1","2","8","9"), array("(暂停)","(待测)","可用","或不可用","不可用"), $decoded->monitors[0]->status),"</td>
	<td>",$decoded->monitors[0]->friendly_name,"</td>
	<td>",$decoded->monitors[0]->url;
	//if port number exist, add it after URL or IP
	if ($decoded->monitors[0]->port != NULL) {
		echo ":",$decoded->monitors[0]->port;
	}
	echo "</td>
	<td>",round($uptime_ranges[0],2),"%</td>
	<td>",round($uptime_ranges[1],2),"%</td>
	<td>",round($uptime_ranges[2],2),"%</td>
	<td>",round($uptime_ratios[0],2),"%</td>
	<td>",round($uptime_ratios[1],2),"%</td>
	</tr>";
}
?>
	</tbody>
	</table>
	<p class="powered_by">Version:3.0</p>
	<p class="powered_by">Powered by <a href="https://uptimerobot.com/" target="_blank" rel="nofollow">UptimeRobot</a>|Page designed by <a href="https://hardrain980.com" target="_blank">Hardrain980</a>|Fork me on <a href="https://github.com/hardrain980/status-monitor" target="_blank">Github</a></p>
</body>
</html>
