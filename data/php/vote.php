<?php
header('content-type: application/json; charset=utf-8');
header("access-control-allow-origin: *");

$vote = "";
if ($_GET["vote"] == "up")
    $vote .= "likes = likes+1";
else if ($_GET["vote"] == "down")
        $vote .= "dislikes = dislikes+1";

if ($vote!="") {
    if (is_numeric($_GET["id"])){
        $src = "../..".$_GET['url']."/config.php";
        if (file_exists($src)) {

            require_once($src);

            $conn = new mysqli($config->mysql->host, $config->mysql->user, $config->mysql->pass, $config->mysql->name);
            // Check connection
            if ($conn->connect_error) {
                $result['error'] = "Connection failed: " . $conn->connect_error;
            }

            $token = md5($_GET["id"]."-".get_client_ip());

            $sql = "INSERT INTO votes (id, date) VALUES ('".$token."', '".time()."')";
            if ($conn->query($sql) === TRUE) {
                $sql = "UPDATE content SET ".$vote." WHERE id=".$_GET["id"];
                if ($conn->query($sql) === TRUE) {
                    $result['success'] = "Vote registered";
                } else $result['error'] = "Error:" . $conn->error;
            } else $result['error'] = "Error:" . $conn->error;

            $conn->close();
        } else $result['error'] = "Please set the [ulr] variable.";
    } else $result['error'] = "Please set the [id] variable: numeric.";
} else $result['error'] = "Please set the [vote] variable: up, down.";

if (!empty($_GET['callback']))
	echo $_GET['callback'] . '(' .json_encode($result) . ')';
else
	echo json_encode($result);

function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
?>
