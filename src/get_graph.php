<div id="mynetwork"></div>
<script type="text/javascript">

<?php
require_once("config/external.php");
$username = $_SESSION["user"];


if (isset($_GET["user"]) && ($_GET["user"] != $username || array_key_exists($_GET["user"], $external_paths))){
    $overviewuser = $_GET["user"];
    $extern = True;
}else{
    $overviewuser = $username;
    $extern = False;
}

function hue2rgb($p, $q, $t) {
    if ($t < 0) $t += 1;
    if ($t > 1) $t -= 1;
    if ($t < 1/6) return $p + ($q - $p) * 6 * $t;
    if ($t < 1/2) return $q;
    if ($t < 2/3) return $p + ($q - $p) * (2/3 - $t) * 6;

    return $p;
}

function rgb2hex($rgb) {
    return str_pad(dechex($rgb * 255), 2, '0', STR_PAD_LEFT);
}

function hslToHex($hsl) {
	list($h, $s, $l) = $hsl;
	if ($s == 0) $s = 0.000001;
	$q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
	$p = 2 * $l - $q;
	$r = hue2rgb($p, $q, $h + 1/3);
	$g = hue2rgb($p, $q, $h);
	$b = hue2rgb($p, $q, $h - 1/3);
	return rgb2hex($r) . rgb2hex($g) . rgb2hex($b);
}

require_once("config/db_connect.php");
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
$public_filter = $extern?" AND `access`=1":"";
$sql = "SELECT * FROM zettel WHERE `user`='$overviewuser'$public_filter";
$result = $mysqli->query($sql);

echo "var nodes = new vis.DataSet([\n";
$nodes = array();

$date_start = 1619107295;
$max_date_span = time() - $date_start;

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $date = $row['date_creation'];
        $words = $row['words'];
        $size = intval(sqrt($words) * 2);
        $date_span = strtotime($date) - $date_start;
        $date_factor = $date_span / $max_date_span;
        #print_r($date_factor);
        $bordercolor = hslToHex(array($date_factor*2/3, 0.8, 0.3));
        $facecolor = hslToHex(array($date_factor*2/3, 0.8, 0.6));
        echo "{ id: '". $row["name"] ."', label: '". $row["title"] ."', shape: 'dot', size: '$size', color: {background: '#$facecolor', border: '#$bordercolor'}},\n";
        $nodes[] = $row["name"];
    }
}
echo "]);\n";

$sql = "SELECT * FROM connections WHERE `origin_user`='$overviewuser' AND `target_user`='$overviewuser';";
$result = $mysqli->query($sql);


echo "var edges = new vis.DataSet([\n";
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if (in_array($row['origin_name'], $nodes) && in_array($row['target_name'], $nodes)){
            echo "{ from: '". $row['origin_name'] ."', to: '". $row['target_name'] ."'},\n";
        }
    }
}
echo "]);\n";

?>
// create a network
var container = document.getElementById("mynetwork");
var data = {
    nodes: nodes,
    edges: edges,
};
var options = {
    layout: {
        improvedLayout: false
    },
    nodes: {
        font: {
            size: 14,
                face: 'Titillium Web',
            }
    }
};
var network = new vis.Network(container, data, options);
network.on("click", function (params) {
    var node = this.getNodeAt(params.pointer.DOM);
    if (node){
        var url = "view.php?link=" + node;
        window.open(url,'_blank');
    }
});
</script>

