<?php

/*
 * return site title
 */

function the_title($echo = true) {
    if ($echo) {
        echo Yii::app()->params['title'];
    } else {
        return Yii::app()->params['title'];
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

/*
 * get admin url
 */

function get_admin_url($p = '/') {
    $module_id = yii::app()->getModule('manager')->id;
    return '/' . $module_id . $p;
}

/*
 * get user id
 */

function get_user_id($echo = true) {

    if ($echo) {
        echo Yii::app()->user->id;
    } else {
        return Yii::app()->user->id;
    }
}

/*
 * translate
 */

function __t($text = '', $echo = true) {

    if ($echo) {
        echo Yii::t('app', $text);
    } else
        return Yii::t('app', $text);
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

function send_mail($name, $subject = 'Yeni Siparis', $email, $body = '', $extra_header = array()) {
    $adminEmail = Yii::app()->params['adminEmail'];
    $name = '=?UTF-8?B?' . base64_encode($name) . '?=';
    $subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
    $headers = "From: $name <{$email}>\r\n" .
            "Reply-To: {$email}\r\n" .
            "MIME-Version: 1.0\r\n" .
            "Content-type: text/html; charset=UTF-8";

    mail(Yii::app()->params['adminEmail'], $subject, $body, $headers);

    $headers = "From: Zamane Elektronik <{$adminEmail}>\r\n" .
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