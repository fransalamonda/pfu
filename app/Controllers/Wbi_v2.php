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
                $plant_dept = "IDCGMD";
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
                        $str_plant .= "<option value='" . $d['dept'] . "'>" . $d['plant_name'] . "</option>";
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
                    cache()->save($cache_dept, $respon_data_dep, 30000);
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

                        $encrypted_id = $item['equipment_id'];
                        $newDate = date("d/m/Y", strtotime($item['last_inspection_date']));
                        $tmped = "class='btnRes' data-id='" . $encrypted_id . "'";
                        $tmped .= " data-tbl='waiting'";
                        $tmped .= " data-con='" . $item['conditions'] . "'";
                        $tmped .= " data-tg='" . $newDate . "'";
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
                    cache()->save($ket_cache_pending, $respon_data_pending, 600); //5 menit
                }
                if ($respon_data_pending == '') {
                    $str2 = "<tr><td colspan='6' style='margin-left: auto;
                    margin-right: auto;'>";
                    $str2 .= "<div class='table-no-data'></Br></Br>";
                    $str2 .= "<img class='table-no-data-icon' 
                                src='" .   $basurl . "/public/assets/images/Vector_ceklist.png' height='18'>";
                    $str2 .= "<p class='table-no-data-bolt'><b>Well Done</b><p></Br>
                    <p class='table-no-data-tulisan'>There are no more cases that need to be assigned. </Br>
                    Let’s check Need Review to monitor current cases</p></Br>";
                    $str2 .= "<button type='button' class='table-no-data-btn btn-next-2' >Go to Need Review</button></div></td>";
                    $str2 .= "</tr>";
                } else {
                    foreach ($respon_data_pending as $pa) {
                        $encrypted_id = $pa['equipment_id'];
                        $r_date = date("d M Y h:i A", strtotime($pa['date_created']));
                        if ($pa['inspection_date'] == "0000-00-00 00:00:00")
                            $newDate = "00-00-0000";
                        else
                            $newDate = date("d/m/Y", strtotime($pa['inspection_date']));
                        $tmppa = "class='btnRes' data-id='" . $encrypted_id . "'";
                        $tmppa .= " data-tbl='waiting'";
                        $tmppa .= " data-idpending='" . $pa['id'] . "'";
                        $tmppa .= " data-con='" . $pa['conditions'] . "'";
                        $tmppa .= " data-tg='" . $newDate . "'";
                        $tmppa .= " data-ec='" . $pa['equipment_id'] . "'";
                        $tmppa .= " data-cp='" . $pa['checkpoint'] . "'";
                        $tmppa .= " data-cd='" . $pa['checkpoint_detail'] . "'";
                        $tmppa .= " data-cn='" . $pa['checklist_notes'] . "'";
                        $tmppa .= " data-picdep='" . $pa['department_pic_id'] . "'";
                        $tmppa .= " data-pl_d='" . $plant_dept . "'";
                        $tmppa .= " data-pro_d='" . $pa['problem_detail'] . "'";
                        $tmppa .= " data-maintenance_plan='" . $pa['maintenance_plan'] . "'";
                        $tmppa .= " data-date_created='" . $r_date . "'";
                        $tmppa .= " data-st='" . $pa['work_status_id'] . "'";
                        $tmppa .= " data-spd='" . $pa['spare_part_detail'] . "'";
                        $tmppa .= " data-spa='" . $pa['spare_part_arrived'] . "'";
                        $nostr2++;
                        $str2 .= "<tr class='font-tabel-tr' >";
                        if ($pa['conditions'] == 'Need Action') {
                            $str2 .= "<td   title=''><div class='font-tabel-na'>" . $pa['conditions'] . "</div></td>";
                        } elseif ($pa['conditions'] == 'Monitoring') {
                            $str2 .= "<td   title=''><div class='font-tabel-mt'>" . $pa['conditions'] . "</div></td>";
                        } elseif ($pa['conditions'] == 'Abnormal') {
                            $str2 .= "<td   title=''><div class='font-tabel-an'>" . $pa['conditions'] . "</div></td>";
                        } else {
                            $str2 .= "<td   title=''><div class='font-tabel-an'></div></td>";
                        }
                        $str2 .= "<td   title=''>" . $pa['equipment_id'] . "</td>";
                        $str2 .= "<td   title=''>" . $newDate . "</td>";
                        $str2 .= "<td   title='Checkpoint'>" . $pa['checkpoint'] . "</td>";
                        $str2 .= "<td   title='Checkpoint Detail'>" . wordwrap($pa['checkpoint_detail'], 30, "<br>\n") . "</td>";
                        $str2 .= "<td   title='Work'>" . $action_status[$pa['work_status_id']] . "</td>";
                        $str2 .= "<td  class=''><a href='javascript:void(0)' " . $tmppa . " title='Open Case'><h7 class='font-tabel-td ' ><small></small>Open Case</h7></a></td>";
                        $str2 .= "</tr>";
                    }
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
                    $str3 = "<tr><td colspan='6' style='margin-left: auto;
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
                        $r_date = date("d M Y h:i A", strtotime($ip['date_created']));
                        $encrypted_id = $ip['equipment_id'];
                        if ($ip['inspection_date'] == "0000-00-00 00:00:00")
                            $newDate = "00-00-0000";
                        else
                            $newDate = date("d/m/Y", strtotime($ip['inspection_date']));
                        $tmpip = "class='btnRes' data-id='" . $encrypted_id . "'";
                        $tmpip .= " data-idip='" . $ip['id'] . "'";
                        $tmpip .= " data-tbl='waiting'";
                        $tmpip .= " data-con='" . $ip['conditions'] . "'";
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
                        $nostr3++;
                        $str3 .= "<tr class='font-tabel-tr' >";
                        if ($ip['conditions'] == 'Need Action') {
                            $str3 .= "<td   title=''><div class='font-tabel-na'>" . $ip['conditions'] . "</div></td>";
                        } elseif ($ip['conditions'] == 'Monitoring') {
                            $str3 .= "<td   title=''><div class='font-tabel-mt'>" . $ip['conditions'] . "</div></td>";
                        } elseif ($ip['conditions'] == 'Abnormal') {
                            $str3 .= "<td   title=''><div class='font-tabel-an'>" . $ip['conditions'] . "</div></td>";
                        } else {
                            $str3 .= "<td   title=''><div class='font-tabel-an'></div></td>";
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
                //$plant_dept = 'IDCGMD';
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
                    //"str_log"       => $str_log,
                    "today"         => $today,
                    "conditions" => $conditions,
                    // "not_started"    => $not_started,
                    //"not_action"    => $cases_action,
                    //"Equipment_Code"    => $Equipment_Code,
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
    function list_data_v1()
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
                $plant              = $this->request->getVar('plant');
                $id = DEV_LANG;
                $role_name = $_SESSION['role_display_name'];
                //$role_name = "IDCGBT";
                $plant_dept = "IDCGMD";
                $token = TOKEN_API;
                $nik     = $_SESSION['nik'];

                $str1 = '';
                $nostr1 = 0;
                $str2 = '';
                $nostr2 = 0;
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
                    cache()->save($cache_dept, $respon_data_dep, 30000);
                }
                foreach ($respon_data_dep as $d) {
                    //if ($d['active'] == 'Y') {
                    $action_dept[$d['id']] = $d['department_pic'];
                    //}
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

                /* 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 * Update Pending Patrol Report  - START
                 * 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 */
                // $data_pending = "user=$nik&dev_lang=$id&plant_dept=$plant_dept";
                // $url = 'https://report-id.online/api_patrol_prod/pfu/get_patrol_report';
                // $curl = curl_init($url);
                // curl_setopt($curl, CURLOPT_POSTFIELDS, $data_pending);
                // $authorization = "Authorization: Bearer " . $token;
                // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));
                // $response_pending = curl_exec($curl);
                // $jsonData_p = json_decode($response_pending, true);
                // $respon_p = $jsonData_p['status'];
                // if ($respon_p == false) {
                //     $output = array(
                //         'status'    =>  0,
                //         "msg"       =>  $respon_p['message'],
                //     );
                //     exit(json_encode($output));
                // }
                /* 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 * CEK Patrol Report CACHE DI SERVER REDIS - START
                 * 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 */
                $data = "dev_lang=$id&plant_dept=$plant_dept";
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
                    $respon_login = $jsonData['status'];

                    if ($respon_login == false) {
                        $output = array(
                            'status'    =>  0,
                            "msg"       =>  $jsonData['message'],
                        );
                        exit(json_encode($output));
                    }
                    $respon_data = $jsonData['result'];
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
                foreach ($respon_data as $item) {

                    $encrypted_id = $item['equipment_id'];
                    $newDate = date("d/m/Y", strtotime($item['last_inspection_date']));
                    $tmped = "class='btnRes' data-id='" . $encrypted_id . "'";
                    $tmped .= " data-tbl='waiting'";
                    $tmped .= " data-con='" . $item['conditions'] . "'";
                    $tmped .= " data-tg='" . $newDate . "'";
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
                }

                if ($nostr1 == '0') {
                    $str1 = "<tr><td colspan='6' style='margin-left: auto;
                    margin-right: auto;'>";
                    $str1 .= "<div class='table-no-data'></Br></Br>";
                    $str1 .= "<img class='table-no-data-icon' 
                                src='" .   $basurl . "/public/assets/images/Vector_ceklist.png' height='18'>";
                    $str1 .= "<p class='table-no-data-bolt'><b>Well Done</b><p></Br>
                    <p class='table-no-data-tulisan'>There are no more cases that need to be assigned. </Br>
                    Let’s check In Progress to monitor current cases</p></Br>";
                    $str1 .= "<button type='button' class='table-no-data-btn btn-next-1' >Go to In Progress</button></div></td>";
                    $str1 .= "</tr>";
                }

                /* 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 * CEK get_ts_action CACHE DI SERVER REDIS - START
                 * 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 */
                $data_h = "dev_lang=$id";
                $cachegetaction = $plant_dept . "_getaction";
                if (!$respon_action = cache($cachegetaction)) {

                    $url = 'https://report-id.online/api_patrol_prod/pfu/get_ts_action';
                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_h);
                    $authorization = "Authorization: Bearer " . $token;
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    $hasil1 = curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));

                    $response_api = curl_exec($curl);
                    $jsonData = json_decode($response_api, true);
                    $respon_his = $jsonData['status'];

                    if ($respon_his == false) {
                        $output = array(
                            'status'    =>  0,
                            "msg"       =>  $jsonData['message'],
                        );
                        exit(json_encode($output));
                    }

                    $respon_action = $jsonData['result'];
                    curl_close($curl);
                    cache()->save($cachegetaction, $respon_action, 86400); //5 menit
                }
                foreach ($respon_action as $h) {
                    /* 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 * Tabel pending Action - START
                 * 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 */

                    if ($h['work_status_id'] == '1' && $h['plant_dept'] == $plant_dept) {

                        $encrypted_id = $h['id'];
                        $tmppa = "class='btnRes' data-id='" . $encrypted_id . "'";
                        $tmppa .= " data-tbl='pending'";
                        $tmppa .= " data-ec='" . $h['equipment_id'] . "'";
                        $tmppa .= " data-cp='" . $h['checkpoint'] . "'";
                        $tmppa .= " data-cd='" . $h['checkpoint_detail'] . "'";
                        $tmppa .= " data-cn='" . $h['checklist_notes'] . "'";
                        $tmppa .= " data-pl='" . $h['plant'] . "'";
                        $tmppa .= " data-pl_d='" . $plant_dept . "'";
                        $tmppa .= " data-dep='" . $action_dept[$h['department_pic_id']] . "'";
                        $tmppa .= " data-st='" . $h['work_status_id'] . "'";
                        $tmppa .= " data-sd='" . $h['start_date'] . "'";
                        $tmppa .= " data-pd='" . $h['problem_detail'] . "'";


                        $Date_pa = date("d/m/Y", strtotime($h['date_created']));
                        $status = $action_status[$h['work_status_id']];
                        $nostr2++;

                        $str2 .= "<tr class='font-tabel-tr' >";
                        // if ($h['conditions'] == 'Need Action') {
                        //     $str2 .= "<td   title=''><div class='font-tabel-na'>" . $h['conditions'] . "</div></td>";
                        // } elseif ($h['conditions'] == 'Monitoring') {
                        //     $str2 .= "<td   title=''><div class='font-tabel-mt'>" . $h['conditions'] . "</div></td>";
                        // } elseif ($h['conditions'] == 'Abnormal') {
                        //     $str2 .= "<td   title=''><div class='font-tabel-an'>" . $h['conditions'] . "</div></td>";
                        // }
                        $str2 .= "<td   title=''><div class='font-tabel-na'></div></td>";
                        //$str2 .= "<td   title=''>" . $Date_pa . "</td>";
                        $str2 .= "<td   title=''></td>";
                        $str2 .= "<td   title=''>" . $h['equipment_id'] . "</td>";
                        $str2 .= "<td   title=''>" . $h['checkpoint'] . "</td>";
                        $str2 .= "<td   title=''>" . wordwrap($h['checkpoint_detail'], 50, "<br>\n") . "</td>";
                        $str2 .= "<td   title=''>" . $status . "</td>";
                        $str2 .= "<td  class=''><a href='javascript:void(0)' " . $tmppa . " title='Open Case'><h7 class='font-tabel-td ' ><small></small>Open Case</h7></a></td>";

                        $str2 .= "</tr>";
                    }
                    /* 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 * Tabel in Progress - START
                 * 
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 */
                    if ($h['work_status_id'] == '2' || $h['work_status_id'] == '3' || $h['work_status_id'] == '4' && $h['plant_dept'] == $plant_dept) {
                        $encrypted_id = $h['id'];
                        $tmpip = "class='btnRes' data-id='" . $encrypted_id . "'";
                        $tmpip .= " data-tbl='pending'";
                        $tmpip .= " data-ec='" . $h['equipment_id'] . "'";
                        $tmpip .= " data-cp='" . $h['checkpoint'] . "'";
                        $tmpip .= " data-cd='" . $h['checkpoint_detail'] . "'";
                        $tmpip .= " data-cn='" . $h['checklist_notes'] . "'";
                        $tmpip .= " data-pl='" . $h['plant'] . "'";
                        $tmpip .= " data-pl_d='" . $plant_dept . "'";
                        $tmpip .= " data-dep='" . $action_dept[$h['department_pic_id']] . "'";
                        $tmpip .= " data-st='" . $h['work_status_id'] . "'";

                        $nostr3++;
                        $Date_pa = date("d/m/Y", strtotime($h['date_created']));
                        $status = $action_status[$h['work_status_id']];

                        $str3 .= "<tr class='font-tabel-tr' >";
                        // if ($h['conditions'] == 'Need Action') {
                        //     $str2 .= "<td   title=''><div class='font-tabel-na'>" . $h['conditions'] . "</div></td>";
                        // } elseif ($h['conditions'] == 'Monitoring') {
                        //     $str2 .= "<td   title=''><div class='font-tabel-mt'>" . $h['conditions'] . "</div></td>";
                        // } elseif ($h['conditions'] == 'Abnormal') {
                        //     $str2 .= "<td   title=''><div class='font-tabel-an'>" . $h['conditions'] . "</div></td>";
                        // }
                        $str3 .= "<td   title=''><div class='font-tabel-na'></div></td>";
                        //$str2 .= "<td   title=''>" . $Date_pa . "</td>";
                        $str3 .= "<td   title=''></td>";
                        $str3 .= "<td   title=''>" . $h['equipment_id'] . "</td>";
                        $str3 .= "<td   title=''>" . $h['checkpoint'] . "</td>";
                        $str3 .= "<td   title=''>" . wordwrap($h['checkpoint_detail'], 50, "<br>\n") . "</td>";
                        $str3 .= "<td   title=''>" . $status . "</td>";
                        $str3 .= "<td  class=''><a href='javascript:void(0)' " . $tmpip . " title='Open Case'><h7 class='font-tabel-td ' ><small></small>Open Case</h7></a></td>";

                        $str3 .= "</tr>";
                    }
                }

                if ($nostr2 == '0') {
                    $str2 = "<tr><td colspan='6' style='margin-left: auto;
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
                if ($nostr3 == '0') {
                    $str3 = "<tr><td colspan='6' style='margin-left: auto;
                    margin-right: auto;'>";
                    $str3 .= "<div class='table-no-data'></Br></Br>";
                    $str3 .= "<img class='table-no-data-icon' 
                                src='" .   $basurl . "/public/assets/images/Vector_ceklist.png' height='18'></Br>";
                    $str3 .= "<p class='table-no-data-bolt'><b>Well Done</b><p></Br>
                    <p class='table-no-data-tulisan'>There are no more cases that need to review. </Br>";
                    $str3 .= "</div></td>";
                    $str3 .= "</tr>";
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
                    //"str_plant"    => $str_plant,
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
                $id = DEV_LANG;
                $plant_dept = 'IDCGMD';
                $token = TOKEN_API;
                $data = "dev_lang=$id";
                $cachehistory = $plant_dept . "_history";
                $str1 = '';
                $nostr1 = 0;

                if (!$respon_data = cache($cachehistory)) {

                    $url = 'https://report-id.online/api_patrol_prod/pfu/get_ts_action';
                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    $authorization = "Authorization: Bearer " . $token;
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    $hasil1 = curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', $authorization));


                    $response_api = curl_exec($curl);
                    $jsonData = json_decode($response_api, true);
                    $respon_login = $jsonData['status'];

                    if ($respon_login == false) {
                        $output = array(
                            'status'    =>  0,
                            "msg"       =>  $jsonData['message'],
                        );
                        exit(json_encode($output));
                    }

                    $respon_data = $jsonData['result'];
                    curl_close($curl);
                    cache()->save($cachehistory, $respon_data, 86400); //5 menit
                }


                $basurl = base_url();
                foreach ($respon_data as $item) {
                    if ($item['plant_dept'] == $plant_dept) {
                        $encrypted_id = $item['equipment_id'];
                        $tmped = "class='btnHis' data-id='" . $encrypted_id . "'";
                        // $tmped .= " data-tbl='waiting'";

                        $nostr1++;
                        $str1 .= "<tr class='font-tabel-tr' >";
                        $str1 .= "<td   title=''></td>";
                        $str1 .= "<td   title=''></td>";
                        $str1 .= "<td   title=''>" . $item['equipment_id'] . "</td>";
                        $str1 .= "<td   title=''>" . $item['checkpoint'] . "</td>";
                        $str1 .= "<td   title=''>" . $item['checkpoint_detail'] . "</td>";
                        $str1 .= "<td   title=''></td>";
                        $str1 .= "<td  class=''><a href='javascript:void(0)' " . $tmped . " title='Open Case'><h7 class='font-tabel-td ' ><small></small>Open Case</h7></a></td>";
                        $str1 .= "</tr>";
                    }
                }

                if ($nostr1 == '0') {
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


    function add_act()
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
                $current_date_time = date("Y-m-d H:i:s");
                $token = TOKEN_API;

                $equipment_id = $this->request->getVar('idw');
                $checkpoint = $this->request->getVar('ckt');
                $checkpoint_d = $this->request->getVar('ckt_d');
                $plant              = $this->request->getVar('plant');
                $plant_dept         = $this->request->getVar('plant_d');
                $icdepp             = $this->request->getVar('icdepp');
                $detpro             = $this->request->getVar('detpro');
                $maint              = $this->request->getVar('maint');
                $inp_sd             = $this->request->getVar('inp_sd');
                $inp_sd1 = date("Y-m-d", strtotime($inp_sd));
                $sd_time            = $this->request->getVar('sd_time');
                $sd_time1 = date("H:i:s", strtotime($sd_time));
                $start_date         = $inp_sd1 . " " . $sd_time1;
                $inp_ed             = $this->request->getVar('inp_ed');
                $inp_ed1 = date("Y-m-d", strtotime($inp_ed));
                $en_time            = $this->request->getVar('en_time');
                $en_time1 = date("H:i:s", strtotime($en_time));
                $end_date           = $inp_ed1 . " " . $en_time1;
                $sttwork            = $this->request->getVar('sttwork');
                $spare_part_d = $this->request->getVar('spare_part_detail');
                $spare_part_a       = $this->request->getVar('spare_part_detail');
                $d_sel                = $this->request->getVar('d_sel');
                $d_sel1 = date("Y-m-d", strtotime($d_sel));
                $spa                = $this->request->getVar('spa');
                $spa1 = date("H:i:s", strtotime($spa));
                $spare_part_arrived = $d_sel1 . " " . $spa1;
                $data = "plant=$plant&user='" . $_SESSION['nik'] . "'&dev_lang='" . DEV_LANG . "'&plant_dept=$plant_dept&equipment_id=$equipment_id&checkpoint=$checkpoint&checkpoint_detail=$checkpoint_d&department_pic_id=$icdepp&problem_detail=$detpro&maintenance_plan=$maint&start_date=$start_date&end_date=$end_date&work_status_id=$sttwork&spare_part_detail=$spare_part_d&spare_part_arrived=$spare_part_arrived";

                $url = 'https://report-id.online/api_patrol_prod/pfu/add_ts_action';
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
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
                $cachegetaction = $plant_dept . "_getaction";
                $cache->delete($cachegetaction);
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

    function add_act_pending()
    {
        if ($this->request->isAJAX()) {
            try {
                $email_smtp = \Config\Services::email();

                // $this->email->setFrom('digital.cg@cemindo.com', 'fsm');
                // $this->email->setTo('fransalamonda@gmail.com');
                // $email_smtp->setSubject("Ini subjectnya");
                // $email_smtp->setMessage("Ini isi/body email");


                // $email_smtp->send();

                // $this->email->attach($attachment);

                // $this->email->setSubject($title);
                // $this->email->setMessage($message);

                // if (!$this->email->send()) {
                //     return false;
                // } else {
                //     return true;
                // }
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
                $id = $this->request->getVar('id');
                $tbl = $this->request->getVar('tbl');
                $ws = $this->request->getVar('status_work');
                $pic = $this->request->getVar('picdep');
                $con = $this->request->getVar('con');
                $id_pl = $this->request->getVar('pl_d');

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
                 * CEK Get Action DI SERVER REDIS - START
                 * +++++++++++++++++++++++++++++++++++++++++++++
                 */
                // $cache_action = $current_date_time . "_" . $id;
                // if (!$respon_action = cache($cache_action)) {
                // }
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
                    cache()->save($cache_dept, $respon_data, 30000);
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
                    if ($s['id'] == '1' or $s['id'] == '2') {
                        if ($s['id'] == $ws)
                            $str_pr .= "<option value='" . $s['id'] . "' selected=''>";
                        else
                            $str_pr .= "<option value='" . $s['id'] . "'>";
                        $str_pr .= $s['work_status'] . "</option>";
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
                        $str_log .= "<li><div class='card-kiri-tulisan-tiga'>'" . $log['date_created'] . "'</div></li>";
                    }
                    $str_log .= "</ul >";
                }
                $response = array(
                    "status"    => 1,
                    "csrf_hash" => csrf_hash(),
                    "str_dept" => $str_dept,
                    "str_status" => $str_pr,
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
                $id = $this->request->getVar('id');
                $tbl = $this->request->getVar('tbl');
                $id_pl = $this->request->getVar('pl_d');
                $ws = $this->request->getVar('status_work');
                $pic = $this->request->getVar('picdep');
                $con = $this->request->getVar('con');

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
                    cache()->save($cache_dept, $respon_data, 30000);
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
                    if ($s['id'] == '2' or $s['id'] == '3' or $s['id'] == '4') {
                        if ($s['id'] == $ws)
                            $str_pr3 .= "<option value='" . $s['id'] . "' selected=''>";
                        else
                            $str_pr3 .= "<option value='" . $s['id'] . "'>";
                        $str_pr3 .= $s['work_status'] . "</option>";
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
                        $str_log .= "<li><div class='card-kiri-tulisan-tiga'>'" . $log['date_created'] . "'</div></li>";
                    }
                    $str_log .= "</ul >";
                }




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










































    function list_action()
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
                $str1 = '';
                if (!$respon_data = cache('list_data')) {

                    $id = '03301393';
                    $app_key = 'w71Y9wTLfRTIj1fsRnRX';

                    $data = [
                        'id' => $id,
                        'app_key' => $app_key,
                    ];
                    $url = 'http://localhost/api_wbi/api_data/list_data';
                    $curl = curl_init($url);
                    $hasil0 = curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    $hasil = curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    $response_api = curl_exec($curl);
                    $jsonData = json_decode($response_api, true);
                    $respon_login = $jsonData['success'];
                    if ($respon_login == false) {
                        $output = array(
                            'status'    =>  0,
                            "msg"       =>  $jsonData['message'],
                        );
                        exit(json_encode($output));
                    }

                    $respon_data = $jsonData['data'];
                    curl_close($curl);
                    cache()->save('list_data', $respon_data, 300); //5 menit
                }
                foreach ($respon_data as $item) {
                    $encrypted_id = "wbi-" . $item['Equipment_ID'];
                    $tmped = "class='btn btn-xs btn-outline-info waves-purple waves-light btnRes' data-id='" . $encrypted_id . "'";
                    $Inspection_Date = $item['Inspection_Date'];
                    $newDate = date("d/m/Y", strtotime($Inspection_Date));
                    $str1 .= "<tr class='table-active' >";
                    $str1 .= "<td  class='text Column content' title=' ' >
                    <button class='btn btn-danger waves-effect waves-light kotak-satu' ></button>
                    <button class='btn btn-purple waves-effect waves-light kotak-dua' ></button>
                    <button class='btn btn-warning waves-effect waves-light kotak-tiga' ></button>
                    </td>";
                    $str1 .= "<td  class='text' title=''>" . $item['Equipment_ID'] . "</td>";
                    $str1 .= "<td  class='text' title=''>" . $newDate . "</td>";
                    $str1 .= "<td  class='text' title=''>" . $item['Condition'] . "</td>";
                    $str1 .= "<td  class='text' title=''></td>";
                    $str1 .= "<td  class=''><a href='javascript:void(0)' " . $tmped . " title='Open Case'><h7 class='mt-3 mb-0 ' ><small></small>Open Detail</h7></a></td>";
                    $str1 .= "</tr>";
                }
                $not_started = "Unresolved (" . count($respon_data) . ")";
                $cases_action = "<b>3</> cases waiting for your action";

                $response = array(
                    "status"    => 1,
                    "csrf_hash"     => csrf_hash(),
                    "str"           => $str1,
                    "not_started"    => $not_started,
                    "not_action"    => $cases_action,
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
    function open_detail()
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
                $idcomb     = $this->request->getVar("id");
                $tmp = explode('-', $idcomb);
                if (count($tmp) != 2)
                    throw new \Exception("Invalid ID");
                $kate  = $tmp[0];
                $id = $tmp[1];
                $_arr = array("wbi");
                if (!in_array($kate, $_arr))
                    throw new \Exception("InvaliD Kategori");
                // if (!is_numeric($id))
                //     throw new \Exception("Invalid ID");

                //$id = '03301393';
                $app_key = 'w71Y9wTLfRTIj1fsRnRX';

                $data = [
                    'id' => $id,
                    'app_key' => $app_key,
                ];
                $url = 'http://localhost/api_wbi/api_data/open_detail';
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $response_api = curl_exec($curl);
                $jsonData = json_decode($response_api, true);
                $respon_login = $jsonData['success'];
                if ($respon_login == false) {
                    $output = array(
                        'status'    =>  0,
                        "msg"       =>  $jsonData['message'],
                    );
                    exit(json_encode($output));
                }
                $strno = 1;
                $str = '';
                $strno2 = 1;
                $str2 = '';
                $strno3 = 1;
                $str3 = '';
                $equipmentcode = '561_FN1';
                $waitingcase = '';
                $respon_data = $jsonData['data'];
                foreach ($respon_data as $item) {

                    $originalDate = $item['schedule_updated_date'];
                    $newDate = date("d/m/Y", strtotime($originalDate));
                    $encrypted_id = "wbi-" . $item['schedule_id'];
                    $tmped = "class='btn btn-xs btn-outline-info waves-purple waves-light btnCase' data-id='" . $encrypted_id . "'";

                    if ($item['eq_erp_id'] == '001_BC1') {
                        $strno++;
                        $str .= "<tr class='table-active' >";
                        $str .= "<td  class='text Column content' title=' ' ></td>";
                        $str .= "<td  class='text' title=''>" . $newDate . "</td>";
                        $str .= "<td  class='text ' title=''>" . $item['eq_erp_id'] . "</td>";
                        $str .= "<td  class='text' title=''></td>";

                        $str .= "<td class='text-right'></td>";
                        $str .= "<td  class=''><a href='javascript:void(0)' " . $tmped . " title='Open Case'><h7 class='mt-3 mb-0 ' ><small></small>Open Case</h7></a></td>";
                        $str .= "</tr>";
                    }
                    if ($item['eq_erp_id'] == '101_TW1') {
                        $strno2++;
                        $str2 .= "<tr class='table-active' >";
                        $str2 .= "<td  class='text Column content' title=' ' ></td>";
                        $str2 .= "<td  class='text' title=''>" . $newDate . "</td>";
                        $str2 .= "<td  class='text ' title=''>" . $item['eq_erp_id'] . "</td>";
                        $str2 .= "<td  class='text' title=''></td>";
                        $str2 .= "<td class='text-right'></td>";
                        $str2 .= "<td  class=''><a href='javascript:void(0)' " . $tmped . " title='Open Case'><h7 class='mt-3 mb-0 ' ><small></small>Open Case</h7></a></td>";
                        $str2 .= "</tr>";
                    }
                    if ($item['eq_erp_id'] == '312_RE1') {
                        $strno3++;
                        $str3 .= "<tr class='table-active' >";
                        $str3 .= "<td  class='text Column content' title=' ' ></td>";
                        $str3 .= "<td  class='text' title=''>" . $newDate . "</td>";
                        $str3 .= "<td  class='text ' title=''>" . $item['eq_erp_id'] . "</td>";
                        $str3 .= "<td  class='text' title=''></td>";
                        $str3 .= "<td class='text-right'></td>";
                        $str3 .= "<td  class=''><a href='javascript:void(0)' " . $tmped . " title='Open Case'><h7 class='mt-3 mb-0 ' ><small></small>Open Case</h7></a></td>";
                        $str3 .= "</tr>";
                    }
                }
                $no1 = $strno - 1;
                $no2 = $strno2 - 1;
                $no3 = $strno3 - 1;
                $not_started = "Not Started (<a>" . $no1 . "</a>)";
                $in_progress = "In Progress (<a>" . $no2 . "</a>)";
                $completed = "Completed (<a>" . $no3 . "</a>)";
                $cases_action = "<b>3</> cases waiting for your action";
                curl_close($curl);
                $angka = 3;
                $waitingcase = "<b>" . $angka . "</b> cases waiting for your action";
                $response = array(
                    "status"    => 1,
                    "csrf_hash"     => csrf_hash(),
                    "not_started"         => $not_started,
                    "str"           => $str,
                    "in_progress"         => $in_progress,
                    "str_2"         => $str2,
                    "completed"         => $completed,
                    "str_3"         => $str3,

                    "eq_code"       => $equipmentcode,
                    "waitingcase"   => $waitingcase,
                );

                //list Data

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
    function listbackup()
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


                // $nik = '03301393';
                // $password = '123456';
                // $app_key = 'w71Y9wTLfRTIj1fsRnRX';

                // $data = [
                //     'nik' => $nik,
                //     'password' => $password,
                //     'app_key' => $app_key,
                // ];
                // $url = 'https://report-id.online/dais_sso_new/public/api/login';
                // $curl = curl_init($url);
                // $hasil0 = curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                // $hasil = curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                // $response_api = curl_exec($curl);

                // $jsonData = json_decode($response_api, true);


                // $duser = $jsonData['user']['nik'];

                //list Data

                $id = $this->request->getVar('id');
                if ($id == '')
                    $sql = "SELECT CM.*, MC.`checkpoint_name`, CL.checklist_desc
                    FROM checklist_mapping CM
                    LEFT JOIN `master_checkpoint` MC ON CM.`checkpoint_id`=MC.`checkpoint_id`
                    LEFT JOIN `master_checklist` CL ON CM.checklist_id=CL.checklist_id
                    WHERE 1=1
                     LIMIT 5";
                else
                    $sql = "SELECT CM.*, MC.`checkpoint_name`, CL.checklist_desc
                    FROM checklist_mapping CM
                    LEFT JOIN `master_checkpoint` MC ON CM.`checkpoint_id`=MC.`checkpoint_id`
                    LEFT JOIN `master_checklist` CL ON CM.checklist_id=CL.checklist_id
                    WHERE 1=1 
                    LIMIT 3";

                $list_data = $this->db->query($sql);
                if (!$list_data) {
                    $msg = $_SESSION['id'] . " " . $this->router->fetch_class() . " : " . $this->db->error()["message"];
                    $msg = '1';
                    log_message("error1", $msg);
                    throw new \Exception("Invalid SQL!");
                }
                if ($list_data->resultID->num_rows == 0)
                    $str = "<tr><td colspan='8'>Data tidak ditemukan</td></tr>";

                $no = 1;
                $str = '';
                // $not_started = "Not Started (5)";
                $not_started = "Not Started (" . $list_data->resultID->num_rows . ")";
                $cases_action = "<b>3</> cases waiting for your action";
                foreach ($list_data->getResult() as $v) {
                    $originalDate = $v->updated_date;
                    $newDate = date("d/m/Y", strtotime($originalDate));


                    $idcomb = "wbi-" . $v->mapping_id;
                    $encrypted_id = "wbi-" . $v->mapping_id;
                    //$encrypted_id = base64_encode(openssl_encrypt($idcomb, "AES-128-ECB", ENCRYPT_PASS));
                    //$encrypted_id = $encrypter->encrypt($idcomb);

                    $tmped = "class='btn btn-xs btn-outline-info waves-purple waves-light btnRes' data-id='" . $encrypted_id . "'";

                    $str .= "<tr class='table-active' >";
                    // $str .= "<td  class='text' title=' ' ><p class='badge badge-warning'>" . $v->section_id . "</p></td>";
                    $str .= "<td  class='text Column content' title=' ' ><button type='button' class='btn btn-pink btn-xs' disabled=''>" . $v->section_id . "</button> </td>";
                    $str .= "<td  class='text' title=''>" . $newDate . "</td>";
                    $str .= "<td  class='text ' title=''>" . $v->eq_erp_id . "</td>";
                    $str .= "<td  class='text' title=''>" . $v->checkpoint_name . "</td>";

                    $str .= "<td class='text-right'></td>";
                    //$str .= "<td  class='text' title=''></td>";
                    $str .= "<td  class=''><a href='javascript:void(0)' " . $tmped . " title='Open Case'><h7 class='mt-3 mb-0 ' ><small></small>Open Case</h7></a></td>";
                    $str .= "</tr>";
                }
                //curl_close($curl);
                $response = array(
                    "status"    => 1,
                    "csrf_hash" => csrf_hash(),
                    "str"       => $str,
                    "not_started"    => $not_started,
                    "not_action"    => $cases_action,
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
    function list_action_backup()
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


                // $nik = '03301393';
                // $password = '123456';
                // $app_key = 'w71Y9wTLfRTIj1fsRnRX';

                // $data = [
                //     'nik' => $nik,
                //     'password' => $password,
                //     'app_key' => $app_key,
                // ];
                // $url = 'https://report-id.online/dais_sso_new/public/api/login';
                // $curl = curl_init($url);
                // $hasil0 = curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                // $hasil = curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                // $response_api = curl_exec($curl);

                // $jsonData = json_decode($response_api, true);


                // $duser = $jsonData['user']['nik'];

                //list Data

                $id = $this->request->getVar('id');

                $sql = "SELECT A.schedule_id AS 'SCHEDULE ID', A.schedule_date AS 'Schedule Date', A.patrol_date AS 'Inspection_Date', H.plant_name AS 'Plant', P.section_name 'Section', A.shift_id AS 'Shift',
                A.patroller_id, A.eq_erp_id AS 'Equipment_ID', B.eq_desc AS 'Equipment Name', K.checkpoint_name AS 'Checkpoint', L.checklist_desc AS 'Checkpoint Detail',
                M.option_name AS 'Condition', N.option_name AS 'Cleanliness', I.checklist_notes AS 'Checklist Notes',
                CASE 
                    WHEN Q.schedule_id IS NOT NULL THEN CONCAT('https://report-id.online/api_patrol_prod/checklist/image_proof?schedule_id=',A.schedule_id,'&mapping_id=',J.mapping_id)
                    ELSE NULL
                END AS'Image Proof',
                E.status_name AS 'Patrol Status', F.status_name AS 'End Shift Status', A.end_shift_reason AS 'End Shift Reason',
                A.completed_at_latitude, A.completed_at_longitude,
                CONCAT_WS('', '', A.completed_at_latitude, A.completed_at_longitude) AS patrol_location
                FROM schedules A
                LEFT JOIN master_equipment B ON A.eq_erp_id = B.eq_erp_id AND A.plant_dept = B.dept
                LEFT JOIN master_shift D ON A.shift_id = D.shift_id
                LEFT JOIN master_status E ON A.patrol_status = E.status_id
                LEFT JOIN master_status F ON A.end_shift_status = F.status_id
                LEFT JOIN (SELECT * FROM master_plant ) H ON A.plant_dept = H.dept
                LEFT JOIN (SELECT DISTINCT section_id, eq_erp_id, plant_dept FROM checklist_mapping) O ON A.eq_erp_id = O.eq_erp_id AND A.plant_dept = O.plant_dept
                LEFT JOIN master_section P ON O.section_id = P.section_id
                LEFT JOIN schedule_answer I ON A.schedule_id = I.schedule_id
                LEFT JOIN checklist_mapping J ON I.checklist_mapping_id = J.mapping_id
                LEFT JOIN master_checkpoint K ON J.checkpoint_id = K.checkpoint_id
                LEFT JOIN master_checklist L ON J.checklist_id = L.checklist_id
                LEFT JOIN checklist_option M ON I.option_id_condition = M.checklist_option_id
                LEFT JOIN checklist_option N ON I.option_id_cleanliness = N.checklist_option_id
                LEFT JOIN (SELECT DISTINCT schedule_id, checklist_mapping_id FROM image_uploaded) Q ON Q.schedule_id = A.schedule_id AND Q.checklist_mapping_id = J.mapping_id
                    WHERE A.schedule_date = '2022-06-30'
                    GROUP BY A.eq_erp_id
                        ORDER BY A.schedule_id, A.eq_erp_id, I.schedule_answer_id";

                $list_data = $this->db->query($sql);
                if (!$list_data) {
                    $msg = $_SESSION['id'] . " " . $this->router->fetch_class() . " : " . $this->db->error()["message"];
                    $msg = '1';
                    log_message("error1", $msg);
                    throw new \Exception("Invalid SQL!");
                }
                if ($list_data->resultID->num_rows == 0)
                    $str = "<tr><td colspan='8'>Data tidak ditemukan</td></tr>";

                $no = 1;
                $str = '';
                // $not_started = "Not Started (5)";
                $not_started = "Unresolved (" . $list_data->resultID->num_rows . ")";
                $cases_action = "<b>3</> cases waiting for your action";
                foreach ($list_data->getResult() as $v) {
                    $Inspection_Date = $v->Inspection_Date;
                    $newDate = date("d/m/Y", strtotime($Inspection_Date));

                    $idcomb = "wbi-" . $v->Equipment_ID;
                    $encrypted_id = "wbi-" . $v->Equipment_ID;

                    $tmped = "class='btn btn-xs btn-outline-info waves-purple waves-light btnRes' data-id='" . $encrypted_id . "'";

                    $str .= "<tr class='table-active' >";
                    $str .= "<td  class='text Column content' title=' ' >
                    <button class='btn btn-danger waves-effect waves-light kotak-satu' ></button>
                    <button class='btn btn-purple waves-effect waves-light kotak-dua' ></button>
                    <button class='btn btn-warning waves-effect waves-light kotak-tiga' ></button>

                    </td>";
                    $str .= "<td  class='text' title=''>" . $v->Equipment_ID . "</td>";
                    $str .= "<td  class='text' title=''>" . $newDate . "</td>";
                    //$str .= "<td  class='text ' title=''>" . $v->eq_erp_id . "</td>";
                    $str .= "<td  class='text' title=''>" . $v->Condition . "</td>";

                    $str .= "<td class='text-right'></td>";
                    //$str .= "<td  class='text' title=''></td>";
                    $str .= "<td  class=''><a href='javascript:void(0)' " . $tmped . " title='Open Case'><h7 class='mt-3 mb-0 ' ><small></small>Open Detail</h7></a></td>";
                    $str .= "</tr>";
                }
                //curl_close($curl);
                $response = array(
                    "status"    => 1,
                    "csrf_hash"     => csrf_hash(),
                    "str"           => $str,
                    "not_started"    => $not_started,
                    "not_action"    => $cases_action,
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
