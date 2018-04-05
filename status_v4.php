<?php
	//Paste all your API Keys to be used here
	$api_key = array(
		"",
		"",
		""
	);

  //Set timezone according to your Uptimerobot account profile.
	date_default_timezone_set("UTC");
	$now = time();
	$today = strtotime(date('Y-m-d', time())." 00:00:00");
	$yesterday = $today - 86400;
	$twodayago = $today - 172800;

	function utr($api_key) {
		global $now, $today, $yesterday, $twodayago;
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://api.uptimerobot.com/v2/getMonitors");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_ENCODING, "");
		curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
		curl_setopt($curl, CURLOPT_TIMEOUT, 5);
		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			"cache-control: no-cache",
			"content-type: application/x-www-form-urlencoded"
		));
		curl_setopt($curl, CURLOPT_POSTFIELDS, 
			"api_key=".$api_key."&format=json&custom_uptime_ranges=".$today."_".$now."-".$yesterday."_".$today."-".$twodayago."_".$yesterday."&custom_uptime_ratios=7-30");
		$responce_json = json_decode(curl_exec($curl));
		curl_close($curl);

		$range = explode("-", $responce_json->monitors[0]->custom_uptime_ranges);
		$ratio = explode("-", $responce_json->monitors[0]->custom_uptime_ratio);

		$status = str_replace(array("0","1","2","8","9"), array("(暂停)","(待测)","可用","或不可用","不可用"), $responce_json->monitors[0]->status);
		$name = $responce_json->monitors[0]->friendly_name;
		$url = $responce_json->monitors[0]->url;
		if ($responce_json->monitors[0]->port != NULL) {
			$url .= ":".$responce_json->monitors[0]->port;
		}
		$uptime = array(
			round($range[0], 2),
			round($range[1], 2),
			round($range[2], 2),
			round($ratio[0], 2),
			round($ratio[1], 2)
		);
		return array($status, $name, $url, $uptime);
	}
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
foreach ($api_key as $single_api_key) {
	$responce = utr($single_api_key);
	//Create table row
	echo "<tr>
	<td>".$responce[0]."</td>
	<td>".$responce[1]."</td>
	<td>".$responce[2]."</td>
	<td>".$responce[3][0]."%</td>
	<td>".$responce[3][1]."%</td>
	<td>".$responce[3][2]."%</td>
	<td>".$responce[3][3]."%</td>
	<td>".$responce[3][4]."%</td>
	</tr>";
}
?>
	</tbody>
	</table>
	<p class="powered_by">Version:4.0 | Data displayed are based on <?php echo date("T"); ?> time.</p>
	<p class="powered_by">Powered by <a href="https://uptimerobot.com/" target="_blank" rel="nofollow">UptimeRobot</a> | Page designed by <a href="https://hardrain980.com" target="_blank">Hardrain980</a> | Fork me on <a href="https://github.com/hardrain980/status-monitor" target="_blank">Github</a></p>
</body>
</html>
