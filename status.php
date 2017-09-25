<?php
	$api_key = array("paste_your_api_key_1_here","paste_your_api_key_2_here","paste_your_api_key_3_here","paste_your_api_key_N_here");
	$api_key_number = count($api_key);

	$stat_orig = array("ok","fail");
	$stat_rpl = array("可用","不可用");
?>
<html>
<head>
	<title>服务器状态监控</title>
	<meta content="text/html" charset="UTF-8">
	<style type="text/css">
	.status thead {
		background-color: cyan;
	}
	.status tbody tr:nth-child(even) td {
		background-color:bisque;
	}
	.status tbody tr:nth-child(odd) td {
		background-color:cadetblue;
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
		<th>当前状态</th>
		<th>名称</th>
		<th>URL</th>
		<th>今天可用率</th>
		<th>近三天可用率</th>
		<th>本星期可用率</th>
		<th>本月可用率</th>
	</tr></thead>
	<tbody>
<?php
for($x=0;$x<$api_key_number;$x++) {
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://api.uptimerobot.com/v2/getMonitors",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => "api_key=".$api_key[$x]."&format=json&custom_uptime_ratios=1-3-7-30",
		CURLOPT_HTTPHEADER => array(
			"cache-control: no-cache",
			"content-type: application/x-www-form-urlencoded"
		),
	));
	
	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);
	//if ($err) {
	//	echo "cURL Error #:" . $err;
	//} else {
	//	echo $response;
	//}

	$decoded = json_decode($response);
	$uptime = explode("-", $decoded->monitors[0]->custom_uptime_ratio);
	$uptime_1d = $uptime[0];
	$uptime_3d = $uptime[1];
	$uptime_1w = $uptime[2];
	$uptime_1m = $uptime[3];
	//echo $decoded->stat,"<br />",$decoded->monitors[0]->friendly_name,"<br />",$decoded->monitors[0]->url,"<br />";
	echo "<tr>
	<td>",str_replace($stat_orig, $stat_rpl, $decoded->stat),"</td>
	<td>",$decoded->monitors[0]->friendly_name,"</td>
	<td>",$decoded->monitors[0]->url,"</td>
	<td>",round($uptime_1d,2),"%</td>
	<td>",round($uptime_3d,2),"%</td>
	<td>",round($uptime_1w,2),"%</td>
	<td>",round($uptime_1m,2),"%</td>
	</tr>";
}
?>
	</tbody>
	</table>
	<p class="powered_by">Powered by <a href="https://uptimerobot.com/" target="_blank" rel="nofollow">UptimeRobot</a>|Page designed by <a href="https://hardrain980.com" target="_blank">Hardrain980</a></a></p>
	<p class="powered_by">Fork me on <a href="https://github.com/hardrain980/status-monitor" target="_blank">Github</a></p>
</body>
</html>
