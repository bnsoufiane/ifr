<?php
$title_array = explode(' ', $current_activity->title);
$title1 = "";
$title2 = "";

$n = count($title_array);
$m = ($n%2) ?($n / 2)-1 : ($n / 2);
$m = ($n==1)?1 :$m;
for ($i = 0; $i < $n; $i++) {
	if ($i < $m) {
		$title1.= ($title1 == "") ? $title_array[$i] : " " . $title_array[$i];
	} else {
		$title2.= ($title2 == "") ? $title_array[$i] : " " . $title_array[$i];
	}
}

if($title1 != "SubActivity"){
?>
    <div class="headTitle"><?php echo $title1; ?></div>
    <div class="headSubTitle"><?php echo $title2; ?></div>
<?php
}
?>
