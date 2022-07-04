<?php
function checkRemoteFile($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($ch);
    curl_close($ch);
    if ($result !== FALSE) {
        return true;
    } else {
        return false;
    }
}
function downloadIMG($img, $target_dir, $img_name = NULL, $AllowedFiles = ['jpg', 'png', 'jpeg', 'gif'])
{
    $img_name = $img_name ?? md5(rand());
    if (checkRemoteFile($img)) {
        $ext = explode('.', $img);
        $ext = end($ext);
        if (in_array($ext, $AllowedFiles)) {
            if (getimagesize($img) != false) {
                $image = imgName($img_name, $ext, $target_dir);
                file_put_contents($target_dir . $image, fopen($img, 'r'));
                return $image;
            }
        }
    }
    return false;
}
function seo_desc($str)
{
    $str = strip_tags($str);
    $str = htmlspecialchars_decode($str);
    $str = strip_tags($str);
    $str = str_replace('\n', ' ', $str);
    $str = str_replace('\r', '', $str);
    return mb_substr($str, 0, 155);
}
function imgName($name, $ext, $path = false)
{
    $img = format_uri($name . ' ' . rand()) . '.' . $ext;
    if ($path == false)
        return $img;
    else {
        $i = 1;
        while (file_exists($path . $img))
            $img = format_uri($name . ' ' . rand() . $i++) . '.' . $ext;
        return $img;
    }
}
function format_uri($string, $separator = '-')
{
    $unwanted_array = array(
        'Š' => 'S', 'š' => 's', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
        'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U',
        'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c',
        'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
        'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y', 'č' => 'c', 'ć' => 'c', 'đ' => 'd'
    );
    $string = strtr(strtolower($string), $unwanted_array);
    $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
    $special_cases = array('&' => 'and', "'" => '');
    $string = mb_strtolower(trim($string), 'UTF-8');
    $string = str_replace(array_keys($special_cases), array_values($special_cases), $string);
    $string = preg_replace($accents_regex, '$1', htmlentities($string, ENT_QUOTES, 'UTF-8'));
    $string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
    $string = preg_replace("/[$separator]+/u", "$separator", $string);
    return $string;
}
function full_url()
{
    return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}
function length($str, $c)
{
    return strlen($str) >= $c ? $str : '';
}
function extension($d)
{
    $ex = explode(".", $d);
    return end($ex);
}
function isEmail($q)
{
    return filter_var($q, FILTER_VALIDATE_EMAIL) ? $q : false;
}
function url()
{
    return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' . "://" . $_SERVER['SERVER_NAME'] . "/" : 'https' . "://" . $_SERVER['SERVER_NAME'] . "/";
}
function strip($q)
{
    return htmlspecialchars($q);
}
function int($q)
{
    $q = preg_replace('/[^0-9.]/', '', $q);
    return (empty($q)) ? 0 : $q;
}
function ints($q)
{
    $q = preg_replace('/[^0-9]/', '', $q);
    return (empty($q)) ? 0 : (int)$q;
}
function digs($q)
{
    $q = preg_replace('/[^0-9]/', '', $q);
    return (empty($q)) ? 0 : $q;
}
function md50($q)
{
    return strlen($q) > 0 ? substr(strtonum(md5($q)), 6, 6) : '';
}
function md6($q)
{
    return strlen($q) > 0 ? substr(clean(base64_encode(md5($q))), 6, 6) : '';
}
function md9($q)
{
    return strlen($q) > 0 ? substr(strtonum(md5($q)), 0, 9) : '';
}
function strtonum($data)
{
    $new_string = "";
    $alphabet =  range("a", "z");
    $string_arr = str_split(clean($data));
    foreach ($string_arr as $str) {
        $new_string .= is_numeric($str) ? $str : array_search($str, $alphabet);
    }
    return $new_string;
}
function clean($q)
{
    return strtolower(preg_replace('/[^\w]/', '', $q));
}
function ip()
{
    return $_SERVER['REMOTE_ADDR'];
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];
    if (filter_var($client, FILTER_VALIDATE_IP)) {
        $ip = $client;
    } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
        $ip = $forward;
    } else {
        $ip = $remote;
    }
    return $ip;
}
function get($data, $value, $type = 'string')
{
    if ($type == 'string') {
        return isset($data[$value]) ? strip($data[$value]) : '';
    } elseif ($type == 'int') {
        return isset($data[$value]) ? int($data[$value]) : '';
    }
}
function post($v)
{
    return isset($_POST[$v]) ? trim(strip($_POST[$v])) : '';
}
function data($data, $value)
{
    return isset($data["post_data"][$value]) ? strip($data["post_data"][$value]) : '';
}
function isCurrent($data, $value)
{
    $page = get($data, 'page');
    return $page == $value ? 'active' : '';
}
function format_date($date)
{
    return date_format(date_create($date), "H:i\h d.m.Y.");
}
function format_time($h, $m)
{
    $h = (int)($h);
    $m = (int)($m);
    $d = substr(($h < 10 ? "0" . $h : $h) . ($m < 10 ? "0" . $m : $m), 0, 4);
    return $d < 2400 ? $d : "0000";
}
function writeFile($p, $t, $c = "")
{
    $f = fopen(pathFile($p), $t) or die('Unable to open file1!');
    fwrite($f, $c);
    fclose($f);
}
function readFiles($p, $t = 'r')
{
    $f = fopen($p, $t) or die("Unable to open file!");
    $d = fread($f, filesize($p));
    fclose($f);
    return $d;
}
function dec2($n)
{
    return number_format($n, 2, '.', ',');
}
function dc2($n)
{
    return number_format($n, 2, '.', '');
}
function pathFile($p)
{
    if (defined("CREATE_FILE")) return false;
    $p = str_replace("\\", "/", $p);
    $d = explode('/', $p);
    unset($d[count($d) - 1]);
    createPath(implode('/', $d));
    $f = fopen($p, "a") or die("Unable to open file! -> " . $p);
    fclose($f);
    return $p;
}
function createPath($path)
{
    $path = str_replace("\\", "/", $path);
    if (is_dir($path)) return true;
    echo "\n";
    $prev_path = substr($path, 0, strrpos($path, '/', -2) + 1);
    $return = createPath($prev_path);
    return ($return && is_writable($prev_path)) ? mkdir($path) : false;
}
function time_elapsed_string($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;
    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v)
        if ($diff->$k)
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        else
            unset($string[$k]);
    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
function content($part, $data)
{
    if ($part == ".php") return false;
    if ($part == ".js") return false;
    if ($part == ".css") return false;
    // if(strlen($part) < 5) return "";
    return getFile('/public/views/' . $part, $data);
}
function section($data){
    return content($data['view'].'/'.$data['page'].'.php', $data);
}
function url_strip($x)
{
    return substr($x, 0, 2) == "//" || substr($x, 0, 7) == "http://" || substr($x, 0, 8) == "https://";
}
function uncache($f)
{
    $FILE_NAMES = explode('/', $f);
    $FILE_name = array_pop($FILE_NAMES);
    $FILE_NAMES = implode('/', $FILE_NAMES);

    $FILE = DIR . $FILE_NAMES."/".md5($FILE_name.".min.js").'.CodeLighter.js';
    if (is_file($FILE) && is_readable($FILE) && filesize($FILE) != 0)
        return $FILE_NAMES."/".md5($FILE_name.".min.js").'.CodeLighter.js'. '?' . md6(filemtime($FILE));

    $FILE = DIR . $FILE_NAMES."/".md5($FILE_name.".min.css").'.CodeLighter.css';
    if (is_file($FILE) && is_readable($FILE) && filesize($FILE) != 0)
        return $FILE_NAMES."/".md5($FILE_name.".min.css").'.CodeLighter.css'.'?' . md6(filemtime($FILE));

    $FILE = DIR . $f;
    if (is_file($FILE) && is_readable($FILE) && filesize($FILE) != 0)
        return $f . '?' . md6(filemtime($FILE));

    return false;
}
function getFile($f, $data = [])
{
    // if (!file_exists(DIR . $f)) return false;
    if (!file_exists(DIR . $f)) pathFile(DIR . $f);
    if ($f == ".php") return false;
    if ($f == ".js") return false;
    if ($f == ".css") return false;
    //if(strlen($f) < 5) return false;
    if (extension($f) == "php")
        require_once(DIR . $f);
    else
        return uncache($f);
}

function redirect($w)
{
    header('Location: ' . $w);
    die;
}
function random($l = 6)
{
    $rand = strtoupper(hash('sha256', time() . md5(microtime(true)) . rand()));
    return substr($rand, 0, $l);
}
function fatal_handler()
{
    $error = error_get_last();
    if ($error === NULL) return false;
    die(json_encode($error));
}
register_shutdown_function("fatal_handler");

function sanitize_output($buffer)
{
    $search = array(
        '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
        '/[^\S ]+\</s',     // strip whitespaces before tags, except space
        '/(\s)+/s',         // shorten multiple whitespace sequences
        '/<!--(.|\s)*?-->/' // Remove HTML comments
    );
    $replace = array(
        '>',
        '<',
        '\\1',
        ''
    );
    $buffer = preg_replace($search, $replace, $buffer);
    $buffer = str_replace("> <", "><", $buffer);
    $buffer = str_replace("  ", " ", $buffer);
    $buffer = str_replace(".png", ".png?v", $buffer);
    $buffer = str_replace(".jpg", ".jpg?v", $buffer);
    $buffer = str_replace(".jpeg", ".jpeg?v", $buffer);
    $buffer = str_replace(".svg", ".svg?v", $buffer);
    $buffer = str_replace(PHP_EOL, "", $buffer);
    return $buffer;
}
function Generate2FA($u, $new_code_seconds = 30, $i = 0)
{
    return substr(strtonum(md5($u . (((int)(time() / $new_code_seconds)) - $i))), 6, 6);
}
function Confirm2FA($u, $c, $valid_minutes = 15, $new_code_seconds = 30)
{
    $times = $valid_minutes / $new_code_seconds * 60;
    for ($i = 0; $i < $times; $i++) if ($c == Generate2FA($u, $new_code_seconds, $i)) return 1;
    return 0;
}


function shortMessage($msg, $characters)
{
    if (strlen($msg) < $characters) return $msg;
    return substr($msg, 0, $characters) . "...";
}
