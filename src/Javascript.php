<?php

namespace CodeLighter;

class Javascript
{
    static $ss = [];
    static function add($n, $h)
    {
        self::$ss[$n] = ['href' => $h, 'ext' => is_array($h) ? url_strip($h[0] ?? NULL) : url_strip($h)];
    }
    static function page($d)
    {
        if (!$d['script']) return false;
        foreach ($d['script'] as $s)
            $ss[] = self::$ss[$s];
        foreach ($ss as $s)
            if (is_array($s['href']))
                foreach ($s['href'] as $b)
                    $e[] = $s['ext'] ? $b :  uncache($b);
            else
                $e[] = $s['ext'] ? $s['href'] :  uncache($s['href']);
        $e[] = getFile('/public/scripts/' . $d['view'] . '.js');
        $e[] = getFile('/public/scripts/' . $d['view'] . '/' . $d['page'] . '.js');
        // $e = array_unique($e);
        // echo PHP_EOL;
        foreach ($e as $s) {
            $s = substr($s, 0, 7) == '/public' ? substr($s, 7) : $s;
            if ($s !== false) echo '<script defer src="' . $s . '"></script>' . PHP_EOL;
        }
    }
}
