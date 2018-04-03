<?php
namespace Jwc\Controller;
use Common\Controller\ApiController;

class CourseController extends ApiController {

	public function html_old() {
		$html = $_POST['html'];
		$isTeacher = $_POST['type'] == 'js';

		if (strpos($html, '用户名和密码不能为空') !== FALSE) {
            $rel['type'] = false;
            $rel['msg'] = "SNO_OR_PWD_WRONG";
            $this->apiError('-1', '学号和密码不能为空', $rel);
        } elseif (strpos($html, '用户名或密码错误') !== FALSE) {
            $rel['type'] = false;
            $rel['msg'] = "SNO_OR_PWD_WRONG";
            $this->apiError('200', '学号或密码错误', $rel);
        } elseif (strpos($html, '确定注销用户吗') !== FALSE) {
            import('@.Util.simple_html_dom');
            $dom = new \simple_html_dom();
            $dom->load($html);
            $table = $dom->find('#xkyjf', 0);
            if (!$table) $this -> apiError(-1, 'invalid request');
            $rel = array();
            for ($i = 0; $i < 7; $i++)
            for ($j = 0; $j < 5; $j++)
                $rel[$i][$j] = '';
            $week_dic = array('一' => 0, '二' => 1, '三' => 2, '四' => 3, '五' => 4, '六' => 5, '日' => 6);
            $time_dic = array('1' => 0, '3' => 1, '5' => 2, '7' => 3, '9' => 4);
            
            // 教务处课程
            $isTeacher = ($table->find('tr', 0)->children(0) -> innertext == '学年学期');
            
            foreach ($table->find('tr') as $key => $val) {
                if ($val -> children(0) -> innertext != "课程名称" && $val -> children(0) -> innertext != "学年学期") {
                    $sub['name'] = rtrim($val -> children($isTeacher ? 1 : 0) -> innertext, '&nbsp;');
                    $sub['teacher'] = rtrim($val -> children($isTeacher ? 6 : 1) -> innertext, '&nbsp;');
                    $sub['time'] = rtrim($val -> children($isTeacher ? 8 : 2) -> innertext, '&nbsp;');
                    $sub['place'] = rtrim($val -> children($isTeacher ? 9 : 3) -> innertext, '&nbsp;');
                    $sub['score'] = rtrim($val -> children($isTeacher ? 7 : 5) -> innertext, '&nbsp;');
                    $time = $sub['time'];

                    $sub['time'] = explode(';', $sub['time']);
                    $sub['place'] = explode(';', $sub['place']);

                    foreach ($sub['time'] as $key => $val) {
                        $match = array();
                        preg_match("/周(.*)第(.*)节{(.*)}/", $val, $match);
                        if (count($match) == 4) {
                            $class['id'] = '0';
                            $class['name'] = $sub['name'];
                            $class['teacher'] = $sub['teacher'];
                            $class['time'] = $match[3];
                            $class['place'] = $sub['place'][$key];
                            $class['score'] = $sub['score'];
                            $class['hash'] = md5('bks'.$class['name'].$class['teacher']);
                            $class['thash'] = md5(trim(str_replace('&nbsp;', '', $time)));
                            $match[2] = explode("-", $match[2]);
                            $rel[$week_dic[$match[1]]][$time_dic[$match[2][0]]][] = $class;
                        }
                    }
                }
            }
            //  $term_start = strtotime(C("term_start"));
            //  $rel['term_start'] = $term_start;
            
            // 自定义课程
            /*$sno = _T($_REQUEST['sno']);
            $course = M('course');
            $info = $course->where("`sno` = '{$sno}' AND `term`='201620172'")->get();
            foreach($info as $key => $value) {
                $day = $value['day'];
                $no = $value['no'];
                $value['hash'] = md5('zdy'.$value['id']);
                $value['thash'] = md5($value['time']);
                unset($value['day']); unset($value['no']); unset($value['sno']);
                $rel[$day][$no][] = $value;
            }*/
            
            // 愚人节活动
            /*if(!strpos($_SERVER['HTTP_USER_AGENT'], 'Android/2.4.5')) {
            $act = array('黑魔法防御课','初级出门妆','红包心理学','表情包发展与使用A','霸王餐实训','炼金术A','盒饭热力学','狗狗防身术','艾泽拉斯国家地理B','放风筝','睡觉','刷朋友圈','负重五公里越野','吃黄焖鸡','妮可妮可妮','折纸飞机','骑自行车','斗地主','发呆','坐火车去拉萨','喝奶茶');
            $place = array('霍格沃兹学院','寝室','QQ群','QQ群','风味食堂','地下室','工大路','南湖大草原','暴风城','南湖大草原','寝室','寝室','博学广场','工大路','音乃木坂学院','南湖大草原','广场西二路','寝室','寝室','汉口火车站','奶茶店');
            
            for ($i = 0; $i < 7; $i++)
                for ($j = 0; $j < 5; $j++)
                    if(empty($rel[$i][$j])) {
                        $rand = mt_rand(0, count($act) - 1);
                        $rel[$i][$j][] = array(
                            'id' => 0,
                            'name' => $act[$rand],
                            'teacher' => 'Token团队',
                            'time' => '第6-6周',
                            'place' => $place[$rand],
                            'score' => '4.1',
                            'hash' => md5('fool'.$act[$rand]),
                            'thash' => md5('2017')
                        );
                    }
            }*/
            
            $this->apiSuccess('OK', $rel);
        }
        $this->apiError(-1, 'UNKNOWN');
	}


    public function html() {
        $html = $_POST['html'];
        // $isTeacher = $_POST['type'] == 'js';

        if (strpos($html, '用户名和密码不能为空') !== FALSE) {
            $rel['type'] = false;
            $rel['msg'] = "SNO_OR_PWD_WRONG";
            $this->apiError('-1', '学号和密码不能为空', $rel);
        } elseif (strpos($html, '用户名或密码错误') !== FALSE) {
            $rel['type'] = false;
            $rel['msg'] = "SNO_OR_PWD_WRONG";
            $this->apiError('200', '学号或密码错误', $rel);
        } elseif (strpos($html, '登录时间：') !== FALSE) {
            import('@.Util.simple_html_dom');
            $dom = new \simple_html_dom();
            $dom->load($html);
            $table = $dom->find('#weekTable', 0);//根据id
    //        echo $tables->children(0)->children(1)->innertext;exit;
            // 课表数组初始化
            $rel = array();
            for ($i = 0; $i < 7; $i++)
                for ($j = 0; $j < 5; $j++)
                    $rel[$i][$j] = null;

            // $i 控制课程所处一天中的节数(0-4)
            $i = 0;
            // 教务处课程
            foreach ($table->find('tr') as $key => $val) {
                // 跳过表的周次 <thead>
                if ($i == 0) {
                    $i++;
                    continue;
                }
                // 获取第X大节的行
                if ($val->children(0)->innertext != ' ') {
                    // $k 控制课程的周次(0-6)
                    for ($k = 0; $k < 7; $k++) {
                        // 获取形如 "商务英语(第1-16单周,郭卫,新4-408)" "计算机组成原理D(第01-16单周,田小华,新2-402)操作系统D(第01-15双周,刘军,新2-402)"  的字符串
                        $sub_str[$k][$i - 1] = str_replace(array("\t", "&nbsp;", " ", "老师"), "", trim($val->children($k + 1)->innertext));
                        //$rel[$k][$i-1][] = $sub_str[$k][$i-1];
                        // 判断数据条数
                        $str_counts = substr_count($sub_str[$k][$i - 1], ")");
                        $temp_str = explode(")", $sub_str[$k][$i - 1]);

                        // 多条数据处理
                        for ($count_id = 0; $count_id < $str_counts; $count_id++) {
                            // 解析 sub_str 字符串获取里面的内容
                            $match = array();
                            preg_match("/(.*)\((.*),(.*),(.*)/", $temp_str[$count_id], $match);
                            $class['id'] = '0';
                            $class['name'] = $match[1];
                            $class['teacher'] = $match[3];
                            $class['time'] = $match[2];
                            $class['place'] = $match[4];
                            $class['score'] = '0';
                            $class['hash'] = md5('bks' . $class['name'] . $class['teacher']);
                            $class['thash'] = "";
                            $rel[$k][$i - 1][$count_id] = $class;
                        }
                    }
                    $i++;
                }
            }
            $this->apiSuccess('OK', $rel);
        }
        $this->apiError(-1, 'UNKNOWN');
    }


	public function json() {
		$json = $_POST['json'];

		$course = json_decode($json, true);
		$time_arr = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'); 
		if ($course['errcode'] == 0) {
			$rel = array();
	        foreach($course['data']['items'] as $index => $value) {
	            foreach($time_arr as $i => $week) {
	                if($value[$week]) {
	                    $singleCourseArr = explode('电话:', $value[$week]);
	                    foreach($singleCourseArr as $courseStr) {
	                        if(strpos($courseStr, '地点')) $rel[$i][$index][] = $this->dealGdCourse($courseStr);
	                    }
	                }
	                else $rel[$i][$index] = '';
	            }
	        }
	        $this->apiSuccess('OK', $rel);
	    }
	}

	private function dealGdCourse($value) {
        $value = str_replace('<br/><br/>','<br/>',$value);
        $arr = explode('<br/>', $value);
        $time = str_replace('周次:(', '第', str_replace(')', '周', $arr[3]));
        return array(
            'id' => '0',
            'name' => $arr[1],
            'college' => str_replace('开课院系:','',$arr[5]),
            'teacher' => strtok($arr[2], ' '),
            'time' =>  $time,
            'place' => str_replace('地点:','',$arr[4]),
            'score' =>  '0',
            'hash' => md5('yjs'.$arr[1].explode(' ', $arr[2], 0)),
            'thash' => md5($time)
        );
    }  

    
}