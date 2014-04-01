<?php

/* global */
$_controller = null;

/*
 * return site title
 */

function the_title($echo = true) {
    if ($echo) {
        echo isset(Yii::app()->params['title']) ? Yii::app()->params['title'] : yii::app()->name;
    } else {
        return isset(Yii::app()->params['title']) ? Yii::app()->params['title'] : yii::app()->name;
    }
}

/*
 * @params param key
 */

function get_params($key) {
    if (isset(Yii::app()->params[$key])) {
        return Yii::app()->params[$key];
    }
    return false;
}

/* ===================================== USER FUNCTION ============================================ */

/*
 * get user id
 */

function get_user_id($echo = true) {
    if ($echo) {
        echo Yii::app()->user->isGuest ? 'guest' : Yii::app()->user->id;
    } else {
        return Yii::app()->user->isGuest ? 'guest' : Yii::app()->user->id;
    }
}

/*
 * get user id
 */

function get_user_name($echo = true) {
    if ($echo) {
        echo Yii::app()->user->isGuest ? 'guest' : Yii::app()->user->name;
    } else {
        return Yii::app()->user->isGuest ? 'guest' : Yii::app()->user->name;
    }
}

/*
 * get customer attr
 */

function getUserAttr($attr, $echo = true) {
    if ($echo) {
        if (isset(Yii::app()->user->$attr))
            echo Yii::app()->user->$attr;
        else
            echo "";
    } else {
        if (isset(Yii::app()->user->$attr))
            return Yii::app()->user->$attr;
        else
            return '';
    }
}

/* ===================================== USER FUNCTION END ============================================ */



/* ===================================== SCRIPT FUNCTION ============================================== */
/*
 * Register script
 * @param filepath
 * @param position 
 */

function register_script($script_id = "", $script = "", $position = CClientScript::POS_READY) {

    if ($script_id == "") {
        $script_id = "srript_" . rand(0, 200000);
    }


    Yii::app()->getClientScript()->registerScript($script_id, $script, $position);
}

/*
 * Register script file
 * @param filepath
 * @param position 
 */

function register_script_file($fileName, $position = CClientScript::POS_HEAD) {
    Yii::app()->getClientScript()->registerScriptFile($fileName, $position);
}

/* ===================================== SCRIPT FUNCTION END ========================================== */


/* ===================================== DATE TIME FUNCTION ========================================== */

/*
 * format date
 */

function getFormatDate($date = '', $format = 'yyyy-MM-dd', $echo = true) {
    if ($date == '') {
        $timestamp = strtotime(date('Y-m-d H:i:s'));
    } else {
        $timestamp = strtotime($date);
    }

    $dateFormatter = yii::app()->dateFormatter;


    if ($echo) {
        echo $dateFormatter->format($format, $timestamp);
        ;
    } else
        return $dateFormatter->format($format, $timestamp);
}

/* ===================================== DATE TIME FUNCTION END========================================== */

/* return baseUrl */

function get_baseurl() {
    return Yii::app()->getBaseUrl(true);
}

/* return site url */

function store_url($echo = true) {
    if ($echo) {
        echo Yii::app()->params['url'];
    } else {
        return Yii::app()->params['url'];
    }
}

/* return site meta */

function site_descript($echo = true) {
    if ($echo) {
        //Yii::log(Yii::app()->params);
        echo Yii::app()->params['options']['meta_description'];
    } else {
        return Yii::app()->params['options']['meta_description'];
    }
}

/* ===================================== THEME FUNCTION========================================== */


/* return store theme path 
 * @echo boolean print option
 */

function get_theme_path($echo = true) {
    if ($echo)
        echo Yii::app()->theme->basePath;
    else
        return Yii::app()->theme->basePath;
}

/* return store theme url 
 * @echo boolean print option
 */

function get_theme_url($echo = true) {
    if ($echo)
        echo Yii::app()->theme->baseUrl;
    else
        return Yii::app()->theme->baseUrl;
}

/*
 * get admin url
 */

function get_admin_url($p = '/') {
    $module_id = yii::app()->getModule('manager')->id;
    return '/' . $module_id . $p;
}

/*
 * get asset  path
 */

function get_asset_path($echo = true) {
    if ($echo) {
        echo $GLOBALS['_controller']->publish_path;
    } else {
        return $GLOBALS['_controller']->publish_path;
    }
}

/* return site style sheet */

function get_style() {
    echo '<link rel="stylesheet" type="text/css" href="' . Yii::app()->theme->baseUrl . '/style.css' . '"/>';
}

/*
 * template install function
 * @param template name
 * return boolean
 */

function install_template($param) {
    if (!is_install_template($param)) {
        
    }
}

/*
 * template is instaled
 * return boolean
 */

function is_install_template($param) {
    $criteria = new CDbCriteria();
    $criteria->select = '*';
    $criteria->condition = 'name=:name';
    $criteria->params = array(':name' => $param);
    $model = Template::model()->find($criteria);
    if ($model) {
        return $model->getAttributes();
    } else {
        return false;
    }
}

/*
 * get active template
 * return array 
 */

function get_theme() {
    $criteria = new CDbCriteria();
    $criteria->select = '*';
    $criteria->condition = 'status=1';
    $model = Template::model()->find($criteria);
    if ($model) {
        return $model->getAttributes();
    } else {
        return array('name' => 'default');
    }
}

/*
 * file header data
 */

function get_file_data($file, $default_headers, $context = '') {


    // We don't need to write to the file, so just open for reading.
    $fp = fopen($file, 'r');

    // Pull only the first 8kiB of the file in.
    $file_data = fread($fp, 8192);

    // PHP will close file handle, but we are good citizens.
    fclose($fp);

    foreach ($default_headers as $field => $regex) {
        preg_match('/' . preg_quote($regex, '/') . ':(.*)$/mi', $file_data, ${$field});
        if (!empty(${$field}))
            ${$field} = trim(preg_replace("/\s*(?:\*\/|\?>).*/", '', ${$field}[1]));
        else
            ${$field} = '';
    }

    $file_data = compact(array_keys($default_headers));

    return $file_data;
}

/* ===================================== THEME FUNCTION END========================================== */



/*
 * Translate
 */

function __($category = 'store', $message = '', $params = array()) {
    return Yii::t($category, $message, $params);
}

/*
 * get base path
 */

function getRoot($echo = true) {
    if ($echo) {
        echo Yii::app()->basePath;
    } else {
        return Yii::app()->basePath;
    }
}

/*
 * get base path
 */

function createUrl($url, $echo = true) {
    if ($echo) {
        echo Yii::app()->createUrl($url);
    } else {
        return Yii::app()->createUrl($url);
    }
}

/*
 * get request parametre
 */

function query_param($key, $defaultValue = null, $echo = true) {
    if ($echo) {
        echo Yii::app()->request->getQuery($key, $defaultValue);
    } else {
        return Yii::app()->request->getQuery($key, $defaultValue);
    }
}

/*
 * db command
 */

function qr_result($sql = '', $param = array()) {
    if (count($param) > 0) {


        $command = yii::app()->db->createCommand($sql, $param);
        foreach ($param as $k => $v) {
            $command->bindParam($k, $v);
        }
        return $command->queryall();
    } else {
        return yii::app()->db->createCommand($sql)->queryall();
    }
}

/*
 * mail send
 */

function send_mail($name, $subject = '', $email, $body = '', $extra_header = array()) {
    $adminEmail = Yii::app()->params['adminEmail'];
    $name = '=?UTF-8?B?' . base64_encode($name) . '?=';
    $subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
    $headers = "From: $name <{$email}>\r\n" .
            "Reply-To: {$email}\r\n" .
            "MIME-Version: 1.0\r\n" .
            "Content-type: text/html; charset=UTF-8";

    mail(Yii::app()->params['adminEmail'], $subject, $body, $headers);

    $headers = "From: {$adminEmail}\r\n" .
            "Reply-To: {$adminEmail}\r\n" .
            "MIME-Version: 1.0\r\n" .
            "Content-type: text/html; charset=UTF-8";

    mail($email, $subject, $body, $headers);
    //Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
}

function gunfark($a, $b, $r = 0) {
    $ay = array();
    $a = explode("-", $a);
    $b = explode("-", $b);

    print_r($a);

    return false;
    $g = 0;
    $at = mktime(0, 0, 0, $a[1], $a[2], $a[0]);
    $bt = mktime(0, 0, 0, $b[1], $b[2], $b[0]);

    while (1) {
        $at = mktime(0, 0, 0, $a[1], $a[2] + $g, $a[0]);
        if ($at < $bt) {
            $g++;
            if ($r)
                $ay[date("Y-m", $at)] ++;
        } else
            break;
    }

    if ($r)
        return $ay;
    else
        return $g;
}

/*
  Sql değer kontrol

 * Veri tabanına eklenecek verileri kontrol eder
 *
 */

function CheckData($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
    //$theValue = htmlentities($theValue

    $theValue = mysql_real_escape_string($theValue);

    switch ($theType) {
        case "text":
        case 'string':
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
            break;
        case "long":
            $theValue = ($theValue != "") ? intval($theValue) : "NULL";
            break;
        case "integer":
            $theValue = ($theValue != "") ? intval($theValue) : "NULL";
            break;
        case "float":
            $theValue = ($theValue != "") ? floatval($theValue) : "NULL";
            break;
        case "double":
            $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
            break;
        case "timestamp":
            if (!preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})$/", trim($theValue), $match)) {
                $theValue = "NULL";
            }

            if (count($match) == 7 &&
                    ($match[4] < 24 && $match[4] >= 0) &&
                    ($match[5] < 60 && $match[5] >= 0) &&
                    ($match[6] < 60 && $match[6] >= 0) && checkdate($match[2], $match[3], $match[1])) {

                $theValue = "'" . $theValue . "'";
            };
            break;
            break;
        case "datetime":
            if (!preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2})$/", trim($theValue), $match)) {
                $theValue = "NULL";
            }

            if (count($match) == 6 &&
                    ($match[4] < 24 && $match[4] >= 0) &&
                    ($match[5] < 60 && $match[5] >= 0) && checkdate($match[2], $match[3], $match[1])) {

                $theValue = "'" . $theValue . "'";
            };
            break;
        case "date":
            $date = explode("-", $theValue);
            if (count($date) == 2 && checkdate($date[1], $date[2], $date[0])) {
                $theValue = "'" . $theValue . "'";
            } else {
                $theValue = "NULL";
            }
            break;
        case "numeric":
            $theValue = (is_numeric($theValue) != "") ? "'" . $theValue . "'" : "NULL";
            break;

        case "defined":
            $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
            break;
    }
    return $theValue;
}

?>