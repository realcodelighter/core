<?php

namespace CodeLighter;

class Stylesheet
{
    static $ss = [];
    static function add($n, $h)
    {
        self::$ss[$n] = ['href' => $h, 'ext' => is_array($h) ? url_strip($h[0] ?? NULL) : url_strip($h)];
    }
    static function page($d)
    {
        if (!$d['style']) return false;
        foreach ($d['style'] as $s)
            $ss[] = self::$ss[$s];
        foreach ($ss as $s)
            if (is_array($s['href']))
                foreach ($s['href'] as $b)
                    $e[] = $s['ext'] ? $b :  uncache($b);
            else
                $e[] = $s['ext'] ? $s['href'] :  uncache($s['href']);
        $e[] = getFile('/public/styles/' . $d['view'] . '.css');
        $e[] = getFile('/public/styles/' . $d['view'] . '/' . $d['page'] . '.css');
        // $e = array_unique($e);
        // echo PHP_EOL;
        foreach ($e as $s) {
            $s = substr($s, 0, 7) == '/public' ? substr($s, 7) : $s;
            if ($s !== false) echo '<link rel="stylesheet" href="' . $s . '">' . PHP_EOL;
        }
    }
}
