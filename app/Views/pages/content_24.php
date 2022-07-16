<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="row  _win">
    <div class="col-lg-12">
        <div class="card-box _t_report">

            <div class="row">
                <div class="col-sm-6">
                    <div class="float-left">
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <div style="display: flex;">
                                    <div class="card tombol-aktif _nots" id="_nots">
                                        <button type="button" class="btn b-tombol-aktif b-tombol-font nots" id="nots">Unassigned (<a>0</a>)</button>

                                    </div>
                                    <div class="card tombol-tidakaktif _prog" id="_prog">
                                        <button type="button" class="btn b-tombol-tidak-aktif b-tombol-font prog" id="prog">Pending Action (<a>0</a>)</button>
                                    </div>
                                    <div class="card tombol-tidakaktif _comp" id="_comp">
                                        <button type="button" class="btn b-tombol-tidak-aktif b-tombol-font comp" id="comp">In Progress (<a>0</a>)</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="float-right">
                        <div class="form-group row">
                            <div class="col-lg-12">

                                <div style="display: flex;">
                                    <div class="card tombol-tidakaktif ">
                                        <div class="btn b-tombol-tidak-aktif">Select Plant : </div>
                                    </div>
                                    <div class="card tombol-tidakaktif " style="width: 175px;">
                                        <select class="form-control input-sm " data-toggle="select2" data-placeholder="Select here" id="idplant" name="idplant" readonly="" style="font-family: 'Inter';">
                                        </select>
                                    </div>
                                    <div class="card tombol-tidakaktif ">
                                        <button type="button" class="btn  b-tombol-tidak-aktif b-tombol-font hist">Case History <i class="mdi mdi-history"></i></button>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive _not_started">
                <table class="table table-striped mb-0" id="t_waiting">
                    <thead>
                        <tr class="font-tabel-tr">
                            <th>Condition<i></i></th>
                            <th>Inspection Date</th>
                            <th>Equipment Id </th>
                            <th>Checkpoint</th>
                            <th>Checkpoint Detail</th>
                            <th>Checklist Notes</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

            <div class="table-responsive _t_in_progress" style="display:none">
                <table class="table table-striped mb-0" id="t_in_progress">
                    <thead>
                        <tr class="font-tabel-tr">
                            <th>Condition</th>
                            <th>Inspection Date</th>
                            <th>Equipment Id </th>
                            <th>Checkpoint</th>
                            <th>Checkpoint Details</th>
                            <th>Work Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

            <div class="table-responsive _t_completed" style="display:none">
                <table class="table table-striped mb-0" id="t_need_riview">
                    <thead>
                        <tr class="font-tabel-tr">
                            <th>Condition</th>
                            <th>Inspection Date</th>
                            <th>Equipment Id</th>
                            <th>Checkpoint</th>
                            <th>Checkpoint Details</th>
                            <th>Work Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

        </div>

        <div class="card-box _t_casehistory" style="display:none">
            <div class="row">
                <div class="col-sm-6">
                    <div class="float-left">
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <div style="display: flex;">
                                    <div class="card tombol-tidakaktif">
                                        <button type="button" class="btn b-tombol-tidak-aktif b-tombol-font back_r"><i class=" mdi mdi-keyboard-backspace "></i> Case History</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="table-responsive ">
                <table class="table table-striped mb-0" id="t_casehistory">
                    <thead>
                        <tr class="font-tabel-tr">
                            <th>Condition</th>
                            <th>Inspection Date</th>
                            <th>Equipment ID</th>
                            <th>Checkpoint</th>
                            <th>Checkpoint Details</th>
                            <th>Work Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
<!-- 
    Unassigned
    form action 
-->
<form id="form_add2">

    <div id="modal_add2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-full modal-dialog-form">
            <div class="modal-content">

                <div class="modal-body" style="height: 52px;">
                    <a type="button" class="close btnX" id="btnX" name="btnX">×</a>
                    <h5 class=" text-center text-form-judul">Action Form</h5>
                    <p class="text-center lead text-form-sub-judul">Complete Form to Update this case
                    </p>
                </div>

                <div class="modal-body">

                    <div class="container-fluid">

                        <div class="row">

                            <div class="col-lg-3">

                                <div class="card-header card-kiri-satu">
                                    <div class="col-sm-6 col-lg-4 col-xl-12 tulisan-g" style="  height: 17px;color: #535687;">
                                        <i class="ion ion-md-alert"></i> General Information
                                    </div>
                                    </br>
                                    <div class="col-xl-12">
                                        <ul class="list-unstyled mb-0">
                                            <li class="card-kiri-tulisan-satu">Condition</li>
                                            <li>
                                                <div class="" id="kon"></div>
                                            </li>

                                            <li class="card-kiri-tulisan-satu">Inspection Date</li>
                                            <li class="card-kiri-tulisan-tiga" id="tgl">xx/xx/xxxx
                                            </li>

                                            <li class="card-kiri-tulisan-satu">Equipment Code</li>
                                            <li class="card-kiri-tulisan-tiga" id="ec">..</li>

                                            <li class="card-kiri-tulisan-satu">Checkpoint</li>
                                            <li class="card-kiri-tulisan-tiga" id="cp">..</li>

                                            <li class="card-kiri-tulisan-satu">Checkpoint Detail</li>
                                            <li class="card-kiri-tulisan-tiga" id="cd">..</li>
                                            </Br>
                                            <li class="card-kiri-tulisan-satu">Checklist Notes</li>
                                            <li class="card-kiri-tulisan-tiga" id="cn">..</li>
                                        </ul>
                                    </div>

                                </div>
                                </Br>
                                <div class="card-header card-kiri-dua">
                                    <div class="col-sm-6 col-lg-4 col-xl-12">
                                        <div class="card-kiri-dua-tulisan-satu"><i class=" mdi mdi-chart-timeline-variant "></i> Status History
                                        </div>
                                        </br>
                                        <div id="sh_1">
                                        </div>

                                    </div>
                                    </br>
                                </div>
                            </div>

                            <div class="col-lg-9">
                                <div class="card-kanan-satu">
                                    <div class="row">

                                        <div class="col-lg-6 card-kanan-dua">


                                            <div class="">

                                                <div class="card-body">
                                                    <input type="hidden" id="cdn" name="cdn" value="" readonly='' />
                                                    <input type="hidden" id="ind" name="ind" value="" readonly='' />
                                                    <input type="hidden" class="idw" id="idw" name="idw" value="" readonly='' />
                                                    <input type="hidden" class="ckt" id="ckt" name="ckt" value="" readonly='' />
                                                    <input type="hidden" class="" id="ckt_d" name="ckt_d" value="" />
                                                    <input type="hidden" class="" id="plant" name="plant" value="" />
                                                    <input type="hidden" class="" id="plant_d" name="plant_d" value="" />
                                                    <input type="hidden" class="" id="note_c" name="note_c" value="" />

                                                    <div class="row">
                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Departemen PIC<span class="text-danger"> *</span></label>
                                                                <select class="form-control input-sm required" data-toggle="select2" data-placeholder="Select here" id="icdepp" name="icdepp" readonly="" style="font-family: 'Inter';">
                                                                </select>
                                                            </div>

                                                        </div>


                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Email PIC<span class="text-danger"> *</span></label>
                                                                <div class="input_fields_wrap">
                                                                    <div class="input_fields_wrap1" style="height: 42px;">

                                                                        <input type="email" id="1addm" name="1addm" autocomplete=" current-password" placeholder="Input email" class="int-pass int-text ">
                                                                        <!-- <i class=" ion ion-md-close  pUlang" style="margin-left: -30px; cursor: pointer;"></i> -->
                                                                        </Br>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <!-- <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <div class="input_fields_wrap1">
                                                                    <button type="button" class="btn b-tombol-tidak-aktif b-tombol-font add_field_button"><i class="ion ion-md-add-circle-outline"></i> Add More Email</button>

                                                                </div>
                                                            </div>
                                                        </div> -->


                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Problem Detail<span class="text-danger"> *</span></label>
                                                                <textarea class="form-control" rows="3" id="detpro" name="detpro" placeholder="add description      "></textarea>

                                                            </div>
                                                        </div>

                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Required Action<span class="text-danger"> *</span></label>
                                                                <textarea class="form-control" rows="3" id="maint" name="maint" placeholder="add description"></textarea>

                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-lg-6 card-kanan-tiga">
                                            <div class="">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Request Date<span class="text-danger"> *</span></label>
                                                                <div class="input-group">

                                                                    <input type="text" class="form-control " placeholder="" data-provide="" id="req_date" name="req_date" readonly=''>
                                                                    <!-- <input type="text" class="form-control" id="req_tm" name="req_tm" readonly=''> -->
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Target Completion Date<span class="text-danger"> *</span></label>
                                                                <div class="input-group">

                                                                    <input type="text" class="form-control  inp_sd" placeholder="Select Date" data-provide="datepicker" id="inp_sd" name="inp_sd" readonly=''>
                                                                    <!-- <input type="text" class="form-control" id="sd_time" name="sd_time" readonly=''> -->
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <!-- <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">End Date<span class="text-danger"> *</span></label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control inp_ed" id="inp_ed" name="inp_ed" placeholder="Select Date" data-provide="datepicker" readonly=''>

                                                                    <input type="text" class="form-control" id="en_time" name="en_time" readonly=''>
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> -->

                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Work Status<span class="text-danger"> *</span></label>
                                                                <select class="form-control" data-toggle="select2" data-placeholder="Select here" id="sttwork" name="sttwork">
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-12 date_status" style="display:none">
                                                            <div class="form-group">
                                                                <label class="t-action" id="Date_sparepart">Status Update Date<span class="text-danger"> *</span></label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" placeholder="Select Date" id="sud" name="sud" data-provide="" readonly=''>

                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-12 area_select" style="display:none">
                                                            <div class="form-group">
                                                                <label class="t-action" id='input_detail'>Spare Part Detail <span class="text-danger"> </span></label>
                                                                <textarea class="form-control" rows="3" id="area_select" name="area_select" placeholder="add description"></textarea>

                                                            </div>
                                                        </div>

                                                        <div class="col-xl-12 date_arrived" style="display:none">
                                                            <div class="form-group">
                                                                <label class="t-action" id="Date_sparepart">Spare Part Arrived (ETA)<span class="text-danger"> </span></label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" placeholder="Select Date" id="d_sel" name="d_sel" data-provide="datepicker" readonly=''>
                                                                    <!-- <input type="text" class="form-control" id="spa" name="spa" readonly=''> -->
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 card-kanan-tiga">
                                            <div class="col-lg-12">
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btnUpdate" data-dismiss="modal">&nbsp;Cancel</button>
                                                    <button type="submit" class="btn btnUpdate-aktif" id="btnKirim">&nbsp;Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-12">

                            </div>

                        </div>
                        <div class="row">

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

</form>

<!-- pending action -->
<form id="form_add3">
    <div id="modal_add3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-full modal-dialog-form">
            <div class="modal-content">

                <div class="modal-body" style="height: 52px;">
                    <a type="button" class="close btnX" id="btnX" name="btnX">×</a>
                    <h5 class=" text-center text-form-judul">Action Form</h5>
                    <p class="text-center lead text-form-sub-judul">Complete Form to Update this case
                    </p>
                </div>

                <div class="modal-body">

                    <div class="container-fluid">

                        <div class="row">

                            <div class="col-lg-3">

                                <div class="card-header card-kiri-satu">
                                    <div class="col-sm-6 col-lg-4 col-xl-12 tulisan-g" style="  height: 17px;color: #535687;">
                                        <i class="ion ion-md-alert"></i> General Information
                                    </div>
                                    </br>
                                    <div class="col-xl-12">
                                        <ul class="list-unstyled mb-0">
                                            <li class="card-kiri-tulisan-satu">Condition</li>
                                            <li>
                                                <div class="" id="kon">xxxxxxxxxx</div>
                                            </li>
                                            <li class="card-kiri-tulisan-satu">Inspection Date</li>
                                            <li class="card-kiri-tulisan-tiga" id="tgl">xx/xx/xxxx
                                            </li>
                                            <li class="card-kiri-tulisan-satu">Equipment Code</li>
                                            <li class="card-kiri-tulisan-tiga" id="ec">..</li>
                                            <li class="card-kiri-tulisan-satu">Checkpoint</li>
                                            <li class="card-kiri-tulisan-tiga" id="cp">..</li>
                                            <li class="card-kiri-tulisan-satu">Checkpoint Detail</li>
                                            <li class="card-kiri-tulisan-tiga" id="cd">..</li>
                                            </Br>
                                            <li class="card-kiri-tulisan-satu">Checklist Notes</li>
                                            <li class="card-kiri-tulisan-tiga" id="cn">..</li>
                                        </ul>
                                    </div>

                                </div>
                                </Br>
                                <div class="card-header card-kiri-dua">
                                    <div class="col-sm-6 col-lg-4 col-xl-12">
                                        <div class="card-kiri-dua-tulisan-satu"><i class=" mdi mdi-chart-timeline-variant "></i> Status History
                                        </div>
                                        </br>
                                        <div id="sh">
                                            <!-- <ul class="list-unstyled mb-0">
                                                <li class="">Condition</Br>xxxxxxxxxx</li>
                                                <li>
                                                    xxxxxxxxxx</Br>xxxxxxxxxx
                                                </li>
                                            </ul> -->
                                        </div>
                                    </div>
                                    </br>
                                </div>
                            </div>

                            <div class="col-lg-9">
                                <div class="card-kanan-satu">
                                    <div class="row">
                                        <div class="col-lg-6 card-kanan-dua">
                                            <div class="">
                                                <div class="card-body">
                                                    <input type="hidden" id="cdn2" name="cdn2" value="" readonly='' />
                                                    <input type="hidden" id="ind2" name="ind2" value="" readonly='' />
                                                    <input type="hidden" class="" id="idpending" name="idpending" value="" />
                                                    <input type="hidden" class="idw2" id="idw2" name="idw2" value="" />
                                                    <input type="hidden" class="ckt2" id="ckt2" name="ckt2" value="" />
                                                    <input type="hidden" class="" id="ckt_d2" name="ckt_d2" value="" />
                                                    <input type="hidden" class="" id="nmplant" name="nmplant" value="" />
                                                    <input type="hidden" class="" id="plt" name="plt" value="" />
                                                    <div class="row">
                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Departemen PIC<span class="text-danger"> *</span></label>
                                                                <select class="form-control input-sm required" data-toggle="select2" data-placeholder="Select here" id="dep_pic" name="dep_pic" readonly="" style="font-family: 'Inter';" disabled>
                                                                </select>
                                                            </div>
                                                        </div>


                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Email PIC<span class="text-danger"> *</span></label>
                                                                <div class="input_fields_wrap">
                                                                    <div class="input_fields_wrap1" style="height: 42px;">

                                                                        <input type="email" id="1addm2" name="1addm2" autocomplete=" current-password" placeholder="Input email" class="int-pass int-text " disabled>
                                                                        </Br>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <!-- <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <div class="input_fields_wrap1">
                                                                    <button type="button" class="btn b-tombol-tidak-aktif b-tombol-font add_field_button"><i class="ion ion-md-add-circle-outline"></i> Add More Email</button>

                                                                </div>
                                                            </div>
                                                        </div> -->


                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Problem Detail<span class="text-danger"> *</span></label>
                                                                <textarea class="form-control" rows="3" id="detpro2" name="detpro2" placeholder="add description" disabled></textarea>

                                                            </div>
                                                        </div>

                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Required Action<span class="text-danger"> *</span></label>
                                                                <textarea class="form-control" rows="3" id="maint2" name="maint2" placeholder="add description" disabled></textarea>

                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-lg-6 card-kanan-tiga">
                                            <div class="">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Request Date<span class="text-danger"> *</span></label>
                                                                <div class="input-group">

                                                                    <input type="text" class="form-control  req_dt2" placeholder="Select Date" data-provide="" id="req_dt2" name="req_dt2" readonly=''>

                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Target Completion Date<span class="text-danger"> *</span></label>
                                                                <div class="input-group">

                                                                    <input type="text" class="form-control" placeholder="Select Date" data-provide="datepicker" id="pa_inp_sd" name="pa_inp_sd" readonly='' disabled>
                                                                    <input type="text" class="form-control" id="pa_sd_time" name="pa_sd_time" readonly='' disabled>
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>


                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Work Status<span class="text-danger"> *</span></label>
                                                                <select class="form-control" data-toggle="" data-placeholder="Select here" id="pa_work_s" name="pa_work_s">
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-12 date_status" style="display:none">
                                                            <div class="form-group">
                                                                <label class="t-action" id="Date_sparepart">Status Update Date<span class="text-danger"> </span>*</label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" placeholder="Select Date" id="sud2" name="sud2" data-provide="" readonly=''>

                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-12 sp_detail" style="display:none">
                                                            <div class="form-group">
                                                                <label class="t-action">Spare Part Detail <span class="text-danger"> </span></label>
                                                                <textarea class="form-control" rows="3" id="apa_spd2" name="apa_spd2" placeholder="add description"></textarea>

                                                            </div>
                                                        </div>

                                                        <div class="col-xl-12 date_arrived" style="display:none">
                                                            <div class="form-group">
                                                                <label class="t-action" id="Date_sparepart">Spare Part Arrived (ETA)<span class="text-danger"> </span></label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" placeholder="Select Date" id="pa_spa2" name="pa_spa2" data-provide="datepicker" readonly=''>
                                                                    <!-- <input type="text" class="form-control" id="spa" name="spa" readonly=''> -->
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 card-kanan-tiga">
                                            <div class="col-lg-12">
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btnUpdate" data-dismiss="modal">&nbsp;Cancel</button>
                                                    <button type="submit" class="btn btnUpdate-aktif" id="btnKirim">&nbsp;Update Case</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-12">

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

</form>
<!-- In Progress -->
<form id="form_add4">
    <div id="modal_add4" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-full modal-dialog-form">
            <div class="modal-content">

                <div class="modal-body" style="height: 52px;">
                    <a type="button" class="close btnX" id="btnX" name="btnX">×</a>
                    <h5 class=" text-center text-form-judul">Action Form</h5>
                    <p class="text-center lead text-form-sub-judul">Complete Form to Update this case
                    </p>
                </div>

                <div class="modal-body">

                    <div class="container-fluid">

                        <div class="row">

                            <div class="col-lg-3">

                                <div class="card-header card-kiri-satu">
                                    <div class="col-sm-6 col-lg-4 col-xl-12 tulisan-g" style="  height: 17px;color: #535687;">
                                        <i class="ion ion-md-alert"></i> General Information
                                    </div>
                                    </br>
                                    <div class="col-xl-12">
                                        <ul class="list-unstyled mb-0">
                                            <li class="card-kiri-tulisan-satu">Condition</li>
                                            <li>
                                                <div class="" id="kon3">xxxxxxxxxx</div>
                                            </li>
                                            <li class="card-kiri-tulisan-satu">Inspection Date</li>
                                            <li class="card-kiri-tulisan-tiga" id="tgl3">xx/xx/xxxx
                                            </li>
                                            <li class="card-kiri-tulisan-satu">Equipment Code</li>
                                            <li class="card-kiri-tulisan-tiga" id="ec3">..</li>
                                            <li class="card-kiri-tulisan-satu">Checkpoint</li>
                                            <li class="card-kiri-tulisan-tiga" id="cp3">..</li>
                                            <li class="card-kiri-tulisan-satu">Checkpoint Detail</li>
                                            <li class="card-kiri-tulisan-tiga" id="cd3">..</li>
                                            </Br>
                                            <li class="card-kiri-tulisan-satu">Checklist Notes</li>
                                            <li class="card-kiri-tulisan-tiga" id="cn3">..</li>
                                        </ul>
                                    </div>

                                </div>
                                </Br>
                                <div class="card-header card-kiri-dua">
                                    <div class="col-sm-6 col-lg-4 col-xl-12">
                                        <div class="card-kiri-dua-tulisan-satu"><i class=" mdi mdi-chart-timeline-variant "></i> Status History
                                        </div>
                                        </br>
                                        <div id="sh_3">
                                        </div>
                                    </div>
                                    </br>
                                </div>
                            </div>

                            <div class="col-lg-9">
                                <div class="card-kanan-satu">
                                    <div class="row">
                                        <div class="col-lg-6 card-kanan-dua">
                                            <div class="">
                                                <div class="card-body">
                                                    <input type="hidden" id="idpending3" name="idpending3" value="" />
                                                    <input type="hidden" id="cdn3" name="cdn3" value="" readonly='' />
                                                    <input type="hidden" id="ind3" name="ind3" value="" readonly='' />
                                                    <input type="hidden" class="" id="idip" name="idip" value="" />
                                                    <input type="hidden" class="" id="idw3" name="idw3" value="" />
                                                    <input type="hidden" id="ckt3" name="ckt3" value="" />
                                                    <input type="hidden" id="ckt_d3" name="ckt_d3" value="" />
                                                    <input type="hidden" id="nmplant3" name="nmplant3" value="" />
                                                    <input type="hidden" id="plt3" name="plt3" value="" />
                                                    <div class="row">
                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Departemen PIC<span class="text-danger"> *</span></label>
                                                                <select class="form-control input-sm required" data-toggle="select2" data-placeholder="Select here" id="dep_pic3" name="dep_pic3" readonly="" style="font-family: 'Inter';" disabled>
                                                                </select>
                                                                <!-- <input type="text" class="form-control  icdepp" id="icdepp" name="icdepp" readonly=''> -->
                                                            </div>

                                                        </div>


                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Email PIC<span class="text-danger"> *</span></label>
                                                                <div class="input_fields_wrap">
                                                                    <div class="input_fields_wrap1" style="height: 42px;">

                                                                        <input type="email" id="1addm3" name="1addm3" autocomplete=" current-password" placeholder="Input email" class="int-pass int-text ">
                                                                        </Br>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <!-- <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <div class="input_fields_wrap1">
                                                                    <button type="button" class="btn b-tombol-tidak-aktif b-tombol-font add_field_button"><i class="ion ion-md-add-circle-outline"></i> Add More Email</button>

                                                                </div>
                                                            </div>
                                                        </div> -->


                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Problem Detail<span class="text-danger"> *</span></label>
                                                                <textarea class="form-control" rows="3" id="detpro3" name="detpro3" placeholder="add description" disabled></textarea>

                                                            </div>
                                                        </div>

                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Required Action<span class="text-danger"> *</span></label>
                                                                <textarea class="form-control" rows="3" id="maint3" name="maint3" placeholder="add description" disabled></textarea>

                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-lg-6 card-kanan-tiga">
                                            <div class="">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Request Date<span class="text-danger"> *</span></label>
                                                                <div class="input-group">

                                                                    <input type="text" class="form-control  req_dt3" placeholder="Select Date" data-provide="" id="req_dt3" name="req_dt3" readonly=''>

                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Target Completion Date<span class="text-danger"> *</span></label>
                                                                <div class="input-group">

                                                                    <input type="text" class="form-control" placeholder="Select Date" data-provide="datepicker" id="pa_inp_sd3" name="pa_inp_sd3" readonly='' disabled>
                                                                    <input type="text" class="form-control" id="pa_sd_time3" name="pa_sd_time3" readonly='' disabled>
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Work Status<span class="text-danger"> *</span></label>
                                                                <select class="form-control" data-toggle="" data-placeholder="Select here" id="pa_work_s3" name="pa_work_s3">
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-12 sts_ud" style="display:none">
                                                            <div class="form-group">
                                                                <label class="t-action">Status Update Date<span class="text-danger"> *</span></label>
                                                                <div class="input-group">

                                                                    <input type="text" class="form-control " placeholder="Select Date" data-provide="" id="s_ud3" name="s_ud3" readonly=''>

                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-12 part_detail3">
                                                            <div class="form-group">
                                                                <label class="t-action">Spare Part Detail <span class="text-danger"> </span></label>
                                                                <textarea class="form-control" rows="3" id="apa_spd3" name="apa_spd3" placeholder="add description"></textarea>

                                                            </div>
                                                        </div>

                                                        <div class="col-xl-12 date_arrived3">
                                                            <div class="form-group">
                                                                <label class="t-action" id="Date_sparepart">Spare Part Arrived (ETA)<span class="text-danger"> </span></label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" placeholder="Select Date" id="pa_spa3" name="pa_spa3" data-provide="datepicker" readonly=''>
                                                                    <!-- <input type="text" class="form-control" id="spa" name="spa" readonly=''> -->
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 card-kanan-tiga">
                                            <div class="col-lg-12">
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btnUpdate" data-dismiss="modal">&nbsp;Cancel</button>
                                                    <button type="submit" class="btn btnUpdate-aktif" id="btnKirim">&nbsp;Update Case</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-12">

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

</form>

<!-- Popup detail History -->
<form id="form_history">

    <div id="modal_history" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-full">
            <div class="modal-content">

                <div class="modal-body" style="height: 52px;">
                    <a type="button" class="close btnX" id="btnX" name="btnX">×</a>
                    <h5 class=" text-center text-form-judul">Detail Report</h5>
                    <p class="text-center lead text-form-sub-judul">Complete Form to Update this case
                    </p>
                    </Br></Br>
                </div>


                <div class="modal-body">

                    <div class="container-fluid">

                        <div class="row">

                            <div class="col-lg-3">

                                <div class="card-header" style="margin-top: 15px;">
                                    <div class="col-sm-6 col-lg-4 col-xl-12 tulisan-g" style="  height: 17px;color: #535687;">
                                        <i class="ion ion-md-alert"></i> General Information
                                    </div>
                                    </br>
                                    <div class="col-xl-12">
                                        <ul class="list-unstyled mb-0">
                                            <li class="card-kiri-tulisan-satu">Condition</li>
                                            <li id="">
                                                <div class="" id="con_h">

                                                </div>
                                            </li>
                                            <li class="card-kiri-tulisan-satu">Inspection Date</li>
                                            <li class="card-kiri-tulisan-tiga" id="ind_h">xx/xx/xxxx
                                            </li>

                                            <li class="card-kiri-tulisan-satu">Equipment Code</li>
                                            <li class="card-kiri-tulisan-tiga" id="ec">..</li>

                                            <li class="card-kiri-tulisan-satu">Checkpoint</li>
                                            <li class="card-kiri-tulisan-tiga" id="cp_h">..</li>

                                            <li class="card-kiri-tulisan-satu">Checkpoint Detail</li>
                                            <li class="card-kiri-tulisan-tiga" id="cpd_h">..</li>
                                            </Br>
                                            <li class="card-kiri-tulisan-satu">Checklist Notes</li>
                                            <li class="card-kiri-tulisan-tiga" id="cn_h">..</li>
                                        </ul>
                                    </div>

                                </div>
                                </Br>

                            </div>

                            <div class="col-lg-4">
                                <div class="card-header " style="margin-top: 15px;">


                                    <div class="col-xl-12">
                                        <ul class="list-unstyled mb-0">
                                            <li class="card-kiri-tulisan-satu">Department PIC</li>
                                            <li class="card-kiri-tulisan-tiga" id="dp_h2">
                                                xx-xx
                                            </li>

                                            <li class="card-kiri-tulisan-satu">Email PIC</li>
                                            <li class="card-kiri-tulisan-tiga" id="eml_h2">xx/xx/xxxx
                                            </li>

                                            <li class="card-kiri-tulisan-satu">Problem Detail</li>
                                            <li class="card-kiri-tulisan-tiga" id="prd">..</li>

                                            <li class="card-kiri-tulisan-satu">Required Action</li>
                                            <li class="card-kiri-tulisan-tiga" id="mp_h2">..</li>

                                            <li class="card-kiri-tulisan-satu">Request Date</li>
                                            <li class="card-kiri-tulisan-tiga" id="rd_h">..</li>

                                            <li class="card-kiri-tulisan-satu"> Target Completion Date</li>
                                            <li class="card-kiri-tulisan-tiga" id="tcd_h">..</li>
                                            <li class="card-kiri-tulisan-satu"> Spare Part Detail</li>
                                            <li class="card-kiri-tulisan-tiga" id="spd_h">..</li>
                                            <li class="card-kiri-tulisan-satu"> Spare Part Arrived (ETA)</li>
                                            <li class="card-kiri-tulisan-tiga" id="spa_h">..</li>
                                        </ul>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-5">


                                <div class="card-header " style="margin-top: 15px;">
                                    <p class="text-center" style="font-family: 'Inter';">Work Status History</p>


                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card">

                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="table-responsive">
                                                                <table class="table mb-0" id="t_history">
                                                                    <thead style="font-family: 'Inter'; background: #535687; color: #FFFFFF;">
                                                                        <tr>
                                                                            <th>time</th>
                                                                            <th class="text-right">Status
                                                                            </th>

                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>xx-xx-xxxx</td>
                                                                            <td>xxxxx xxxx</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>xx-xx-xxxx</td>
                                                                            <td>xxxxx xxx xxx</td>
                                                                        </tr>

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>


                        </div>

                    </div>

                    <div class="col-lg-12">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-cls" data-dismiss="modal">&nbsp;Close</button>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
    </div>
    </div>

</form>
<?= $this->endSection(); ?>