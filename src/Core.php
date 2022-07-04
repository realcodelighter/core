<?php

namespace CodeLighter;

class Core
{
    static function init($data = [])
    {
        $url = isset($_GET['CodeLighter']) ? explode('/', filter_var(rtrim($_GET['CodeLighter'] ?? [], '/'), FILTER_SANITIZE_URL)) : [];
        foreach ($data['css'] as $key => $val)
            Stylesheet::add($key, $val);

        foreach ($data['js'] as $key => $val)
            Javascript::add($key, $val);

        $Controller = 'Home';
        $Method = 'index';
        $Folder = 'Controllers';
        if (isset($url[0]) && !empty($url[0])) {
            $Controller = ucwords($url[0]);
            unset($url[0]);
            if ($Controller == 'Api') {
                $Folder = "Response";
                $Controller = ucwords($url[1]);
                unset($url[1]);
            }
            if (count($url)) {
                $Method = current($url);
                array_shift($url);
            }
        }
        $Controller = '\\' . $Folder . '\\' . $Controller;
        if (!class_exists($Controller)) {
            header('Content-Type: application/json');
            die(json_encode('Unknown 404 error.'));
        }
        $Controller =  new $Controller;
        if (!method_exists($Controller, $Method)) {
            header('Content-Type: application/json');
            die(json_encode('Unknown 404 error.'));
        }
        $params = $url ? array_values($url) : [];

        $init = call_user_func_array([$Controller, $Method], $params);
?>
        <!DOCTYPE html>
        <html>

        <head>
            <title><?= $init['title'] ?></title>
            <meta charset="utf-8">
            <meta name="description" content="<?= $init['description'] ?>">
            <meta name="keywords" content="<?= $init['keywords'] ?>">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <?php \CodeLighter\Stylesheet::page($init); ?>
            <?php \CodeLighter\Javascript::page($init); ?>
        </head>
        <?php content($init['view'] . '.php', $init); ?>

        </html>
<?php
    }
}
