<?php
/*
 * Operating systems pie chart
 * Browsers pie chart (Firefox, IE, etc..)
 * Browser version reprezentation like: Firefox 5, Firefox 6, Firefox 7, Firefox 8, IE 6, IE 7, IE 9, etc.
 * Resolution pie chart (most used, up to 10)
 * 
 */
?>
<?php
global $wpdb;

$no_entries = 0;

$chart_size_width = 350;//Default chart width
$chart_size_height = 200;//Default chart height
//
//General browser statistics
$ws_query = "SELECT `ws_statistico_name` as browser, SUM(`ws_agent_count`) as cnt FROM `".$wpdb->prefix."ws_statistico_agent` GROUP BY `ws_statistico_name`";
$ws_results = $wpdb->get_results($ws_query, OBJECT);

$explorer_count = 0;
$firefox_count = 0;
$chrome_count = 0;
$safari_count = 0;
$opera_count = 0;
$other_browser_count = 0;
$browsers_count = 0;

foreach ($ws_results as $ws_result){
    if($ws_result->browser == 'Explorer'){
        $explorer_count = $explorer_count +  $ws_result->cnt;
    }
    if($ws_result->browser == 'Firefox'){
        $firefox_count = $firefox_count  +  $ws_result->cnt;
    }
    if($ws_result->browser == 'Chrome'){
        $chrome_count = $chrome_count  +  $ws_result->cnt;
    }
    if($ws_result->browser == 'Safari'){
        $safari_count = $safari_count  +  $ws_result->cnt;
    }
    if($ws_result->browser == 'Opera'){
        $opera_count = $opera_count  +  $ws_result->cnt;
    }
    if($ws_result->browser !== 'Explorer' && $ws_result->browser !== 'Firefox' && $ws_result->browser !== 'Chrome' && $ws_result->browser !== 'Safari' && $ws_result->browser !== 'Opera'){
        $other_browser_count = $other_browser_count  +  $ws_result->cnt;
    }
    $browsers_count = $browsers_count  +  $ws_result->cnt;
}

$browser_percent = 0;
$browser_percent = ($browsers_count / 100);

$explorer_percent = 0;
$explorer_percent = round($explorer_count / $browser_percent, 2);

$firefox_percent = 0;
$firefox_percent = round($firefox_count / $browser_percent, 2);

$chrome_percent = 0;
$chrome_percent = round($chrome_count / $browser_percent, 2);

$safari_percent = 0;
$safari_percent = round($safari_count / $browser_percent, 2);

$opera_percent = 0;
$opera_percent = round($opera_count / $browser_percent, 2);

$other_percent = 0;
$other_percent = round($other_browser_count / $browser_percent, 2);

//OS Statistics
$ws_query = "SELECT `ws_os` as OS, SUM(`ws_agent_count`) as cnt FROM `".$wpdb->prefix."ws_statistico_agent` GROUP BY `ws_os`";
$ws_results = $wpdb->get_results($ws_query, OBJECT);

$windows_count = 0;
$linux_count = 0;
$mac_count = 0;
$other_os_count = 0;
$os_count = 0;

foreach ($ws_results as $ws_result){
    if($ws_result->OS == 'Windows'){
        $windows_count = $windows_count +  $ws_result->cnt;
    }//Windows
    if($ws_result->OS == 'Linux'){
        $linux_count = $linux_count  +  $ws_result->cnt;
    }//Linux
    if($ws_result->OS == 'Mac'){
        $mac_count = $mac_count  +  $ws_result->cnt;
    }//Mac
    if($ws_result->OS !== 'Windows' && $ws_result->OS !== 'Linux' && $ws_result->OS !== 'Mac'){
        $other_os_count = $ws_result->OS !== 'Mac'  +  $ws_result->cnt;
    }//Other OS
    $os_count = $os_count  +  $ws_result->cnt;
}

$os_percent = 0;
$os_percent = ($os_count / 100);

$windows_count = 0;
$windows_count = round($windows_count / $os_percent, 2);

$linux_count = 0;
$linux_count = round($linux_count / $os_percent, 2);

$mac_count = 0;
$mac_count = round($mac_count / $os_percent, 2);

$other_os_count = 0;
$other_os_count = round($other_os_count / $os_percent, 2);

//Particular browser chart statistic
$ws_query = "SELECT ws_statistico_name, ws_statistico_version, sum(`ws_agent_count`) as cnt FROM `".$wpdb->prefix."ws_statistico_agent` GROUP BY ws_statistico_name, ws_statistico_version ORDER BY ws_statistico_name, cnt DESC";
$ws_results = $wpdb->get_results($ws_query, OBJECT);

$explorer_string = '';
$explorer_string_percent = 0;
$explorer_values = '';

$firefox_string = '';
$firefox_string_percent = 0;
$firefox_values = '';

$chrome_string = '';
$chrome_string_percent = 0;
$chrome_values = '';

$safari_string = '';
$chrome_string_percent = 0;
$chrome_values = '';

$opera_string = '';
$opera_string_percent = 0;
$opera_values = '';

foreach ($ws_results as $ws_result){
    if($ws_result->ws_statistico_name !== 'An unknown browser' && $ws_result->ws_statistico_version !== 'N/A'){
    if($ws_result->ws_statistico_name == 'Explorer' || $ws_result->ws_statistico_name == 'Firefox' || $ws_result->ws_statistico_name == 'Chrome' || $ws_result->ws_statistico_name == 'Safari' || $ws_result->ws_statistico_name == 'Opera'){
        if($ws_result->ws_statistico_name == 'Explorer'){
            $explorer_string = $explorer_string.'|'.$ws_result->ws_statistico_version;
            $explorer_string_percent = $explorer_string_percent.'|'.round($ws_result->cnt / ($explorer_count / 100), 2);
            $explorer_values = $explorer_values.','.round($ws_result->cnt / ($explorer_count / 100), 2);
        }//Explorer
        if($ws_result->ws_statistico_name == 'Firefox'){
            $firefox_string = $firefox_string.'|'.$ws_result->ws_statistico_version;
            $firefox_string_percent = $firefox_string_percent.'|'.round($ws_result->cnt / ($firefox_count / 100), 2);
            $firefox_values = $firefox_values.','.round($ws_result->cnt / ($firefox_count / 100), 2);
        }//Firefox
        if($ws_result->ws_statistico_name == 'Chrome'){
            $chrome_string = $chrome_string.'|'.$ws_result->ws_statistico_version;
            $chrome_string_percent = $chrome_string_percent.'|'.round($ws_result->cnt / ($chrome_count / 100), 2);
            $chrome_values = $chrome_values.','.round($ws_result->cnt / ($chrome_count / 100), 2);
        }//Chrome
        if($ws_result->ws_statistico_name == 'Safari'){
            $safari_string = $safari_string.'|'.$ws_result->ws_statistico_version;
            $safari_string_percent = $safari_string_percent.'|'.round($ws_result->cnt / ($safari_count / 100), 2);
            $safari_values = $safari_values.','.round($ws_result->cnt / ($safari_count / 100), 2);
        }//Safari
        if($ws_result->ws_statistico_name == 'Opera'){
            $opera_string = $opera_string.'|'.$ws_result->ws_statistico_version;
            $opera_string_percent = $opera_string_percent.'|'.round($ws_result->cnt / ($opera_count / 100), 2);
            $opera_values = $opera_values.','.round($ws_result->cnt / ($opera_count / 100), 2);
        }//Opera
    }//Only show stats for Explorer, Firefox, Chrome, Safari and Opera
    }//NOT unknown browser and version
}

$explorer_values = implode(',', explode(",", substr($explorer_values.','.$ws_result->cnt, 1, -2)));
$firefox_values = implode(',', explode(",", substr($firefox_values.','.$ws_result->cnt, 1, -2)));
$chrome_values = implode(',', explode(",", substr($chrome_values.','.$ws_result->cnt, 1, -2)));
$safari_values = implode(',', explode(",", substr($safari_values.','.$ws_result->cnt, 1, -2)));
$opera_values = implode(',', explode(",", substr($opera_values.','.$ws_result->cnt, 1, -2)));
?>

<?php 
//BROWSER PIE CHART
if($explorer_percent !== 0 || $firefox_percent !== 0 || $chrome_percent !== 0 || $safari_percent !== 0 || $opera_percent !== 0 || $other_percent !== 0){//Check is there any browser entry yet? ?>
<div class="visual-box">
    <img src="http://1.chart.apis.google.com/chart?chs=<?php echo $chart_size_width.'x'.$chart_size_height;?>&cht=p3&chco=015083|FC5B07|FED710|3BADD9|E21515|A9A9A9&chd=t:<?php echo $explorer_percent.','.$firefox_percent.','.$chrome_percent.','.$safari_percent.','.$opera_percent.','.$other_percent;?>&chdl=Explorer+%28<?php echo $explorer_percent;?>%25%29|Firefox+%28<?php echo $firefox_percent;?>%25%29|Chrome+%28<?php echo $chrome_percent;?>%25%29|Safari+%28<?php echo $safari_percent;?>%25%29|Opera+%28<?php echo $opera_percent;?>%25%29|Other+%28<?php echo $other_percent;?>%25%29&chtt=Browser+Usage" />
</div>
<?php }else{
    $no_entries++; //count empty sections
}?>


<?php 
//OPERATIONG SYSTEM (OS) PIE CHART
if($windows_count !== 0 || $linux_count !== 0 || $mac_count !== 0 || $other_os_count !== 0){ //Check is there any OS entry yet??>
<div class="visual-box">
    <img src="http://2.chart.apis.google.com/chart?chs=<?php echo $chart_size_width.'x'.$chart_size_height;?>&cht=p3&chco=3399CC|FC5B07|FED710|A9A9A9&chd=t:<?php echo $windows_count.','.$linux_count.','.$mac_count.','.$other_os_count;?>&chdl=Windows+%28<?php echo $windows_count;?>%25%29|Linux+%28<?php echo $linux_count;?>%25%29|Mac+%28<?php echo $mac_count;?>%25%29|Other+%28<?php echo $other_os_count;?>%25%29&chtt=OS+Usage" />
</div>
<?php }else{
    $no_entries++;//count empty sections
}?>


<?php if($explorer_string_percent !== 0){ //Is there any entry for Internet Explorer yet? ?>
<div class="visual-box">
    <div class="ie-icon"></div>
    <img src="http://chart.apis.google.com/chart?chxl=0:<?php echo $explorer_string;?>|2:<?php echo $explorer_string_percent;?>&chxr=2,-20,100&chxt=x,y,x&chbh=r,0,1&chs=<?php echo $chart_size_width.'x'.$chart_size_height;?>&cht=bvg&chco=015083&chds=0,100&chd=t1:<?php echo $explorer_values;?>|-1&chtt=Explorer+usage+by+version (%)&chts=676767,14.4" />
</div>
<?php }else{
    $no_entries++;//count empty sections
}?>

<?php 
if($firefox_string_percent !== 0){ //Is there any entry for Firefox yet? ?>
<div class="visual-box">
    <div class="firefox-icon"></div>
    <img src="http://chart.apis.google.com/chart?chxl=0:<?php echo $firefox_string;?>|2:<?php echo $firefox_string_percent;?>&chxr=2,-20,100&chxt=x,y,x&chbh=r,0,1&chs=<?php echo $chart_size_width.'x'.$chart_size_height;?>&cht=bvg&chco=FC5B07&chds=0,100&chd=t1:<?php echo $firefox_values;?>|-1&chtt=Firefox+usage+by+version (%)&chts=676767,14.4" />
</div>
<?php }else{
    $no_entries++;//count empty sections
}?>


<?php if($chrome_string_percent !== 0){ //Is there any entry for Chrome yet? ?>
<div class="visual-box">
    <div class="chrome-icon"></div>
    <img src="http://chart.apis.google.com/chart?chxl=0:<?php echo $chrome_string;?>|2:<?php echo $chrome_string_percent;?>&chxr=2,-20,100&chxt=x,y,x&chbh=r,0,1&chs=<?php echo $chart_size_width.'x'.$chart_size_height;?>&cht=bvg&chco=FED710&chds=0,100&chd=t1:<?php echo $chrome_values;?>|-1&chtt=Chrome+usage+by+version (%)&chts=676767,14.4" />
</div>
<?php }else{
    $no_entries++;//count empty sections
}?>

<?php if($safari_string_percent !== 0){ //Is there any entry for Safari yet? ?>
<div class="visual-box">
    <div class="safari-icon"></div>
    <img src="http://chart.apis.google.com/chart?chxl=0:<?php echo $safari_string;?>|2:<?php echo $safari_string_percent;?>&chxr=2,-20,100&chxt=x,y,x&chbh=r,0,1&chs=<?php echo $chart_size_width.'x'.$chart_size_height;?>&cht=bvg&chco=3BADD9&chds=0,100&chd=t1:<?php echo $safari_values;?>|-1&chtt=Safari+usage+by+version (%)&chts=676767,14.4" />
</div>
<?php }else{
    $no_entries++;//count empty sections
}?>

<?php if($opera_string_percent !== 0){ //Is there any entry for Opera yet? ?>
<div class="visual-box">
    <div class="opera-icon"></div>
    <img src="http://chart.apis.google.com/chart?chxl=0:<?php echo $opera_string;?>|2:<?php echo $opera_string_percent;?>&chxr=2,-20,100&chxt=x,y,x&chbh=r,0,1&chs=<?php echo $chart_size_width.'x'.$chart_size_height;?>&cht=bvg&chco=E21515&chds=0,100&chd=t1:<?php echo $opera_values;?>|-1&chtt=Opera+usage+by+version (%)&chts=676767,14.4" />
</div>
<?php }else{
    $no_entries++;//count empty sections
}?>



<?php
if($no_entries == 7){//7 sections means no one section has been shown
    echo '<br />No entries yet';
}
?>