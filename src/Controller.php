<?php

namespace CodeLighter;

class Controller
{
    protected $data = [];

    function model($model)
    {
        $run = '\\Model\\' . $model;
        return new $run();
    }
    function data($data = [])
    {
        foreach ($data as $k => $v)
            $this->data[$k] = $v;
    }
    function view($arr = [])
    {
        $this->data['style'] = $this->data['style'] ?? [];
        $this->data['script'] = $this->data['script'] ?? [];
        $this->data['description'] = $arr['description'] ?? SITENAME;
        $this->data['keywords'] = $arr['keywords'] ?? SITENAME;
        $this->data['page'] = $arr['page'] ?? (debug_backtrace())[1]['function'];
        $this->data['view'] = strtolower($arr['view'] ?? substr(get_class($this), strrpos(get_class($this), '\\') + 1));

        $arr['title'] = SITENAME .  (!empty($this->data['title']) ? " - " . $this->data['title'] : '');

        return array_merge($this->data, $arr);
    }
    function json($data, $t = false)
    {
        header('Content-Type: application/json');
        if ($t) die(json_encode($data, JSON_PRETTY_PRINT));
        $output = ['message' => $data, "error" => false];
        if (is_array($data))
            $output = isset($data['success']) ? ['message' => $data["success"], "error" => true] : ['message' => $data[0], 'field' => $data[1], "error" => false];
        die(json_encode($output, JSON_PRETTY_PRINT));
    }
    function check($arr = [])
    {
        $r = (object) $this->_req;
        $r->_errors = [];
        foreach ($arr as $v => $s)
            if ((isset($this->_req[$v]) && !empty($this->_req[$v]) ? $this->_req[$v] : false) == false) {
                $r->_errors[] = $s;
                $r->_fields[] = $v;
            }
        return $r;
    }
    function setArr($z)
    {
        foreach ($z as $v)
            $this->_req[$v] = isset($_POST[$v]) ? $_POST[$v] : [];
    }
    function setFiles($z)
    {
        foreach ($z as $v)
            $this->_req[$v] = $_FILES[$v] ?? [];
    }
    function set($z)
    {
        foreach ($z as $v) {
            if (is_array($v)) {
                $key = current(array_keys($v));
                if (gettype($key) == "integer") {
                    if (is_array($v[1])) {
                        $val = post($v[0]);
                        foreach ($v[1] as $fu) {
                            $fu[1][] = $val;
                            $arr_vals = array_values(array_reverse($fu[1]));
                            $val = call_user_func_array($fu[0], $arr_vals);
                        }
                    } else {
                        if (isset($v[2])) {
                            $v[2][] = post($v[0]);
                            $arr_vals = array_values(array_reverse($v[2]));
                            $val = call_user_func_array($v[1], $arr_vals);
                        } else
                            $val = $v[1](post($v[0]));
                    }
                    $variable = $v[0];
                } else {
                    $variable = $key;
                    $v = $v[$key];
                    if (is_array($v)) {
                        if (is_array($v[1])) {
                            $val = $v[0];
                            foreach ($v[1] as $fu) {
                                $fu[1][] = $val;
                                $arr_vals = array_values(array_reverse($fu[1]));
                                $val = call_user_func_array($fu[0], $arr_vals);
                            }
                        } else {
                            if (isset($v[2])) {
                                $v[2][] = $v[0];
                                $arr_vals = array_values(array_reverse($v[2]));
                                $val = call_user_func_array($v[1], $arr_vals);
                            } else
                                $val = $v[1]($v[0]);
                        }
                    } else
                        $val = $v;
                }
            } else {
                $variable = $v;
                $val = post($v);
            }
            $variable = str_replace('-', '_', $variable);
            $this->_req[$variable] = $this->DB->escape($val);
        }
    }
}
