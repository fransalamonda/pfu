<?php

namespace App\Controllers;

class Wbi extends BaseController
{
    public function __construct()
    {
        //$this->load->library('form_validation');
        //$session = session();
        $this->db = \Config\Database::connect();
        //$this->encrypter = \Config\Services::encrypter();
    }

    public function index()
    {
        try {

            $session = session();
            if (!$session->has('nik'))
                throw new \Exception("Session Expired", 2);

            date_default_timezone_set("Asia/Jakarta");
            $nik     = $_SESSION['nik'];
            $name = $_SESSION['name'];

            //            $this->js_path = "assets/js/home/home_WBI_24.js?v=" . date("His");
            $this->js_path = "public/assets/js/home/home_WBI_24.js?v=" . date("His");
            $sidebar_view = "sidebar";
            $this->js_init = "main.init();";

            $data_page = [
                "profile"       =>  $_SESSION['name'],
                'tag_title'     => 'WBI | Patrol Follow Up',
                "sidebar"       =>  $sidebar_view,
                "js_path"       =>  base_url($this->js_path),
                "js_initial"    => "main.init();",
            ];
            return view("pages/content_24", $data_page);
        } catch (\Exception $exc) {
            if ($exc->getCode() == 2)
                return redirect()->to('/');
            else
                $output = array(
                    'status'    =>  0,
                    "msg"       =>  $exc->getMessage(),
                );
            exit(json_encode($output));
        }
    }

    function ms_plant()
    {
        if ($this->request->isAJAX()) {
            try {
                $session = session();
                $validation =  \Config\Services::validation();

                if (!$session->has('nik'))
                    throw new \Exception("Session Expired", 2);
                session_write_close();
                $valid = $this->validate(['id' => ['rules' => 'required', 'label' => 'ID', 'errors' => ['required' => 'Rules.id.required',]]]);
                if (!$valid) {
                    $msg = ['errors' => ['errorID' => $validation->getError('id')]];
                }
                $id = DEV_LANG;
                $role_name = $_SESSION['role_display_name'];
                //$role_name = "IDCGBT";
                //$plant_dept = "IDCGMD";
                $token = TOKEN_API;
                $nik     = $_SESSION['nik'];
                $current_date_time = date("Y-m-d");

                /* 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 * CEK Role Plant - START
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 */
                $data_plant = "dev_lang=$id";
                $cache_plant = $current_date_time . "_msplant";
                if (!$respon_data = cache($cache_plant)) {
                    $url = 'https://report-id.online/api_patrol_prod/pfu/get_ms_plant';
                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_plant);
                    $authorization = "Authorization: Bearer " . $token;
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));
                    $response_api = curl_exec($curl);
                    $jsonData = json_decode($response_api, true);
                    $respon_dept = $jsonData['status'];
                    if ($respon_dept == false) {
                        $output = array(
                            'status'    =>  0,
                            "msg"       =>  $jsonData['message'],
                        );
                        exit(json_encode($output));
                    }
                    $respon_data = $jsonData['result'];
                    curl_close($curl);
                    cache()->save($cache_plant, $respon_data, 43200);
                }

                $str_plant = "";
                if ($role_name == 'IDCGALL') {
                    foreach ($respon_data as $d) {
                        if ($d['dept'] != 'IDCGPPE' && $d['dept'] != 'IDCGPPM' && $d['dept'] != 'IDCGWRM' && $d['dept'] != 'IDCGWRE') {
                            $str_plant .= "<option value='" . $d['dept'] . "'>" . $d['plant_name'] . "</option>";
                        }
                    }
                } else {
                    foreach ($respon_data as $d) {

                        $namaplan[$d['dept']] = $d['plant_name'];
                    }
                    $str_plant = "<option value='" . $role_name . "'>" . $namaplan[$role_name] . "</option>";
                }



                $response = array(
                    "status"    => 1,
                    "csrf_hash"     => csrf_hash(),
                    "str_plant"    => $str_plant,
                );
                exit(json_encode($response));
            } catch (\Exception $exc) {
                $json_data = array(
                    "status"    => 0,
                    //"csrf_hash" => $this->security->get_csrf_hash(),
                    "csrf_hash" => csrf_hash(),
                    "msg"       => $exc->getMessage(),
                );
                exit(json_encode($json_data));
            }
        } else die("Die!");
    }
    /*
     * List PFU Patrol Report 
     * author :  FSM
     * date : 14 Jul 2022
     */
    function list_data()
    {
        if ($this->request->isAJAX()) {
            try {
                $session = session();
                //$encrypter = \Config\Services::encrypter();
                $validation =  \Config\Services::validation();

                if (!$session->has('nik'))
                    throw new \Exception("Session Expired", 2);
                session_write_close();
                $valid = $this->validate(['id' => ['rules' => 'required', 'label' => 'ID', 'errors' => ['required' => 'Rules.id.required',]]]);
                if (!$valid) {
                    $msg = ['errors' => ['errorID' => $validation->getError('id')]];
                }
                $plant_dept = $this->request->getVar('id');
                $id = DEV_LANG;
                //$role_name = $_SESSION['role_display_name'];
                $token = TOKEN_API;
                $nik     = $_SESSION['nik'];

                $str3 = '';
                $nostr3 = 0;
                $basurl = base_url();
                $current_date_time = date("Y-m-d");

                /* 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 * CEK Dept CACHE DI SERVER REDIS - START
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 */
                $data_status = "dev_lang=$id";
                $cache_dept = $current_date_time . "_dept";
                if (!$respon_data_dep = cache($cache_dept)) {
                    $url = 'https://report-id.online/api_patrol_prod/pfu/get_department_pic';
                    $curl = curl_init($url);
                    $hasil0 = curl_setopt($curl, CURLOPT_POSTFIELDS, $data_status);
                    $authorization = "Authorization: Bearer " . $token;
                    $hasil2 = curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    $hasil1 = curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));
                    $response_api = curl_exec($curl);
                    $jsonData = json_decode($response_api, true);
                    $respon_dept = $jsonData['status'];
                    if ($respon_dept == false) {
                        $output = array(
                            'status'    =>  0,
                            "msg"       =>  $jsonData['message'],
                        );
                        exit(json_encode($output));
                    }
                    $respon_data_dep = $jsonData['result'];
                    curl_close($curl);
                    cache()->save($cache_dept, $respon_data_dep, 43200);
                }
                foreach ($respon_data_dep as $d) {
                    $action_dept[$d['id']] = $d['department_pic'];
                }
                /* 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 * CEK work status CACHE DI SERVER REDIS - START
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 */
                $cache_status = $current_date_time . "_case";
                if (!$respon_work = cache($cache_status)) {
                    $url = 'https://report-id.online/api_patrol_prod/pfu/get_work_status';
                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_status);
                    $authorization = "Authorization: Bearer " . $token;
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));
                    $response_api = curl_exec($curl);
                    $jsonData = json_decode($response_api, true);
                    $respon_status = $jsonData['status'];
                    if ($respon_status == false) {
                        $output = array(
                            'status'    =>  0,
                            "msg"       =>  $jsonData['message'],
                        );
                        exit(json_encode($output));
                    }
                    $respon_work = $jsonData['result'];
                    curl_close($curl);
                    cache()->save($cache_status, $respon_work, 30000);
                }
                foreach ($respon_work as $s) {
                    if ($s['active'] == 'Y') {
                        $action_status[$s['id']] = $s['work_status'];
                    }
                }
                $data = "dev_lang=$id&plant_dept=$plant_dept";
                /* 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 * CEK Patrol Report CACHE DI SERVER REDIS - START
                 * 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 */
                $ket_cache = $plant_dept . "-Patrol-Report";
                if (!$respon_data = cache($ket_cache)) {

                    $url = 'https://report-id.online/api_patrol_prod/pfu/get_patrol_report';
                    $curl = curl_init($url);
                    $hasil0 = curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    $authorization = "Authorization: Bearer " . $token;
                    $hasil2 = curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    $hasil1 = curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));

                    $response_api = curl_exec($curl);
                    $jsonData = json_decode($response_api, true);
                    $respon_status = $jsonData['status'];
                    if ($respon_status == false) {
                        $respon_data = '';
                    } else {
                        $respon_data = $jsonData['result'];
                    }
                    curl_close($curl);
                    cache()->save($ket_cache, $respon_data, 600); //5 menit
                }
                /* 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 * Tabel unassigned - START
                 * 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 */
                $str1 = '';
                $nostr1 = 0;
                if ($respon_data == '') {
                    $str1 = "<tr><td colspan='7' style='margin-left: auto;
                    margin-right: auto;'>";
                    $str1 .= "<div class='table-no-data'></Br></Br>";
                    $str1 .= "<img class='table-no-data-icon' 
                                src='" .   $basurl . "/public/assets/images/Vector_ceklist.png' height='18'>";
                    $str1 .= "<p class='table-no-data-bolt'><b>Well Done</b><p></Br>
                    <p class='table-no-data-tulisan'>There are no more cases that need to be assigned. </Br>
                    Let’s check In Progress to monitor current cases</p></Br>";
                    $str1 .= "<button type='button' class='table-no-data-btn btn-next-1' >Go to In Progress</button></div></td>";
                    $str1 .= "</tr>";
                } else {
                    foreach ($respon_data as $item) {
                        // if (!preg_match('/[.]/', $item['equipment_id'])) {
                        $encrypted_id = $item['equipment_id'];
                        $newDate = date("d/m/Y", strtotime($item['last_inspection_date']));
                        $tmped = "class='btnRes' data-id='" . $encrypted_id . "'";
                        $tmped .= " data-tbl='waiting'";
                        $tmped .= " data-con='" . $item['conditions'] . "'";
                        $tmped .= " data-tg='" . $newDate . "'";
                        $tmped .= " data-ind='" . $item['last_inspection_date'] . "'";
                        $tmped .= " data-ec='" . $item['equipment_id'] . "'";
                        $tmped .= " data-cp='" . $item['checkpoint'] . "'";
                        $tmped .= " data-cd='" . $item['checkpoint_detail'] . "'";
                        $tmped .= " data-cn='" . $item['checklist_notes'] . "'";
                        $tmped .= " data-pl='" . $item['plant'] . "'";
                        $tmped .= " data-pl_d='" . $plant_dept . "'";

                        $nostr1++;
                        $str1 .= "<tr class='font-tabel-tr' >";
                        if ($item['conditions'] == 'Need Action') {
                            $str1 .= "<td   title=''><div class='font-tabel-na'>" . $item['conditions'] . "</div></td>";
                        } elseif ($item['conditions'] == 'Monitoring') {
                            $str1 .= "<td   title=''><div class='font-tabel-mt'>" . $item['conditions'] . "</div></td>";
                        } elseif ($item['conditions'] == 'Abnormal') {
                            $str1 .= "<td   title=''><div class='font-tabel-an'>" . $item['conditions'] . "</div></td>";
                        }

                        $str1 .= "<td   title=''>" . $newDate . "</td>";
                        $str1 .= "<td   title=''>" . $item['equipment_id'] . "</td>";
                        $str1 .= "<td   title=''>" . $item['checkpoint'] . "</td>";
                        $str1 .= "<td   title=''>" . wordwrap($item['checkpoint_detail'], 30, "<br>\n") . "</td>";
                        $str1 .= "<td   title=''>" . wordwrap($item['checklist_notes'], 30, "<br>\n") . "</td>";
                        $str1 .= "<td  class=''><a href='javascript:void(0)' " . $tmped . " title='Open Case'><h7 class='font-tabel-td ' ><small></small>Open Case</h7></a></td>";
                        $str1 .= "</tr>";
                        //}
                    }
                }
                /* 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 * CEK Patrol Report Pending Action CACHE DI SERVER REDIS - START
                 * 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 */
                $str2 = '';
                $nostr2 = 0;
                $data2 = "dev_lang=$id";
                $ket_cache_pending = $plant_dept . "-Patrol-Report-pending";
                if (!$respon_data_pending = cache($ket_cache_pending)) {
                    $url = 'https://report-id.online/api_patrol_prod/pfu/get_data_pending';
                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    $authorization = "Authorization: Bearer " . $token;
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));
                    $response_api = curl_exec($curl);
                    $jsonData = json_decode($response_api, true);
                    $respon_status = $jsonData['status'];
                    if ($respon_status == false) {
                        $respon_data_pending = '';
                    } else {
                        $respon_data_pending = $jsonData['result'];
                    }
                    curl_close($curl);
                    cache()->save($ket_cache_pending, $respon_data_pending, 43200); //300= 5 menit
                }
                if (!$respon_data_pending == '') {
                    //} else {
                    foreach ($respon_data_pending as $pa) {
                        if ($pa['conditions'] != '') {
                            $encrypted_id = $pa['equipment_id'];
                            $r_date = date("d M Y h:i A", strtotime($pa['date_created']));

                            if ($pa['spare_part_arrived'] == "0000-00-00 00:00:00") {
                                $spa_date = '';
                            } else {
                                $spa_date = date("d M Y", strtotime($pa['spare_part_arrived']));
                            }
                            if ($pa['inspection_date'] == "0000-00-00 00:00:00")
                                $newDate = "00/00/0000";
                            else
                                $newDate = date("d/m/Y", strtotime($pa['inspection_date']));

                            $end_date_j = date("d M Y", strtotime($pa['end_date']));
                            $end_date_m = date("h:i A", strtotime($pa['end_date']));
                            $end_date_hapus = $pa['end_date'];
                            $tmppa = "class='btnRes' data-id='" . $encrypted_id . "'";
                            $tmppa .= " data-tbl='waiting'";
                            $tmppa .= " data-idpending='" . $pa['id'] . "'";
                            $tmppa .= " data-con='" . $pa['conditions'] . "'";
                            $tmppa .= " data-ind='" . $pa['inspection_date'] . "'";
                            $tmppa .= " data-tg='" . $newDate . "'";
                            $tmppa .= " data-ec='" . $pa['equipment_id'] . "'";
                            $tmppa .= " data-cp='" . $pa['checkpoint'] . "'";
                            $tmppa .= " data-cd='" . $pa['checkpoint_detail'] . "'";
                            $tmppa .= " data-cn='" . $pa['checklist_notes'] . "'";
                            $tmppa .= " data-picdep='" . $pa['department_pic_id'] . "'";
                            $tmppa .= " data-pl_d='" . $plant_dept . "'";
                            $tmppa .= " data-nmpl='" . $pa['plant'] . "'";
                            $tmppa .= " data-pro_d='" . $pa['problem_detail'] . "'";
                            $tmppa .= " data-maintenance_plan='" . $pa['maintenance_plan'] . "'";
                            $tmppa .= " data-date_created='" . $r_date . "'";
                            $tmppa .= " data-st='" . $pa['work_status_id'] . "'";
                            $tmppa .= " data-spd='" . $pa['spare_part_detail'] . "'";
                            $tmppa .= " data-spa='" . $spa_date . "'";
                            $tmppa .= " data-edj='" . $end_date_j . "'";
                            $tmppa .= " data-edm='" . $end_date_m . "'";
                            $tmppa .= " data-eml='" . $pa['staff_pic'] . "'";
                            $tmppa .= " data-enddatehapus='" . $end_date_hapus . "'";

                            $nostr2++;
                            $str2 .= "<tr class='font-tabel-tr' >";
                            if ($pa['conditions'] == 'need action' or $pa['conditions'] == 'Need Action') {
                                $str2 .= "<td   title=''><div class='font-tabel-na'>" . $pa['conditions'] . "</div></td>";
                            } elseif ($pa['conditions'] == 'monitoring' or $pa['conditions'] == 'Monitoring') {
                                $str2 .= "<td   title=''><div class='font-tabel-mt'>" . $pa['conditions'] . "</div></td>";
                            } elseif ($pa['conditions'] == 'abnormal' or $pa['conditions'] == 'Abnormal') {
                                $str2 .= "<td   title=''><div class='font-tabel-an'>" . $pa['conditions'] . "</div></td>";
                            } elseif ($pa['conditions'] == '') {
                                $str2 .= "<td   title=''><div class='font-tabel-an'>-</div></td>";
                            }

                            $str2 .= "<td   title=''>" . $newDate . "</td>";
                            $str2 .= "<td   title=''>" . $pa['equipment_id'] . "</td>";
                            $str2 .= "<td   title='Checkpoint'>" . $pa['checkpoint'] . "</td>";
                            $str2 .= "<td   title='Checkpoint Detail'>" . wordwrap($pa['checkpoint_detail'], 30, "<br>\n") . "</td>";
                            $str2 .= "<td   title='Work'>" . $action_status[$pa['work_status_id']] . "</td>";
                            $str2 .= "<td  class=''><a href='javascript:void(0)' " . $tmppa . " title='Open Case'><h7 class='font-tabel-td ' ><small></small>Open Case</h7></a></td>";
                            $str2 .= "</tr>";
                        }
                    }
                }
                if ($nostr2 == "0") {
                    $str2 = "<tr><td colspan='7' style='margin-left: auto;
                margin-right: auto;'>";
                    $str2 .= "<div class='table-no-data'></Br></Br>";
                    $str2 .= "<img class='table-no-data-icon' 
                            src='" .   $basurl . "/public/assets/images/Vector_ceklist.png' height='18'>";
                    $str2 .= "<p class='table-no-data-bolt'><b>Well Done</b><p></Br>
                <p class='table-no-data-tulisan'>There are no more cases that need to be assigned. </Br>
                Let’s check Need Review to monitor current cases</p></Br>";
                    $str2 .= "<button type='button' class='table-no-data-btn btn-next-2' >Go to Need Review</button></div></td>";
                    $str2 .= "</tr>";
                }

                $str3 = '';
                $nostr3 = 0;
                $ket_cache_inprogress = $plant_dept . "-Patrol-Report-inprogress";
                if (!$respon_data_inprogress = cache($ket_cache_inprogress)) {
                    $url = 'https://report-id.online/api_patrol_prod/pfu/get_data_inprogress';
                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    $authorization = "Authorization: Bearer " . $token;
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));
                    $response_api = curl_exec($curl);
                    $jsonData = json_decode($response_api, true);
                    $respon_status = $jsonData['status'];
                    if ($respon_status == false) {
                        $respon_data_inprogress = '';
                    } else {
                        $respon_data_inprogress = $jsonData['result'];
                    }
                    curl_close($curl);
                    cache()->save($ket_cache_inprogress, $respon_data_inprogress, 600); //5 menit
                }
                if ($respon_data_inprogress == '') {
                    $str3 = "<tr><td colspan='7' style='margin-left: auto;
                    margin-right: auto;'>";
                    $str3 .= "<div class='table-no-data'></Br></Br>";
                    $str3 .= "<img class='table-no-data-icon' 
                                src='" .   $basurl . "/public/assets/images/Vector_ceklist.png' height='18'></Br>";
                    $str3 .= "<p class='table-no-data-bolt'><b>Well Done</b><p></Br>
                    <p class='table-no-data-tulisan'>There are no more cases that need to review. </Br>";
                    $str3 .= "</div></td>";
                    $str3 .= "</tr>";
                } else {
                    foreach ($respon_data_inprogress as $ip) {
                        if ($ip['conditions'] != '') {
                            $r_date = date("d M Y h:i A", strtotime($ip['date_created']));
                            $spa_date = date("d M Y", strtotime($ip['spare_part_arrived']));
                            $encrypted_id = $ip['equipment_id'];
                            if ($ip['inspection_date'] == "0000-00-00 00:00:00")
                                $newDate = "00/00/0000";
                            else
                                $newDate = date("d/m/Y", strtotime($ip['inspection_date']));

                            $end_date_j = date("d M Y", strtotime($ip['end_date']));
                            $end_date_m = date("h:i A", strtotime($ip['end_date']));

                            $tmpip = "class='btnRes' data-id='" . $encrypted_id . "'";
                            $tmpip .= " data-idpending='" . $ip['id'] . "'";
                            $tmpip .= " data-idip='" . $ip['id'] . "'";
                            $tmpip .= " data-tbl='waiting'";
                            $tmpip .= " data-con='" . $ip['conditions'] . "'";
                            $tmpip .= " data-ind='" . $ip['inspection_date'] . "'";
                            $tmpip .= " data-tg='" . $newDate . "'";
                            $tmpip .= " data-ec='" . $ip['equipment_id'] . "'";
                            $tmpip .= " data-cp='" . $ip['checkpoint'] . "'";
                            $tmpip .= " data-cd='" . $ip['checkpoint_detail'] . "'";
                            $tmpip .= " data-picdep='" . $ip['department_pic_id'] . "'";
                            $tmpip .= " data-pro_d='" . $ip['problem_detail'] . "'";
                            $tmpip .= " data-maintenance_plan='" . $ip['maintenance_plan'] . "'";
                            //date_updated
                            $tmpip .= " data-date_created='" . $r_date . "'";
                            $tmpip .= " data-st='" . $ip['work_status_id'] . "'";
                            $tmpip .= " data-cn='" . $ip['checklist_notes'] . "'";
                            $tmpip .= " data-pl='" . $ip['plant'] . "'";
                            $tmpip .= " data-pl_d='" . $plant_dept . "'";
                            $tmpip .= " data-nmpl='" . $ip['plant'] . "'";
                            $tmpip .= " data-spd='" . $ip['spare_part_detail'] . "'";
                            $tmpip .= " data-spa='" . $spa_date . "'";
                            $tmpip .= " data-edj='" . $end_date_j . "'";
                            $tmpip .= " data-edm='" . $end_date_m . "'";
                            $tmpip .= " data-ema='" . $ip['staff_pic'] . "'";
                            $nostr3++;
                            $str3 .= "<tr class='font-tabel-tr' >";
                            if ($ip['conditions'] == 'need action' or $ip['conditions'] == 'Need Action') {
                                $str3 .= "<td   title=''><div class='font-tabel-na'>" . $ip['conditions'] . "</div></td>";
                            } elseif ($ip['conditions'] == 'monitoring' or $ip['conditions'] == 'Monitoring') {
                                $str3 .= "<td   title=''><div class='font-tabel-mt'>" . $ip['conditions'] . "</div></td>";
                            } elseif ($ip['conditions'] == 'abnormal' or $ip['conditions'] == 'Abnormal') {
                                $str3 .= "<td   title=''><div class='font-tabel-an'>" . $ip['conditions'] . "</div></td>";
                            } elseif ($ip['conditions'] == '') {
                                $str3 .= "<td   title=''><div class='font-tabel-an'>-</div></td>";
                            }
                            $str3 .= "<td   title=''>" . $newDate . "</td>";
                            $str3 .= "<td   title=''>" . $ip['equipment_id'] . "</td>";
                            $str3 .= "<td   title='Checkpoint'>" . $ip['checkpoint'] . "</td>";
                            $str3 .= "<td   title='Checkpoint Detail'>" . wordwrap($ip['checkpoint_detail'], 30, "<br>\n") . "</td>";
                            $str3 .= "<td   title='Work Status'>" . $action_status[$ip['work_status_id']] . "</td>";
                            $str3 .= "<td  class=''><a href='javascript:void(0)' " . $tmpip . " title='Open Case'><h7 class='font-tabel-td ' ><small></small>Open Case</h7></a></td>";
                            $str3 .= "</tr>";
                        }
                    }
                }

                $wfa_btn = "Unassigned (<a>" . $nostr1 . "</a>)";
                $inp_btn = "Pending Action (<a>" . $nostr2 . "</a>)";
                $nrw_btn = "In Progress (<a>" . $nostr3 . "</a>)";

                $response = array(
                    "status"    => 1,
                    "csrf_hash"     => csrf_hash(),
                    "wfa"           => $str1,
                    "ips"           => $str2,
                    "nrw"           => $str3,
                    "btn_wfa"    => $wfa_btn,
                    "btn_inp"    => $inp_btn,
                    "btn_nrw"    => $nrw_btn,
                );
                exit(json_encode($response));
            } catch (\Exception $exc) {
                $json_data = array(
                    "status"    => 0,
                    "csrf_hash" => csrf_hash(),
                    "msg"       => $exc->getMessage(),
                );
                exit(json_encode($json_data));
            }
        } else die("Die!");
    }

    /*
     * view action 
     * author :  FSM
     * date : 14 Jul 2022
     */
    function view_action()
    {
        if ($this->request->isAJAX()) {
            try {
                $session = session();
                $validation =  \Config\Services::validation();
                if (!$session->has('nik'))
                    throw new \Exception("Session Expired", 2);
                session_write_close();
                $valid = $this->validate(['id' => ['rules' => 'required', 'label' => 'ID', 'errors' => ['required' => 'Rules.id.required',]]]);
                if (!$valid) {
                    $msg = ['errors' => ['errorID' => $validation->getError('id')]];
                }
                $valid2 = $this->validate(['tbl' => ['rules' => 'required', 'label' => 'Tabel', 'errors' => ['required' => 'Rules.tbl.required',]]]);
                if (!$valid2) {
                    $msg = ['errors' => ['errorID' => $validation->getError('tbl')]];
                }
                $valid3 = $this->validate(['con' => ['rules' => 'required', 'label' => 'Condi', 'errors' => ['required' => 'Rules.con.required',]]]);
                if (!$valid3) {
                    $msg = ['errors' => ['errorID' => $validation->getError('tbl')]];
                }
                $valid4 = $this->validate(['pl_d' => ['rules' => 'required', 'label' => 'Kode Plant', 'errors' => ['required' => 'Rules.pl_d.required',]]]);
                if (!$valid4) {
                    $msg = ['errors' => ['errorID' => $validation->getError('pl_d')]];
                }
                $id = $this->request->getVar('id');
                $tbl = $this->request->getVar('tbl');
                $con = $this->request->getVar('con');
                $plant_dept = $this->request->getVar('pl_d');

                if ($con == 'Need Action') {
                    $conditions = "<div class='font-tabel-na'>" . $con . "</div>";
                } elseif ($con == 'Monitoring') {
                    $conditions = "<div class='font-tabel-mt'>" . $con . "</div>";
                } elseif ($con == 'Abnormal') {
                    $conditions = "<div class='font-tabel-an'>" . $con . "</div>";
                }

                $current_date_time = date("Y-m-d");
                $today = date("d F Y");
                $token      = TOKEN_API;
                $dev_lang   = DEV_LANG;
                $data = "dev_lang=$dev_lang";
                $button = '';
                /* 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 * CEK Dept CACHE DI SERVER REDIS - START
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 */
                $cache_dept = $current_date_time . "_dept";
                if (!$respon_data = cache($cache_dept)) {
                    $url = 'https://report-id.online/api_patrol_prod/pfu/get_department_pic';
                    $curl = curl_init($url);
                    $hasil0 = curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    $authorization = "Authorization: Bearer " . $token;
                    $hasil2 = curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    $hasil1 = curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));
                    $response_api = curl_exec($curl);
                    $jsonData = json_decode($response_api, true);
                    $respon_dept = $jsonData['status'];
                    if ($respon_dept == false) {
                        $output = array(
                            'status'    =>  0,
                            "msg"       =>  $jsonData['message'],
                        );
                        exit(json_encode($output));
                    }
                    $respon_data = $jsonData['result'];
                    curl_close($curl);
                    cache()->save($cache_dept, $respon_data, 43200);
                }
                $str_dept = "<option value=''> - Select Option - </option>";
                foreach ($respon_data as $d) {
                    if ($d['active'] == 'Y') {
                        $str_dept .= "<option required='' value='" . $d['id'] . "'>";
                        $str_dept .= $d['department_pic'] . "</option>";
                    }
                }

                /* 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 * CEK work status CACHE DI SERVER REDIS - START
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 */
                $cache_status = $current_date_time . "_case";
                if (!$respon_work = cache($cache_status)) {
                    $url = 'https://report-id.online/api_patrol_prod/pfu/get_work_status';
                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    $authorization = "Authorization: Bearer " . $token;
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));
                    $response_api = curl_exec($curl);
                    $jsonData = json_decode($response_api, true);
                    $respon_status = $jsonData['status'];
                    if ($respon_status == false) {
                        $output = array(
                            'status'    =>  0,
                            "msg"       =>  $jsonData['message'],
                        );
                        exit(json_encode($output));
                    }
                    $respon_work = $jsonData['result'];
                    curl_close($curl);
                    cache()->save($cache_status, $respon_work, 43200);
                }
                $str_status = "<option value=''> - Select Option - </option>";
                foreach ($respon_work as $s) {
                    if ($s['active'] == 'Y') {
                        $str_status .= "<option value='" . $s['id'] . "'>";
                        $str_status .= $s['work_status'] . "</option>";
                    }
                }

                /* 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 * CEK Action Log CACHE DI SERVER REDIS - START
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 */
                $cache_log = $current_date_time . "_log";
                if (!$respon_log = cache($cache_log)) {
                    $url = 'https://report-id.online/api_patrol_prod/pfu/get_ts_action_log';
                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    $authorization = "Authorization: Bearer " . $token;
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));
                    $response_api = curl_exec($curl);
                    $jsonData = json_decode($response_api, true);
                    $respon_status = $jsonData['status'];
                    if ($respon_status == false) {
                        $output = array(
                            'status'    =>  0,
                            "msg"       =>  $jsonData['message'],
                        );
                        exit(json_encode($output));
                    }
                    $respon_log = $jsonData['result'];
                    curl_close($curl);
                    cache()->save($cache_log, $respon_log, 30000);
                }
                $str_log = "<ul class='list-unstyled mb-0'>";
                foreach ($respon_log as $log) {
                    if ($log['equipment_id'] == $id && $log['plant_dept'] == $plant_dept) {
                        $str_log .= "<li class='card-kiri-tulisan-tiga'>Request Date</li>";
                        $str_log .= "<li>
                                                    <div class='card-kiri-tulisan-tiga'>'" . $log['date_created'] . "'</div>
                                                </li>";
                    }
                    $str_log .= "</ul >";
                }

                $response = array(
                    "status"        => 1,
                    "csrf_hash"     => csrf_hash(),
                    "str_dept"      => $str_dept,
                    "str_status"    => $str_status,
                    "today"         => $today,
                    "conditions" => $conditions,
                );
                exit(json_encode($response));
            } catch (\Exception $exc) {
                $json_data = array(
                    "status"    => 0,
                    //"csrf_hash" => $this->security->get_csrf_hash(),
                    "csrf_hash" => csrf_hash(),
                    "msg"       => $exc->getMessage(),
                );
                exit(json_encode($json_data));
            }
        } else die("Die!");
    }

    function add_act()
    {
        if ($this->request->isAJAX()) {
            try {
                $session = session();
                $email = \Config\Services::email();
                $cache = \Config\Services::cache();
                $email = \Config\Services::email();
                $validation =  \Config\Services::validation();
                if (!$session->has('nik'))
                    throw new \Exception("Session Expired", 2);
                session_write_close();
                $valid = $this->validate([
                    'idw' => [
                        'rules' => 'required',
                        'label' => 'ID',
                        'errors' => ['required' => 'Rules.idw.required',]
                    ],
                    'cdn' => [
                        'rules' => 'required',
                        'label' => 'Conditions',
                        'errors' => ['required' => 'Rules.cdn.required',]
                    ],
                    'ind' => [
                        'rules' => 'required',
                        'label' => 'Inspection Date',
                        'errors' => ['required' => 'Rules.ind.required',]
                    ],
                    'ckt' => [
                        'rules' => 'required',
                        'label' => 'Checkpoint',
                        'errors' => ['required' => 'Rules.ckt.required',]
                    ],
                    'ckt_d' => [
                        'rules' => 'required',
                        'label' => 'Checkpoint Detail',
                        'errors' => ['required' => 'Rules.ckt_d.required',]
                    ],
                    'icdepp' => [
                        'rules' => 'required',
                        'label' => 'ID',
                        'errors' => ['required' => 'Rules.icdepp.required',]
                    ],
                    'plant' => [
                        'rules' => 'required',
                        'label' => 'plant',
                        'errors' => ['required' => 'Rules.plant.required',]
                    ],
                    'plant_d' => [
                        'rules' => 'required',
                        'label' => 'plant_d',
                        'errors' => ['required' => 'Rules.plant_d.required',]
                    ],
                    'detpro' => [
                        'rules' => 'required',
                        'label' => 'detpro',
                        'errors' => ['required' => 'Rules.detpro.required',]
                    ],
                    'maint' => [
                        'rules' => 'required',
                        'label' => 'maint',
                        'errors' => ['required' => 'Rules.maint.required',]
                    ],
                    'inp_sd' => [
                        'rules' => 'required',
                        'label' => 'inp_sd',
                        'errors' => ['required' => 'Rules.inp_sd.required',]
                    ],
                    'sd_time' => [
                        'rules' => 'required',
                        'label' => 'sd_time',
                        'errors' => ['required' => 'Rules.sd_time.required',]
                    ],
                    'inp_ed' => [
                        'rules' => 'required',
                        'label' => 'inp_ed',
                        'errors' => ['required' => 'Rules.inp_ed.required',]
                    ],
                    'en_time' => [
                        'rules' => 'required',
                        'label' => 'en_time',
                        'errors' => ['required' => 'Rules.en_time.required',]
                    ],
                    'sttwork' => [
                        'rules' => 'required',
                        'label' => 'sttwork',
                        'errors' => ['required' => 'Rules.sttwork.required',]
                    ],
                    'emali' => [
                        'rules' => 'required',
                        'label' => 'emali',
                        'errors' => ['required' => 'Rules.emali.required',]
                    ]
                ]);
                if (!$valid) {
                    $msg = [
                        'errors' => [
                            'errorID' => $validation->getError('idw')
                        ]
                    ];
                }

                date_default_timezone_set("Asia/Jakarta");
                $current_date_time = date("Y-m-d");
                $token = TOKEN_API;
                $start = date("Y-m-d H:i:s");

                $conditions         = $this->request->getVar('cdn'); //conditions
                $inspection_date    = $this->request->getVar('ind'); //inspection_date
                $checkpoint         = $this->request->getVar('ckt');
                $checkpoint_d       = $this->request->getVar('ckt_d');
                $plant              = $this->request->getVar('plant'); //nama plant
                $plant_dept         = $this->request->getVar('plant_d'); //Id Plant
                $equipment_id       = $this->request->getVar('idw'); //equipment_id
                $icdepp             = $this->request->getVar('icdepp'); //Depertement PIC
                $detpro             = $this->request->getVar('detpro'); //problem_detail
                $maint              = $this->request->getVar('maint'); //maintenance_plan
                $start_date         = $start; //start_date
                $inp_ed             = $this->request->getVar('inp_sd'); // Target Completion Date * 
                $inp_ed1            = date("Y-m-d", strtotime($inp_ed));
                $en_time            = date("H:i:s");
                $en_time1           = date("H:i:s", strtotime($en_time));
                $end_date           = $inp_ed1 . " " . $en_time1; //end_date
                $sttwork            = $this->request->getVar('sttwork');
                $spare_part_d       = $this->request->getVar('spare_part_detail'); //Spare Part Detail
                $email_s              = $this->request->getVar('email');
                $d_sel              = $this->request->getVar('d_sel');
                $note_c             = $this->request->getVar('note_c');

                if ($d_sel == '') {
                    $d_sel1 = '0000-00-00';
                } else {
                    $d_sel1 = date("Y-m-d", strtotime($d_sel));
                }
                $spare_part_arrived = $d_sel1 . "00:00:00";

                $userid = $_SESSION['nik'];
                $devlang = DEV_LANG;
                $data2 = "plant=$plant&user=$userid&dev_lang=$devlang&plant_dept=$plant_dept&equipment_id=$equipment_id&checkpoint=$checkpoint&checkpoint_detail=$checkpoint_d&department_pic_id=$icdepp&problem_detail=$detpro&maintenance_plan=$maint&start_date=$start_date&end_date=$end_date&work_status_id=$sttwork&spare_part_detail=$spare_part_d&spare_part_arrived=$spare_part_arrived&conditions=$conditions&inspection_date=$inspection_date&staff_pic=$email_s&checklist_notes=$note_c";

                $url = 'https://report-id.online/api_patrol_prod/pfu/add_ts_action';
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data2);
                $authorization = "Authorization: Bearer " . $token;
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));
                $response_api = curl_exec($curl);
                $jsonData = json_decode($response_api, true);
                $respon_action = $jsonData['status'];
                if ($respon_action == false) {
                    $output = array(
                        'status'    =>  0,
                        "msg"       =>  $jsonData['message'],
                    );
                    exit(json_encode($output));
                }
                curl_close($curl);

                //send email
                if ($sttwork != '5') {
                    $dev_lang   = DEV_LANG;
                    $data = "dev_lang=$dev_lang";
                    $cache_status = $current_date_time . "_case";
                    if (!$respon_work = cache($cache_status)) {
                        $url = 'https://report-id.online/api_patrol_prod/pfu/get_work_status';
                        $curl = curl_init($url);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                        $authorization = "Authorization: Bearer " . $token;
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));
                        $response_api = curl_exec($curl);
                        $jsonData = json_decode($response_api, true);
                        $respon_status = $jsonData['status'];
                        if ($respon_status == false) {
                            $output = array(
                                'status'    =>  0,
                                "msg"       =>  $jsonData['message'],
                            );
                            exit(json_encode($output));
                        }
                        $respon_work = $jsonData['result'];
                        curl_close($curl);
                        cache()->save($cache_status, $respon_work, 43200);
                    }

                    foreach ($respon_work as $s) {
                        $str_status[$s['id']] = $s['work_status'];
                    }

                    /* 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 * CEK Dept CACHE DI SERVER REDIS - START
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 */
                    $cache_dept = $current_date_time . "_dept";
                    if (!$respon_data = cache($cache_dept)) {
                        $url = 'https://report-id.online/api_patrol_prod/pfu/get_department_pic';
                        $curl = curl_init($url);
                        $hasil0 = curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                        $authorization = "Authorization: Bearer " . $token;
                        $hasil2 = curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        $hasil1 = curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));
                        $response_api = curl_exec($curl);
                        $jsonData = json_decode($response_api, true);
                        $respon_dept = $jsonData['status'];
                        if ($respon_dept == false) {
                            $output = array(
                                'status'    =>  0,
                                "msg"       =>  $jsonData['message'],
                            );
                            exit(json_encode($output));
                        }
                        $respon_data = $jsonData['result'];
                        curl_close($curl);
                        cache()->save($cache_dept, $respon_data, 43200);
                    }

                    foreach ($respon_data as $d) {
                        $str_dept[$d['id']] = $d['department_pic'];
                    }


                    if ($conditions == 'Need Action') {
                        $condi = "<div class='font-tabel-na'>" . $conditions . "</div>";
                        $kondi = $conditions;
                    } elseif ($conditions == 'Monitoring') {
                        $condi = "<div class='font-tabel-mt'>" . $conditions . "</div>";
                        $kondi = $conditions;
                    } elseif ($conditions == 'Abnormal') {
                        $condi = "<div class='font-tabel-an'>" . $conditions . "</div>";
                        $kondi = $conditions;
                    } else {
                        $condi = $conditions;
                        $kondi = $conditions;
                    }
                    $Subject = ' New ' . $kondi . ' Patrol Follow Up - ' . $equipment_id . ' ' . $checkpoint . ' ' . $checkpoint_d . '';
                    $indate = date("d/m/Y", strtotime($inspection_date));
                    $template = "<h3>NEW ASSIGNED PATROL FOLLOW UP</h3>
                        <strong><p>General Information</p></strong>
                        <table border='1' style='border-collapse:collapse'>
                            <tr>
                                <td>Plant</td>
                                <td>BATAM PLANT</td>
                            </tr>
                            <tr>
                                <td>Condition</td>
                                <td>" . $condi . "</td>
                            </tr>
                            <tr>
                                <td>Inspection Date</td>
                                <td>" . $indate . "</td>
                            </tr>
                            <tr>
                                <td>Equipment Code</td>
                                <td>" . $equipment_id . "</td>
                            </tr>
                            <tr>
                                <td>Checkpoint</td>
                                <td>" . $checkpoint . "</td>
                            </tr>
                            <tr>
                                <td>Checkpoint Detail</td>
                                <td>" . $checkpoint_d . "</td>
                            </tr>
                            <tr>
                                <td>Checklist Notes</td>
                                <td>" . $note_c . "</td>
                            </tr>
                            <tr>
                                <td>Work Status</td>
                                <td>" . $str_status[$sttwork] . "</td>
                            </tr>
                        </table>

                        <p>
                        <strong>Action Detail</strong>
                        <br/>
                        Request Date : " . date("d/m/Y", strtotime($start_date)) . "
                        <br/>
                        Target Estimation Date : " . date("d/m/Y", strtotime($end_date)) . " 
                        <br/>
                        Department PIC : " . $str_dept[$icdepp] . "
                        <br/>
                        Email PIC : " . $email_s . "
                        <br/>
                        Problem Detail : " . $detpro . "
                        <br/>
                        Required Action : " . $maint . "
                        <br/>
                        <br/> 
                        <br/>
                        <i>This email was generated automatically, please do not reply</i>
                        </p>";
                    $email->setFrom('digital.cg@cemindo.com', 'WBI Patrol System');
                    $email->setTo($email_s);
                    $email->setSubject($Subject);
                    $email->setMessage($template);

                    if (!$email->send()) {
                        $r = $email->printDebugger(['headers']);
                    }
                }

                $ket_cache = $plant_dept . "-Patrol-Report";
                $cache->delete($ket_cache);
                $ket_cache_pending = $plant_dept . "-Patrol-Report-pending";
                $cache->delete($ket_cache_pending);
                $ket_cache_inprogress = $plant_dept . "-Patrol-Report-inprogress";
                $cache->delete($ket_cache_inprogress);
                $cache_log = $current_date_time . "_logg";
                $cache->delete($cache_log);
                $cachehistory = $plant_dept . "_history";
                $cache->delete($cachehistory);
                //sukses
                $output = array(
                    "status"    =>  1,
                    "csrf_hash" => csrf_hash(),
                    "msg"       =>  $jsonData['message']
                );
                exit(json_encode($output));
            } catch (\Exception $exc) {
                $output = array(
                    "status"    =>  $exc->getCode(),
                    "csrf_hash" => csrf_hash(),
                    "msg"       => $exc->getMessage(),
                );
                exit(json_encode($output));
            }
        } else {
            exit("Access Denied");
        }
    }

    function add_act_pending()
    {
        if ($this->request->isAJAX()) {
            try {
                $session = session();
                $email = \Config\Services::email();
                $cache = \Config\Services::cache();
                $validation =  \Config\Services::validation();
                if (!$session->has('nik'))
                    throw new \Exception("Session Expired", 2);
                session_write_close();
                $valid = $this->validate([
                    'plant' => [
                        'rules' => 'required',
                        'label' => 'plant',
                        'errors' => ['required' => 'Rules.plant.required',]
                    ],
                    'plant_d' => [
                        'rules' => 'required',
                        'label' => 'plant_d',
                        'errors' => ['required' => 'Rules.plant_d.required',]
                    ],
                    'idw' => [
                        'rules' => 'required',
                        'label' => 'ID',
                        'errors' => ['required' => 'Rules.idw.required',]
                    ],
                    'cdn' => [
                        'rules' => 'required',
                        'label' => 'Conditions',
                        'errors' => ['required' => 'Rules.cdn.required',]
                    ],
                    'ckt' => [
                        'rules' => 'required',
                        'label' => 'Checkpoint',
                        'errors' => ['required' => 'Rules.ckt.required',]
                    ],
                    'ckt_d' => [
                        'rules' => 'required',
                        'label' => 'Checkpoint Detail',
                        'errors' => ['required' => 'Rules.ckt_d.required',]
                    ],
                    'icdepp' => [
                        'rules' => 'required',
                        'label' => 'ID',
                        'errors' => ['required' => 'Rules.icdepp.required',]
                    ],
                    'detpro' => [
                        'rules' => 'required',
                        'label' => 'detpro',
                        'errors' => ['required' => 'Rules.detpro.required',]
                    ],
                    'maint' => [
                        'rules' => 'required',
                        'label' => 'maint',
                        'errors' => ['required' => 'Rules.maint.required',]
                    ],
                    'req_dt' => [
                        'rules' => 'required',
                        'label' => 'req_dt',
                        'errors' => ['required' => 'Rules.req_dt.required',]
                    ],
                    'inp_sd' => [
                        'rules' => 'required',
                        'label' => 'inp_sd',
                        'errors' => ['required' => 'Rules.inp_sd.required',]
                    ],
                    'inp_tm' => [
                        'rules' => 'required',
                        'label' => 'inp_tm',
                        'errors' => ['required' => 'Rules.inp_tm.required',]
                    ],
                    'sttwork' => [
                        'rules' => 'required',
                        'label' => 'sttwork',
                        'errors' => ['required' => 'Rules.sttwork.required',]
                    ],
                    'spare_part_detail' => [
                        'rules' => 'required',
                        'label' => 'spare_part_detail',
                        'errors' => ['required' => 'Rules.spare_part_detail.required',]
                    ],
                    'd_sel' => [
                        'rules' => 'required',
                        'label' => 'Inspection Date',
                        'errors' => ['required' => 'Rules.d_sel.required',]
                    ],
                    'cdn' => [
                        'rules' => 'required',
                        'label' => 'Cond',
                        'errors' => ['required' => 'Rules.cdn.required',]
                    ],
                    'ind' => [
                        'rules' => 'required',
                        'label' => 'ind',
                        'errors' => ['required' => 'Rules.ind.required',]
                    ],
                    'idpending' => [
                        'rules' => 'required',
                        'label' => 'idpending',
                        'errors' => ['required' => 'Rules.idpending.required',]
                    ]

                ]);
                // if (!$valid) {
                //     $msg = [
                //         'errors' => [
                //             'errorID' => $validation->getError('plant')
                //         ]
                //     ];
                // }

                date_default_timezone_set("Asia/Jakarta");
                $current_date_time = date("Y-m-d");
                $token = TOKEN_API;
                $start = date("Y-m-d H:i:s");
                $req_dt = date("Y-m-d H:i:s", strtotime($this->request->getVar('req_dt'))); //start_date
                $inp_sd = date("Y-m-d", strtotime($this->request->getVar('inp_sd'))); // Target Completion Date * 
                $inp_tm = date("H:i:s", strtotime($this->request->getVar('inp_tm')));

                $idpending          = $this->request->getVar('idpending'); //Id pending
                $plant              = $this->request->getVar('plant'); //nama plant
                $user               = $_SESSION['nik'];
                $dev_lang           = DEV_LANG;
                $plant_dept         = $this->request->getVar('plant_d'); //Id Plant
                $equipment_id       = $this->request->getVar('idw'); //equipment_id
                $checkpoint = $this->request->getVar('ckt');
                $checkpoint_d = $this->request->getVar('ckt_d');
                $icdepp             = $this->request->getVar('icdepp'); //Depertement PIC
                $detpro             = $this->request->getVar('detpro'); //problem_detail
                $maint              = $this->request->getVar('maint'); //maintenance_plan
                $start_date         = $req_dt; //start_date
                $end_date           = $inp_sd . " " . $inp_tm; //end_date
                $sttwork            = $this->request->getVar('sttwork');
                $spare_part_d       = $this->request->getVar('spare_part_detail'); //Spare Part Detail
                $d_sel              = $this->request->getVar('d_sel');
                if ($d_sel == '') {
                    $d_sel1 = '0000-00-00';
                } else {
                    $d_sel1 = date("Y-m-d", strtotime($d_sel));
                }
                $spare_part_arrived = $d_sel1 . " 00:00:00";
                $conditions         = $this->request->getVar('cdn'); //conditions
                $inspection_date    = $this->request->getVar('ind'); //inspection_date
                $email_s              = $this->request->getVar('email'); //email
                $data2 = "plant=$plant&user=$user&dev_lang=$dev_lang&plant_dept=$plant_dept&equipment_id=$equipment_id&checkpoint=$checkpoint&checkpoint_detail=$checkpoint_d&department_pic_id=$icdepp&problem_detail=$detpro&maintenance_plan=$maint&start_date=$start_date&end_date=$end_date&work_status_id=$sttwork&spare_part_detail=$spare_part_d&spare_part_arrived=$spare_part_arrived&id=$idpending";

                $url = 'https://report-id.online/api_patrol_prod/pfu/update_ts_action';
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data2);
                $authorization = "Authorization: Bearer " . $token;
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));
                $response_api = curl_exec($curl);
                $jsonData = json_decode($response_api, true);
                $respon_action = $jsonData['status'];
                if ($respon_action == false) {
                    $output = array(
                        'status'    =>  0,
                        "msg"       =>  $jsonData['message'],
                    );
                    exit(json_encode($output));
                }
                $ket_cache = $plant_dept . "-Patrol-Report";
                $cache->delete($ket_cache);
                $ket_cache_pending = $plant_dept . "-Patrol-Report-pending";
                $cache->delete($ket_cache_pending);
                $ket_cache_inprogress = $plant_dept . "-Patrol-Report-inprogress";
                $cache->delete($ket_cache_inprogress);
                $cache_log = $current_date_time . "_logg";
                $cache->delete($cache_log);
                $cachehistory = $plant_dept . "_history";
                $cache->delete($cachehistory);

                //sukses
                $output = array(
                    "status"    =>  1,
                    "csrf_hash" => csrf_hash(),
                    "msg"       =>  "New data has been saved"
                );
                exit(json_encode($output));
            } catch (\Exception $exc) {
                $output = array(
                    "status"    =>  $exc->getCode(),
                    "csrf_hash" => csrf_hash(),
                    "msg"       => $exc->getMessage(),
                );
                exit(json_encode($output));
            }
        } else {
            exit("Access Denied");
        }
    }

    function add_act_in_progress()
    {
        if ($this->request->isAJAX()) {
            try {
                $session = session();
                $email = \Config\Services::email();
                $cache = \Config\Services::cache();
                $validation =  \Config\Services::validation();
                if (!$session->has('nik'))
                    throw new \Exception("Session Expired", 2);
                session_write_close();
                $valid = $this->validate([
                    'idw' => [
                        'rules' => 'required',
                        'label' => 'ID',
                        'errors' => ['required' => 'Rules.idw.required',]
                    ],
                    'cdn' => [
                        'rules' => 'required',
                        'label' => 'Conditions',
                        'errors' => ['required' => 'Rules.cdn.required',]
                    ],
                    'ind' => [
                        'rules' => 'required',
                        'label' => 'Inspection Date',
                        'errors' => ['required' => 'Rules.ind.required',]
                    ],
                    'ckt' => [
                        'rules' => 'required',
                        'label' => 'Checkpoint',
                        'errors' => ['required' => 'Rules.ckt.required',]
                    ],
                    'ckt_d' => [
                        'rules' => 'required',
                        'label' => 'Checkpoint Detail',
                        'errors' => ['required' => 'Rules.ckt_d.required',]
                    ],
                    'icdepp' => [
                        'rules' => 'required',
                        'label' => 'ID',
                        'errors' => ['required' => 'Rules.icdepp.required',]
                    ],
                    'plant' => [
                        'rules' => 'required',
                        'label' => 'plant',
                        'errors' => ['required' => 'Rules.plant.required',]
                    ],
                    'plant_d' => [
                        'rules' => 'required',
                        'label' => 'plant_d',
                        'errors' => ['required' => 'Rules.plant_d.required',]
                    ],
                    'detpro' => [
                        'rules' => 'required',
                        'label' => 'detpro',
                        'errors' => ['required' => 'Rules.detpro.required',]
                    ],
                    'maint' => [
                        'rules' => 'required',
                        'label' => 'maint',
                        'errors' => ['required' => 'Rules.maint.required',]
                    ],
                    'inp_sd' => [
                        'rules' => 'required',
                        'label' => 'inp_sd',
                        'errors' => ['required' => 'Rules.inp_sd.required',]
                    ],
                    'sd_time' => [
                        'rules' => 'required',
                        'label' => 'sd_time',
                        'errors' => ['required' => 'Rules.sd_time.required',]
                    ],
                    'inp_ed' => [
                        'rules' => 'required',
                        'label' => 'inp_ed',
                        'errors' => ['required' => 'Rules.inp_ed.required',]
                    ],
                    'en_time' => [
                        'rules' => 'required',
                        'label' => 'en_time',
                        'errors' => ['required' => 'Rules.en_time.required',]
                    ],
                    'sttwork' => [
                        'rules' => 'required',
                        'label' => 'sttwork',
                        'errors' => ['required' => 'Rules.sttwork.required',]
                    ],
                    'idpending' => [
                        'rules' => 'required',
                        'label' => 'idpending',
                        'errors' => ['required' => 'Rules.idpending.required',]
                    ]
                ]);
                if (!$valid) {
                    $msg = [
                        'errors' => [
                            'errorID' => $validation->getError('idw')
                        ]
                    ];
                }

                date_default_timezone_set("Asia/Jakarta");
                $current_date_time = date("Y-m-d");
                $token = TOKEN_API;
                $start = date("Y-m-d H:i:s");
                $req_dt = date("Y-m-d H:i:s", strtotime($this->request->getVar('req_dt'))); //start_date
                $inp_sd = date("Y-m-d", strtotime($this->request->getVar('inp_sd'))); // Target Completion Date * 
                $inp_tm = date("H:i:s", strtotime($this->request->getVar('inp_tm')));

                $idpending          = $this->request->getVar('idpending'); //Id pending
                $plant              = $this->request->getVar('plant'); //nama plant
                $user               = $_SESSION['nik'];
                $dev_lang           = DEV_LANG;
                $plant_dept         = $this->request->getVar('plant_d'); //Id Plant
                $equipment_id       = $this->request->getVar('idw'); //equipment_id
                $checkpoint = $this->request->getVar('ckt');
                $checkpoint_d = $this->request->getVar('ckt_d');
                $icdepp             = $this->request->getVar('icdepp'); //Depertement PIC
                $detpro             = $this->request->getVar('detpro'); //problem_detail
                $maint              = $this->request->getVar('maint'); //maintenance_plan
                $start_date         = $req_dt; //start_date
                $end_date           = $inp_sd . " " . $inp_tm; //end_date
                $sttwork            = $this->request->getVar('sttwork');
                $spare_part_d       = $this->request->getVar('spare_part_detail'); //Spare Part Detail
                $d_sel              = $this->request->getVar('d_sel');
                if ($d_sel == '') {
                    $d_sel1 = '0000-00-00';
                } else {
                    $d_sel1 = date("Y-m-d", strtotime($d_sel));
                }
                $spare_part_arrived = $d_sel1 . " 00:00:00";
                $conditions         = $this->request->getVar('cdn'); //conditions
                $inspection_date    = $this->request->getVar('ind'); //inspection_date
                $email    = $this->request->getVar('email'); //email
                // $inp_ed             = $this->request->getVar('inp_ed');
                // $inp_ed1            = date("Y-m-d", strtotime($inp_ed));
                // $en_time            = date("H:i:s");
                // $en_time1           = date("H:i:s", strtotime($en_time));

                $data3 = "plant=$plant&user=$user&dev_lang=$dev_lang&plant_dept=$plant_dept&equipment_id=$equipment_id&checkpoint=$checkpoint&checkpoint_detail=$checkpoint_d&department_pic_id=$icdepp&problem_detail=$detpro&maintenance_plan=$maint&start_date=$start_date&end_date=$end_date&work_status_id=$sttwork&spare_part_detail=$spare_part_d&spare_part_arrived=$spare_part_arrived&id=$idpending";


                $url = 'https://report-id.online/api_patrol_prod/pfu/update_ts_action';
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data3);
                $authorization = "Authorization: Bearer " . $token;
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));
                $response_api = curl_exec($curl);
                $jsonData = json_decode($response_api, true);
                $respon_action = $jsonData['status'];
                if ($respon_action == false) {
                    $output = array(
                        'status'    =>  0,
                        "msg"       =>  $jsonData['message'],
                    );
                    exit(json_encode($output));
                }
                $ket_cache = $plant_dept . "-Patrol-Report";
                $cache->delete($ket_cache);
                $ket_cache_pending = $plant_dept . "-Patrol-Report-pending";
                $cache->delete($ket_cache_pending);
                $ket_cache_inprogress = $plant_dept . "-Patrol-Report-inprogress";
                $cache->delete($ket_cache_inprogress);
                $cache_log = $current_date_time . "_logg";
                $cache->delete($cache_log);
                $cachehistory = $plant_dept . "_history";
                $cache->delete($cachehistory);

                //sukses
                $output = array(
                    "status"    =>  1,
                    "csrf_hash" => csrf_hash(),
                    "msg"       =>  "New data has been saved"
                );
                exit(json_encode($output));
            } catch (\Exception $exc) {
                $output = array(
                    "status"    =>  $exc->getCode(),
                    "csrf_hash" => csrf_hash(),
                    "msg"       => $exc->getMessage(),
                );
                exit(json_encode($output));
            }
        } else {
            exit("Access Denied");
        }
    }

    /*
     * view action 
     * author :  FSM
     * date : 14 Jul 2022
     */
    function view_action_pending()
    {
        if ($this->request->isAJAX()) {
            try {
                $session = session();
                $validation =  \Config\Services::validation();
                if (!$session->has('nik'))
                    throw new \Exception("Session Expired", 2);
                session_write_close();
                $valid = $this->validate(['id' => ['rules' => 'required', 'label' => 'ID', 'errors' => ['required' => 'Rules.id.required',]]]);
                if (!$valid) {
                    $msg = ['errors' => ['errorID' => $validation->getError('id')]];
                }
                $valid2 = $this->validate(['tbl' => ['rules' => 'required', 'label' => 'Tabel', 'errors' => ['required' => 'Rules.tbl.required',]]]);
                if (!$valid2) {
                    $msg = ['errors' => ['errorID' => $validation->getError('tbl')]];
                }
                $valid3 = $this->validate(['status_work' => ['rules' => 'required', 'label' => 'Work Status', 'errors' => ['required' => 'Rules.status_work.required',]]]);
                if (!$valid3) {
                    $msg = ['errors' => ['errorID' => $validation->getError('status_work')]];
                }
                $valid4 = $this->validate(['picdep' => ['rules' => 'required', 'label' => 'Depertemen PIC', 'errors' => ['required' => 'Rules.picdep.required',]]]);
                if (!$valid3) {
                    $msg = ['errors' => ['errorID' => $validation->getError('picdep')]];
                }
                $valid5 = $this->validate(['con' => ['rules' => 'required', 'label' => 'Condi', 'errors' => ['required' => 'Rules.con.required',]]]);
                if (!$valid5) {
                    $msg = ['errors' => ['errorID' => $validation->getError('tbl')]];
                }
                $id_eq = $this->request->getVar('id');
                $tbl = $this->request->getVar('tbl');
                $ws = $this->request->getVar('status_work');
                $pic = $this->request->getVar('picdep');
                $con = $this->request->getVar('kon');
                $id_pl = $this->request->getVar('pl_d');
                $cekpoin = $this->request->getVar('cekpoin');
                $cekpoin_d = $this->request->getVar('cekpoin_d');
                if ($con == 'Need Action' or $con == 'need action') {
                    $conditions = "<div class='font-tabel-na'>" . $con . "</div>";
                } elseif ($con == 'Monitoring' or $con == 'monitoring') {
                    $conditions = "<div class='font-tabel-mt'>" . $con . "</div>";
                } elseif ($con == 'Abnormal' or $con == 'abnormal') {
                    $conditions = "<div class='font-tabel-an'>" . $con . "</div>";
                } else {
                    $conditions = "<div class='font-tabel-an'>" . $con . "</div>";
                }

                $current_date_time = date("Y-m-d");
                $today = date("d F Y");
                $plant_dept = $id_pl;
                $token      = TOKEN_API;
                $dev_lang   = DEV_LANG;
                $data = "dev_lang=$dev_lang";
                $button = '';

                /* 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 * CEK Dept CACHE DI SERVER REDIS - START
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 */
                $cache_dept = $current_date_time . "_dept";
                if (!$respon_data = cache($cache_dept)) {
                    $url = 'https://report-id.online/api_patrol_prod/pfu/get_department_pic';
                    $curl = curl_init($url);
                    $hasil0 = curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    $authorization = "Authorization: Bearer " . $token;
                    $hasil2 = curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    $hasil1 = curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));
                    $response_api = curl_exec($curl);
                    $jsonData = json_decode($response_api, true);
                    $respon_dept = $jsonData['status'];
                    if ($respon_dept == false) {
                        $output = array(
                            'status'    =>  0,
                            "msg"       =>  $jsonData['message'],
                        );
                        exit(json_encode($output));
                    }
                    $respon_data = $jsonData['result'];
                    curl_close($curl);
                    cache()->save($cache_dept, $respon_data, 43200);
                }
                foreach ($respon_data as $d) {
                    if ($d['id'] == $pic) {
                        $str_dept = "<option required='' value='" . $d['id'] . "'>";
                        $str_dept .= $d['department_pic'] . "</option>";
                    }
                }

                /* 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 * CEK work status CACHE DI SERVER REDIS - START
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 */
                $cache_status = $current_date_time . "_case";
                if (!$respon_work = cache($cache_status)) {
                    $url = 'https://report-id.online/api_patrol_prod/pfu/get_work_status';
                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    $authorization = "Authorization: Bearer " . $token;
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));
                    $response_api = curl_exec($curl);
                    $jsonData = json_decode($response_api, true);
                    $respon_status = $jsonData['status'];
                    if ($respon_status == false) {
                        $output = array(
                            'status'    =>  0,
                            "msg"       =>  $jsonData['message'],
                        );
                        exit(json_encode($output));
                    }
                    $respon_work = $jsonData['result'];
                    curl_close($curl);
                    cache()->save($cache_status, $respon_work, 30000);
                }
                $str_pr = "<option value=''> - Choose - </option>";
                foreach ($respon_work as $s) {

                    if ($s['id'] == $ws)
                        $str_pr .= "<option value='" . $s['id'] . "' selected=''>";
                    else
                        $str_pr .= "<option value='" . $s['id'] . "'>";
                    $str_pr .= $s['work_status'] . "</option>";
                }
                foreach ($respon_work as $w) {
                    if ($w['active'] == 'Y') {
                        $action_status[$w['id']] = $w['work_status'];
                    }
                }
                /* 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 * CEK Action Log CACHE DI SERVER REDIS - START
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 */
                // $cache_log = $current_date_time . "_logg";
                // if (!$respon_log = cache($cache_log)) {
                //     $url = 'https://report-id.online/api_patrol_prod/pfu/get_ts_action_log';
                //     $curl = curl_init($url);
                //     curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                //     $authorization = "Authorization: Bearer " . $token;
                //     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                //     $hasil1 = curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));
                //     $response_api = curl_exec($curl);
                //     $jsonData = json_decode($response_api, true);
                //     $respon_status = $jsonData['status'];
                //     if ($respon_status == false) {
                //         $output = array(
                //             'status'    =>  0,
                //             "msg"       =>  $jsonData['message'],
                //         );
                //         exit(json_encode($output));
                //     }
                //     $respon_log = $jsonData['result'];
                //     cache()->save($cache_log, $respon_log, 300);
                // }
                // $str_log = "<ul class='list-unstyled mb-0'>";
                // foreach ($respon_log as $log) {
                //     if ($log['plant_dept'] == $plant_dept && $log['equipment_id'] == $id) {

                //         $str_log .= "<li style=font-family:'Inter'; ><b>" . $action_status[$log['work_status_id']] . "</b></Br>" .  $r_date = date("d M Y", strtotime($log['date_created'])) . "</li></Br>";
                //     }
                // }
                // $str_log .= "</ul >";

                $data_log_action = "plant_dept=$plant_dept&equipment_id=$id_eq&checkpoint=$cekpoin&checkpoint_detail=$cekpoin_d";
                $url = 'https://report-id.online/api_patrol_prod/pfu/get_action_log_by_id';
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data_log_action);
                $authorization = "Authorization: Bearer " . $token;
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $hasil1 = curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));
                $response_api = curl_exec($curl);
                $jsonData = json_decode($response_api, true);
                $respon_status = $jsonData['status'];
                if ($respon_status == false) {
                    $output = array(
                        'status'    =>  0,
                        "msg"       =>  $jsonData['message'],
                    );
                    exit(json_encode($output));
                }
                $respon_log = $jsonData['result'];
                $str_log = "<ul class='list-unstyled mb-0'>";
                foreach ($respon_log as $log) {
                    $str_log .= "<li style=font-family:'Inter'; ><b>" . $action_status[$log['work_status_id']] . "</b></Br>" .  $r_date = date("d M Y", strtotime($log['date_created'])) . "</li></Br>";
                }
                $str_log .= "</ul >";

                $response = array(
                    "status"    => 1,
                    "csrf_hash" => csrf_hash(),
                    "str_dept" => $str_dept,
                    "str_status" => $str_pr,
                    "str_log"       => $str_log,
                    "today" => $today,
                    "kondi" => $conditions
                );
                exit(json_encode($response));
            } catch (\Exception $exc) {
                $json_data = array(
                    "status"    => 0,
                    "csrf_hash" => csrf_hash(),
                    "msg"       => $exc->getMessage(),
                );
                exit(json_encode($json_data));
            }
        } else die("Die!");
    }

    function view_action_inprogress()
    {
        if ($this->request->isAJAX()) {
            try {
                $session = session();
                $validation =  \Config\Services::validation();
                if (!$session->has('nik'))
                    throw new \Exception("Session Expired", 2);
                session_write_close();
                $valid = $this->validate(['id' => ['rules' => 'required', 'label' => 'ID', 'errors' => ['required' => 'Rules.id.required',]]]);
                if (!$valid) {
                    $msg = ['errors' => ['errorID' => $validation->getError('id')]];
                }
                $valid2 = $this->validate(['tbl' => ['rules' => 'required', 'label' => 'Tabel', 'errors' => ['required' => 'Rules.tbl.required',]]]);
                if (!$valid2) {
                    $msg = ['errors' => ['errorID' => $validation->getError('tbl')]];
                }
                $valid3 = $this->validate(['status_work' => ['rules' => 'required', 'label' => 'Work Status', 'errors' => ['required' => 'Rules.status_work.required',]]]);
                if (!$valid3) {
                    $msg = ['errors' => ['errorID' => $validation->getError('status_work')]];
                }
                $valid4 = $this->validate(['picdep' => ['rules' => 'required', 'label' => 'Depertemen PIC', 'errors' => ['required' => 'Rules.picdep.required',]]]);
                if (!$valid3) {
                    $msg = ['errors' => ['errorID' => $validation->getError('picdep')]];
                }
                $valid5 = $this->validate(['con' => ['rules' => 'required', 'label' => 'Condi', 'errors' => ['required' => 'Rules.con.required',]]]);
                if (!$valid5) {
                    $msg = ['errors' => ['errorID' => $validation->getError('tbl')]];
                }
                $id_eq = $this->request->getVar('id');
                $tbl = $this->request->getVar('tbl');
                $id_pl = $this->request->getVar('pl_d');
                $ws = $this->request->getVar('status_work');
                $pic = $this->request->getVar('picdep');
                $con = $this->request->getVar('con');
                $cekpoin = $this->request->getVar('cekpoin');
                $cekpoin_d = $this->request->getVar('cekpoin_d');


                if ($con == 'Need Action') {
                    $conditions = "<div class='font-tabel-na'>" . $con . "</div>";
                } elseif ($con == 'Monitoring') {
                    $conditions = "<div class='font-tabel-mt'>" . $con . "</div>";
                } elseif ($con == 'Abnormal') {
                    $conditions = "<div class='font-tabel-an'>" . $con . "</div>";
                } else {
                    $conditions = "<div class='font-tabel-an'>" . $con . "</div>";
                }

                $current_date_time = date("Y-m-d");
                $today = date("d F Y");
                $plant_dept = $id_pl;
                $token      = TOKEN_API;
                $dev_lang   = DEV_LANG;
                $data = "dev_lang=$dev_lang";
                $button = '';

                /* 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 * CEK Dept CACHE DI SERVER REDIS - START
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 */
                $cache_dept = $current_date_time . "_dept";
                if (!$respon_data = cache($cache_dept)) {
                    $url = 'https://report-id.online/api_patrol_prod/pfu/get_department_pic';
                    $curl = curl_init($url);
                    $hasil0 = curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    $authorization = "Authorization: Bearer " . $token;
                    $hasil2 = curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    $hasil1 = curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));
                    $response_api = curl_exec($curl);
                    $jsonData = json_decode($response_api, true);
                    $respon_dept = $jsonData['status'];
                    if ($respon_dept == false) {
                        $output = array(
                            'status'    =>  0,
                            "msg"       =>  $jsonData['message'],
                        );
                        exit(json_encode($output));
                    }
                    $respon_data = $jsonData['result'];
                    curl_close($curl);
                    cache()->save($cache_dept, $respon_data, 43200);
                }
                $str_dept = "<option required='' value=''>";
                foreach ($respon_data as $d) {
                    if ($d['id'] == $pic) {
                        $str_dept = "<option required='' value='" . $d['id'] . "'>";
                        $str_dept .= $d['department_pic'] . "</option>";
                    }
                }

                /* 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 * CEK work status CACHE DI SERVER REDIS - START
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 */
                $cache_status = $current_date_time . "_case";
                if (!$respon_work = cache($cache_status)) {
                    $url = 'https://report-id.online/api_patrol_prod/pfu/get_work_status';
                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    $authorization = "Authorization: Bearer " . $token;
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));
                    $response_api = curl_exec($curl);
                    $jsonData = json_decode($response_api, true);
                    $respon_status = $jsonData['status'];
                    if ($respon_status == false) {
                        $output = array(
                            'status'    =>  0,
                            "msg"       =>  $jsonData['message'],
                        );
                        exit(json_encode($output));
                    }
                    $respon_work = $jsonData['result'];
                    curl_close($curl);
                    cache()->save($cache_status, $respon_work, 30000);
                }
                $str_pr3 = "<option value=''> - Choose - </option>";
                foreach ($respon_work as $s) {
                    if ($s['id'] == '2' or $s['id'] == '3' or $s['id'] == '4' or $s['id'] == '5' or $s['id'] == '6') {
                        if ($s['id'] == $ws)
                            $str_pr3 .= "<option value='" . $s['id'] . "' selected=''>";
                        else
                            $str_pr3 .= "<option value='" . $s['id'] . "'>";
                        $str_pr3 .= $s['work_status'] . "</option>";
                    }
                }
                foreach ($respon_work as $w) {
                    if ($w['active'] == 'Y') {
                        $action_status[$w['id']] = $w['work_status'];
                    }
                }

                /* 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 * CEK Action Log CACHE DI SERVER REDIS - START
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 */
                $data_log_action = "plant_dept=$plant_dept&equipment_id=$id_eq&checkpoint=$cekpoin&checkpoint_detail=$cekpoin_d";
                // $cache_log = $current_date_time . "_logg";
                // if (!$respon_log = cache($cache_log)) {
                //                $url = 'https://report-id.online/api_patrol_prod/pfu/get_ts_action_log';
                $url = 'https://report-id.online/api_patrol_prod/pfu/get_action_log_by_id';
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data_log_action);
                $authorization = "Authorization: Bearer " . $token;
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $hasil1 = curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));
                $response_api = curl_exec($curl);
                $jsonData = json_decode($response_api, true);
                $respon_status = $jsonData['status'];
                if ($respon_status == false) {
                    $output = array(
                        'status'    =>  0,
                        "msg"       =>  $jsonData['message'],
                    );
                    exit(json_encode($output));
                }
                $respon_log = $jsonData['result'];
                //     cache()->save($cache_log, $respon_log, 300);
                // }
                $str_log = "<ul class='list-unstyled mb-0'>";
                foreach ($respon_log as $log) {
                    $str_log .= "<li style=font-family:'Inter'; ><b>" . $action_status[$log['work_status_id']] . "</b></Br>" .  $r_date = date("d M Y", strtotime($log['date_created'])) . "</li></Br>";
                }
                $str_log .= "</ul >";

                $response = array(
                    "status"    => 1,
                    "csrf_hash" => csrf_hash(),
                    "str_dept" => $str_dept,
                    "str_status" => $str_pr3,
                    //"str_staf" => $str_staf,
                    //"b_button" => $button,
                    "str_log"       => $str_log,
                    "today" => $today,
                    "kondi" => $conditions
                    // "not_started"    => $not_started,
                    //"not_action"    => $cases_action,
                    //"Equipment_Code"    => $Equipment_Code,
                );
                exit(json_encode($response));
            } catch (\Exception $exc) {
                $json_data = array(
                    "status"    => 0,
                    "csrf_hash" => csrf_hash(),
                    "msg"       => $exc->getMessage(),
                );
                exit(json_encode($json_data));
            }
        } else die("Die!");
    }
    /*
     * Case History 
     * author :  FSM
     * date : 14 Jul 2022
     */
    function list_history()
    {
        if ($this->request->isAJAX()) {
            try {
                $session = session();
                $validation =  \Config\Services::validation();
                if (!$session->has('nik'))
                    throw new \Exception("Session Expired", 2);
                session_write_close();
                $valid = $this->validate(['id' => ['rules' => 'required', 'label' => 'ID', 'errors' => ['required' => 'Rules.id.required',]]]);
                if (!$valid) {
                    $msg = ['errors' => ['errorID' => $validation->getError('id')]];
                }

                $plant_dept = $this->request->getVar('id');
                $dl = DEV_LANG;
                $token = TOKEN_API;
                $data = "plant_dept=$plant_dept";
                $data_work = "dev_lang=$dl";
                $current_date_time = date("Y-m-d");

                $str1 = '';
                $nostr1 = 0;
                //$data_status = "dev_lang=$id";
                /* 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 * CEK Dept CACHE DI SERVER REDIS - START
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 */
                $cache_dept = $current_date_time . "_dept";
                if (!$respon_data_dep = cache($cache_dept)) {
                    $url = 'https://report-id.online/api_patrol_prod/pfu/get_department_pic';
                    $curl = curl_init($url);
                    $hasil0 = curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    $authorization = "Authorization: Bearer " . $token;
                    $hasil2 = curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    $hasil1 = curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));
                    $response_api = curl_exec($curl);
                    $jsonData = json_decode($response_api, true);
                    $respon_dept = $jsonData['status'];
                    if ($respon_dept == false) {
                        $output = array(
                            'status'    =>  0,
                            "msg"       =>  $jsonData['message'],
                        );
                        exit(json_encode($output));
                    }
                    $respon_data_dep = $jsonData['result'];
                    curl_close($curl);
                    cache()->save($cache_dept, $respon_data_dep, 43200);
                }
                foreach ($respon_data_dep as $d) {
                    $action_dept[$d['id']] = $d['department_pic'];
                }
                /* 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 * CEK work status CACHE DI SERVER REDIS - START
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 */
                $cache_status = $current_date_time . "_case";
                if (!$respon_work = cache($cache_status)) {
                    $url = 'https://report-id.online/api_patrol_prod/pfu/get_work_status';
                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_work);
                    $authorization = "Authorization: Bearer " . $token;
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));
                    $response_api = curl_exec($curl);
                    $jsonData = json_decode($response_api, true);
                    $respon_status = $jsonData['status'];
                    if ($respon_status == false) {
                        $output = array(
                            'status'    =>  0,
                            "msg"       =>  $jsonData['message'],
                        );
                        exit(json_encode($output));
                    }
                    $respon_work = $jsonData['result'];
                    curl_close($curl);
                    cache()->save($cache_status, $respon_work, 30000);
                }
                foreach ($respon_work as $s) {
                    if ($s['active'] == 'Y') {
                        $action_status[$s['id']] = $s['work_status'];
                    }
                }
                $cachehistory = $plant_dept . "_history";
                if (!$respon_data = cache($cachehistory)) {
                    $url = 'https://report-id.online/api_patrol_prod/pfu/get_action_log_complete';
                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    $authorization = "Authorization: Bearer " . $token;
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    $hasil1 = curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));

                    $response_api = curl_exec($curl);
                    $jsonData = json_decode($response_api, true);
                    $respon_data_s = $jsonData['status'];
                    if ($respon_data_s == false) {
                        $respon_data = '';
                    } else {
                        $respon_data = $jsonData['result'];
                    }
                    curl_close($curl);
                    cache()->save($cachehistory, $respon_data, 86400); //5 menit
                }

                $basurl = base_url();
                $nostr1 = 0;
                if ($respon_data != '') {
                    foreach ($respon_data as $item) {
                        $encrypted_id = $item['id'];
                        $nostr1++;
                        $tmped = "class='btnHis' data-id='" . $encrypted_id . "'";
                        $tmped .= "data-pln='" . $plant_dept . "'";
                        $tmped .= "data-con='" . $item['conditions'] . "'";
                        $tmped .= "data-ind='" . $item['inspection_date'] . "'";
                        $tmped .= "data-equ='" . $item['equipment_id'] . "'";
                        $tmped .= "data-cpt='" . $item['checkpoint'] . "'";
                        $tmped .= "data-cpd='" . $item['checkpoint_detail'] . "'";
                        $tmped .= "data-cln='" . $item['checklist_notes'] . "'";

                        $tmped .= "data-dpc='" . $action_dept[$item['department_pic_id']] . "'";
                        $tmped .= "data-prd='" . $item['problem_detail'] . "'";
                        $tmped .= "data-mph='" . $item['maintenance_plan'] . "'";
                        $tmped .= "data-wsi='" . $item['work_status_id'] . "'";
                        $tmped .= "data-rqd='" . date("d M Y h:i A", strtotime($item['start_date'])) . "'";
                        $tmped .= "data-tcd='" . date("d M Y h:i A", strtotime($item['end_date'])) . "'";
                        $tmped .= "data-spd='" . $item['spare_part_detail'] . "'";
                        $tmped .= "data-spa='" . date("d M Y", strtotime($item['spare_part_arrived']))  . "'";
                        $tmped .= "data-ema='" . $item['staff_pic'] . "'";
                        $str1 .= "<tr class='font-tabel-tr' >";

                        if ($item['conditions'] == 'need action' or $item['conditions'] == 'Need Action') {
                            $str1 .= "<td   title=''><div class='font-tabel-na'>" . $item['conditions'] . "</div></td>";
                        } elseif ($item['conditions'] == 'monitoring' or $item['conditions'] == 'Monitoring') {
                            $str1 .= "<td   title=''><div class='font-tabel-mt'>" . $item['conditions'] . "</div></td>";
                        } elseif ($item['conditions'] == 'abnormal' or $item['conditions'] == 'Abnormal') {
                            $str1 .= "<td   title=''><div class='font-tabel-an'>" . $item['conditions'] . "</div></td>";
                        } elseif ($item['conditions'] == '') {
                            $str1 .= "<td   title=''><div class='font-tabel-an'>-</div></td>";
                        }

                        $str1 .= "<td   title=''>" . date("d M Y h:i A", strtotime($item['inspection_date'])) . "</td>";
                        $str1 .= "<td   title=''>" . $item['equipment_id'] . "</td>";
                        $str1 .= "<td   title=''>" . $item['checkpoint'] . "</td>";
                        $str1 .= "<td   title=''>" . $item['checkpoint_detail'] . "</td>";

                        $str1 .= "<td   title=''>" . $action_status[$item['work_status_id']] . "</td>";

                        $str1 .= "<td  class=''><a href='javascript:void(0)' " . $tmped . " title='Open Case'><h7 class='font-tabel-td ' ><small></small>Open Case</h7></a></td>";
                        $str1 .= "</tr>";
                    }
                }


                if ($nostr1 == 0) {
                    $str1 = "<tr><td colspan='7' style='margin-left: auto;
                    margin-right: auto;'>";
                    $str1 .= "<div class='table-no-data'></Br></Br></Br>";
                    $str1 .= "<img class='table-no-data-icon' 
                                src='" .   $basurl . "/public/assets/images/Vector_ceklist.png' height='18'>";
                    $str1 .= "<p class='table-no-data-bolt'><b>No Case History</b><p>
                    <p class='table-no-data-tulisan'>All completed cases will be displayed on this page.</p></Br>";
                    $str1 .= "</div></td>";
                    $str1 .= "</tr>";
                }
                $response = array(
                    "status"    => 1,
                    "csrf_hash"     => csrf_hash(),
                    "case_history"           => $str1
                );
                exit(json_encode($response));
            } catch (\Exception $exc) {
                $json_data = array(
                    "status"    => 0,
                    //"csrf_hash" => $this->security->get_csrf_hash(),
                    "csrf_hash" => csrf_hash(),
                    "msg"       => $exc->getMessage(),
                );
                exit(json_encode($json_data));
            }
        } else die("Die!");
    }
    /*
     * view action 
     * author :  FSM
     * date : 14 Jul 2022
     */
    function view_action_history()
    {
        if ($this->request->isAJAX()) {
            try {
                $session = session();
                $validation =  \Config\Services::validation();
                if (!$session->has('nik'))
                    throw new \Exception("Session Expired", 2);
                session_write_close();
                $valid = $this->validate(['id' => ['rules' => 'required', 'label' => 'ID', 'errors' => ['required' => 'Rules.id.required',]]]);
                if (!$valid) {
                    $msg = ['errors' => ['errorID' => $validation->getError('id')]];
                }

                $id_action = $this->request->getVar('id');
                $plan = $this->request->getVar('pln'); //plant
                $eq_id = $this->request->getVar('eq_id'); //equipment_id
                $cpt = $this->request->getVar('cpt');
                $cpd = $this->request->getVar('cpd');

                $current_date_time = date("Y-m-d");
                $token = TOKEN_API;
                $id = 'in';
                $data = "dev_lang=$id";
                $data_l = "plant_dept=$plan&equipment_id=$eq_id&checkpoint=$cpt&checkpoint_detail=$cpd";

                /* 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 * CEK work status CACHE DI SERVER REDIS - START
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 */
                $cache_status = $current_date_time . "_case";
                if (!$respon_work = cache($cache_status)) {
                    $url = 'https://report-id.online/api_patrol_prod/pfu/get_work_status';
                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    $authorization = "Authorization: Bearer " . $token;
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));
                    $response_api = curl_exec($curl);
                    $jsonData = json_decode($response_api, true);
                    $respon_status = $jsonData['status'];
                    if ($respon_status == false) {
                        $output = array(
                            'status'    =>  0,
                            "msg"       =>  $jsonData['message'],
                        );
                        exit(json_encode($output));
                    }
                    $respon_work = $jsonData['result'];
                    curl_close($curl);
                    cache()->save($cache_status, $respon_work, 30000);
                }
                foreach ($respon_work as $s) {
                    if ($s['active'] == 'Y') {
                        $action_status[$s['id']] = $s['work_status'];
                    }
                }

                /* 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 * CEK Action Log CACHE DI SERVER REDIS - START
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 */
                // $cache_log = $current_date_time . "_logg";
                // if (!$respon_log = cache($cache_log)) {
                $url = 'https://report-id.online/api_patrol_prod/pfu/get_action_log_by_id';
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data_l);
                $authorization = "Authorization: Bearer " . $token;
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $hasil1 = curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));
                $response_api = curl_exec($curl);
                $jsonData = json_decode($response_api, true);
                $respon_status = $jsonData['status'];
                if ($respon_status == false) {
                    $output = array(
                        'status'    =>  0,
                        "msg"       =>  $jsonData['message'],
                    );
                    exit(json_encode($output));
                }
                $respon_log = $jsonData['result'];
                //     cache()->save($cache_log, $respon_log, 600);
                // }
                $str = '';
                foreach ($respon_log as $rd) {
                    $str .= "<tr class='font-tabel-tr' >";
                    $str .= "<td   title=''>" . date("d M Y h:i A", strtotime($rd['date_updated'])) . "</td>";
                    if ($rd['work_status_id'] == null)
                        $str .= "<td   title=''></td>";
                    else
                        $str .= "<td  class=\"text-right\" title=''>" . $action_status[$rd['work_status_id']] . "</td>";
                    $str .= "</tr>";
                }

                $response = array(
                    "status"        => 1,
                    "tbl_h" => $str,
                    "csrf_hash"     => csrf_hash(),

                );
                exit(json_encode($response));
            } catch (\Exception $exc) {
                $json_data = array(
                    "status"    => 0,
                    //"csrf_hash" => $this->security->get_csrf_hash(),
                    "csrf_hash" => csrf_hash(),
                    "msg"       => $exc->getMessage(),
                );
                exit(json_encode($json_data));
            }
        } else die("Die!");
    }
}
