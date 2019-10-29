<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

require_once("PasswordHash.php");

class Common 
{

    public function nohtml($message) 
    {
        $message = trim($message);
        $message = strip_tags($message);
        $message = htmlspecialchars($message, ENT_QUOTES);
        return $message;
    }

	public function encrypt($password) 
    {
        $phpass = new PasswordHash(12, false);
        $hash = $phpass->HashPassword($password);
    	return $hash;
    }

    public function get_user_role($user) 
    {
        if(isset($user->user_role_name)) {
            return $user->user_role_name;
        } else {
            return lang("ctn_46");
        }
    }

    public function randomPassword() 
    {
    	$letters = array(
            "a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q",
            "r","s","t","u","v","w","x","y","z"
        );
    	$pass = "";
    	for($i=0;$i<10;$i++) {
    		shuffle($letters);
    		$letter = $letters[0];
    		if(rand(1,2) == 1) {
	    		$pass .= $letter;
    		} else {
	    		$pass .= strtoupper($letter);
    		}
    		if(rand(1,3)==1) {
    			$pass .= rand(1,9);
    		}
    	}
    	return $pass;
    }

    public function checkAccess($level, $required) 
    {
        $CI =& get_instance();
        if($level < $required) {
            $CI->template->error(
                "You do not have the required access to use this page. 
                You must be a ". $this->getAccessLevel($required)
                . "to use this page."
            );
        }
    }

    public function send_email($subject, $body, $emailt, $headers=array(), $debug=0) 
    {
        $CI =& get_instance();
        $CI->load->library('email');

        $CI->email->from($CI->settings->info->site_email, $CI->settings->info->site_name);
        $CI->email->to($emailt);

        $CI->email->subject($subject);
        $CI->email->message($body);

        
        foreach($headers as $key=>$value) {
            $CI->email->set_header($key, $value);
        }

        if($debug) {
            $CI->email->send(false);
            return $CI->email->print_debugger(array('headers','subject','body'));
        } else {
            $CI->email->send();
        }
        
    }

    public function check_mime_type($file) 
    {
        return true;
    }

    public function replace_keywords($array, $message) 
    {
        foreach($array as $k=>$v) {
            $message = str_replace($k, $v, $message);
        }
        return $message;
    }

    public function convert_time($timestamp) 
    {
        $time = $timestamp - time();
        if($time <=0) {
            $days = 0;
            $hours = 0;
            $mins = 0;
            $secs = 0;
        } else {
            $days = intval($time / (3600 * 24));
            $hours = intval( ($time - ($days * (3600*24))) / 3600);
            $mins = intval( ($time - ($days * (3600*24)) - ($hours * 3600) ) 
                    / 60);
            $secs = intval( ($time - ($days * (3600*24)) - ($hours * 3600) 
                    - ($mins * 60)) );
        }
        return array(
            "days" => $days, 
            "hours" => $hours, 
            "mins" => $mins, 
            "secs" => $secs
        );
    }

    public function get_time_string($time, $simple = 0) 
    {
        if(isset($time['days']) && 
            ($time['days'] > 1 || $time['days'] == 0)) {
            $days = lang("ctn_294");
        } else {
            $days = lang("ctn_295");
        }
        if(isset($time['hours']) && 
            ($time['hours'] > 1 || $time['hours'] == 0)) {
            $hours = lang("ctn_296");
        } else {
            $hours = lang("ctn_297");
        }
        if(isset($time['mins']) && 
            ($time['mins'] > 1 || $time['mins'] == 0)) {
            $mins = lang("ctn_298");
        } else {
            $mins = lang("ctn_299");
        }
        if(isset($time['secs']) && 
            ($time['secs'] > 1 || $time['secs'] == 0)) {
            $secs = lang("ctn_300");
        } else {
            $secs = lang("ctn_301");
        }

        if($simple) {
            $days = "D";
            $hours = "H";
            $mins = "M";
            $secs = "S";   
        }

        // Create string
        $timeleft = "";
        if(isset($time['days'])) {
            $timeleft = $time['days'] . " " . $days;
        }

        if(isset($time['hours'])) {
            if(!empty($timeleft)) {
                if(!isset($time['mins'])) {
                    $timeleft .= " ".lang("ctn_302")." " . $time['hours'] . " " 
                    . $hours;
                } else {
                    $timeleft .= ", " . $time['hours'] . " " . $hours;
                }
            } else {
                $timeleft .= $time['hours'] . " " . $hours;
            }
        }

        if(isset($time['mins'])) {
            if(!empty($timeleft)) {
                if(!isset($time['secs'])) {
                    $timeleft .= " ".lang("ctn_302")." " . $time['mins'] . " " 
                    . $mins;
                } else {
                    $timeleft .= ", " . $time['mins'] . " " . $mins;
                }
            } else {
                $timeleft .= $time['mins'] . " " . $mins;
            }
        }

        if(isset($time['secs'])) {
            if(!empty($timeleft)) {
                $timeleft .= " ".lang("ctn_302")." " . $time['secs'] . " " 
                . $secs;
            } else {
                $timeleft .= $time['secs'] . " " . $secs;
            }
        }

        return $timeleft;
    }

    public function convert_simple_time($time) 
    {
        $o_time = $time;
        $time = time() - $time;
        if($time <=0) {
            $days = 0;
            $hours = 0;
            $mins = 0;
            $secs = 0;
        } else {
            $days = intval($time / (3600 * 24));
            $hours = intval( ($time - ($days * (3600*24))) / 3600);
            $mins = intval( ($time - ($days * (3600*24)) - ($hours * 3600) ) 
                    / 60);
            $secs = intval( ($time - ($days * (3600*24)) - ($hours * 3600) 
                    - ($mins * 60)) );
        }
        return array(
            "days" => $days, 
            "hours" => $hours, 
            "mins" => $mins, 
            "secs" => $secs,
            "timestamp" => $o_time
        );
    }

    public function convert_simple_time_fixed($time) 
    {
        $o_time = $time;
        if($time <=0) {
            $days = 0;
            $hours = 0;
            $mins = 0;
            $secs = 0;
        } else {
            $days = intval($time / (3600 * 24));
            $hours = intval( ($time - ($days * (3600*24))) / 3600);
            $mins = intval( ($time - ($days * (3600*24)) - ($hours * 3600) ) 
                    / 60);
            $secs = intval( ($time - ($days * (3600*24)) - ($hours * 3600) 
                    - ($mins * 60)) );
        }
        return array(
            "days" => $days, 
            "hours" => $hours, 
            "mins" => $mins, 
            "secs" => $secs,
            "timestamp" => $o_time
        );
    }

    public function get_time_string_simple($time) 
    {
        $CI =& get_instance();
        if(isset($time['days']) && 
            ($time['days'] > 1 || $time['days'] == 0)) {
            $days = lang("ctn_294");
        } else {
            $days = lang("ctn_295");
        }
        if(isset($time['hours']) && 
            ($time['hours'] > 1 || $time['hours'] == 0)) {
            $hours = lang("ctn_296");
        } else {
            $hours = lang("ctn_297");
        }
        if(isset($time['mins']) && 
            ($time['mins'] > 1 || $time['mins'] == 0)) {
            $mins = lang("ctn_298");
        } else {
            $mins = lang("ctn_299");
        }
        if(isset($time['secs']) && 
            ($time['secs'] > 1 || $time['secs'] == 0)) {
            $secs = lang("ctn_300");
        } else {
            $secs = lang("ctn_301");
        }

        if($time['days'] > 7) {
            return date($CI->settings->info->date_format, $time['timestamp']);
        } else {
            if($time['days'] > 0) {
                return $time['days'] . " " . $days . " ago";
            } elseif($time['hours'] > 0) {
                return $time['hours'] . " " . $hours . " ago";
            } elseif($time['mins'] > 0) {
                return $time['mins'] . " " . $mins . " ago";
            } elseif($time['secs'] > 0) {
                return $time['secs'] . " " . $secs . " ago";
            } else {
                return "0 " . lang("ctn_300") . " ago";
            }
        }
    }

    public function has_permissions($required, $user) 
    {
        if(!isset($user->info->user_role_id)) return 0;
        foreach($required as $permission) {
            if(isset($user->info->{$permission}) && $user->info->{$permission}) {
                return 1;
            }
        }
        return 0;
    }

    public function get_user_display($data) 
    {
        if(empty($data['username'])) return "";
        if(isset($data['online_timestamp']) > 0) {
            if($data['online_timestamp'] > time() - (60*15)) {
                $class = "online-dot-user";
                $title = lang("ctn_334");
            } else {
                $class = "offline-dot-user";
                $title = lang("ctn_335");
            }
        } else {
            $class = "online-dot-user";
        }

        $name = "";
        if(isset($data['first_name']) && isset($data['last_name'])) {
            $name = $data['first_name'] . " " . $data['last_name'];
        }
        $CI =& get_instance();
        $html = '<div class="user-box-avatar">
                <div class="'.$class.'" data-toggle="tooltip" data-placement="bottom" title="'.$title.'"></div>
                <a href="'.site_url("profile/" . $data['username']).'"><img src="'. base_url() . $CI->settings->info->upload_path_relative .'/'. $data['avatar'] .'" title="'.$data['username'].'" data-toggle="tooltip" data-placement="right" /></a>
                </div>';
        if($name) {
            $html .='<div class="user-box-name"><p>'.$name.'</p><p class="user-box-username">@'.$data['username'].'</p></div>';
        }
        return $html;
    }

    public function getDatesFromRange($start, $end, $format = 'Y-m-d') 
    {
        $array = array();
        $interval = new DateInterval('P1D');

        $realEnd = new DateTime($end);
        $realEnd->add($interval);

        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

        foreach($period as $date) { 
            $array[] = $date->format($format); 
        }

        return $array;
    }

}

?>
