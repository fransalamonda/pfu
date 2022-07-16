<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<!-- <div class="container-fluid"> -->
<div class="row  _win">
    <div class="col-lg-12">
        <div class="card-box">

            <div class="row">
                <div class="col-sm-6">
                    <div class="float-left">
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <div style="display: flex;">
                                    <div class="card tombol-aktif _nots" id="_nots">
                                        <button type="button" class="btn b-tombol-aktif b-tombol-font nots" id="nots"> Waiting for Assign (<a>0</a>)</button>

                                    </div>
                                    <div class="card tombol-tidakaktif _prog" id="_prog">
                                        <button type="button" class="btn b-tombol-tidak-aktif b-tombol-font prog" id="prog">In Progress (<a>0</a>)</button>
                                    </div>
                                    <div class="card tombol-tidakaktif _comp" id="_comp">
                                        <button type="button" class="btn b-tombol-tidak-aktif b-tombol-font comp" id="comp">Need Review(<a>0</a>)</button>
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
                                    <div class="card tombol-tidakaktif ">
                                        <select class="form-control">
                                            <option>Medan Plant</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                            <option>5</option>
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
                <!-- <div class="col-sm-6 text-right mb-0"> -->

                <!-- <div class="form-group row">
                        <div class="col-md-10">
                            <select class="form-control">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                            </select>

                        </div>
                        <div class="col-md-10">
                            <button type="button" class="btn btn-outline-secondary waves-effect b-tombol-font hist">Case History <i class="mdi mdi-history"></i></button>
                        </div>
                    </div> -->

                <!-- </div> -->
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
                            <th>Checkpoin</th>
                            <th>Ceklist Notes</th>
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
                            <th>Checkpoin</th>
                            <th>Ceklist Notes</th>
                            <th>Work Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

            <div class="table-responsive _t_casehistory" style="display:none">
                <table class="table table-striped mb-0" id="t_casehistory">
                    <thead>
                        <tr class="font-tabel-tr">
                            <th>Condition</th>
                            <th>Close Date</th>
                            <th>Checkpoin</th>
                            <th>Ceklist Detail</th>
                            <th>Status Done</th>
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


<form id="form_add1">

    <div id="modal_add1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                    <div class="col-sm-6 col-lg-4 col-xl-12">
                                        <i class="ion ion-md-alert"></i> General Information
                                    </div>
                                    </br>
                                    <div class="col-xl-12">
                                        <ul class="list-unstyled mb-0">
                                            <li class="card-kiri-tulisan-satu">Condition</li>
                                            <li>
                                                <div class="card-kiri-tulisan-dua">Need action</div>
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
                                            <li class="cahrd-kiri-tulisan-tiga" id="cn">..</li>
                                        </ul>
                                    </div>

                                </div>
                                </Br>
                                <div class="card-header card-kiri-dua">
                                    <div class="col-sm-6 col-lg-4 col-xl-12">
                                        <div class="card-kiri-dua-tulisan-satu"><i class="mdi mdi-file-document"></i> Status History</div>
                                        </br>
                                        <!-- <div class="card-kiri-dua-tulisan-dua" id="sh">x_x x_x xxxx xxxxx xxxx xxxxx xxxxx xxxx</div> -->
                                        <ul class="list-unstyled mb-0">
                                            <li class="card-kiri-tulisan-tiga">..</li>
                                            <li>
                                                <div class="card-kiri-tulisan-tiga">..</div>
                                            </li>
                                        </ul>
                                    </div>
                                    </br>
                                </div>
                            </div>

                            <div class="col-lg-9">
                                <div class="card-kanan-satu">
                                    <div class="row">

                                        <div class="col-lg-6 card-kanan-dua">
                                            <div class="">
                                                <!-- <div class="alert" id="msg"></div> -->

                                                <div class="card-body">
                                                    <input type="" class="idw" id="idw" name="idw" value="" />
                                                    <input type="" class="ckt" id="ckt" name="ckt" value="" />
                                                    <input type="" class="" id="ckt_d" name="ckt_d" value="" />
                                                    <input type="" class="" id="plant" name="plant" value="" />
                                                    <input type="" class="" id="plant_d" name="plant_d" value="" />
                                                    <div class="row">
                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Departemen PIC<span class="text-danger"> *</span></label>
                                                                <select class="form-control input-sm required" data-toggle="select2" data-placeholder="Select here" id="icdepp" name="icdepp" aria-readonly="" style="font-family: 'Inter';">
                                                                </select>
                                                            </div>

                                                        </div>

                                                        <!-- <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Staffs PIC<span class="text-danger"> *</span></label>

                                                                <select class="form-controlselect2-multiple" data-toggle="select2" multiple="multiple" data-placeholder="Choose" id="icpic" name="icpic">

                                                                </select>
                                                            </div>
                                                        </div> -->
                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Staffs PIC<span class="text-danger"> *</span></label>
                                                                <div class="input_fields_wrap">
                                                                    <div class="input_fields_wrap1" style="height: 42px;">

                                                                        <!-- <input type="text" id="example-input-small" name="  " class="form-control input-sm" placeholder=""> -->

                                                                        <input type="email" id="icpic" name="icpic" autocomplete="current-password" required="" placeholder="Enter your email" class="int-pass int-text ">
                                                                        <i class=" ion ion-md-close  pUlang" style="margin-left: -30px; cursor: pointer;"></i>
                                                                        </Br>
                                                                    </div>
                                                                </div>
                                                                <!-- <div class="col-sm-6 col-lg-4 col-xl-3">
                                                                    Add More Email
                                                                </div> -->
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <div class="input_fields_wrap1">
                                                                    <button type="button" class="btn btn-outline-secondary waves-effect add_field_button"><i class="ion ion-md-add-circle-outline"></i> Add More Email</button>
                                                                    <!-- <button class="add_field_button"> <i class="ion ion-md-add-circle-outline"></i> Add More Email</button> -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- <div class="col-sm-6 col-lg-4 col-xl-12">
                                                            <i class="ion ion-md-add-circle-outline"></i> Add More Email
                                                        </div> -->

                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Problem Detail<span class="text-danger"> *</span></label>
                                                                <textarea class="form-control" rows="3" id="detpro" name="detpro" placeholder="add description"></textarea>

                                                            </div>
                                                        </div>

                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Maintainance Plan<span class="text-danger"> *</span></label>
                                                                <textarea class="form-control" rows="3" id="maint" name="maint" placeholder="add description"></textarea>

                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <!-- card-body -->
                                            </div>
                                            <!-- card -->
                                        </div>

                                        <div class="col-lg-6 card-kanan-tiga">
                                            <div class="">
                                                <div class="card-body">
                                                    <div class="row">

                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Start Date<span class="text-danger"> *</span></label>
                                                                <div class="input-group">

                                                                    <input type="text" class="form-control  inp_sd" placeholder="Select Date" data-provide="datepicker" id="inp_sd" name="inp_sd" readonly=''>
                                                                    <input type="text" class="form-control" id="sd_time" name="sd_time" readonly=''>
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="col-xl-12">
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
                                                        </div>

                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Work Status<span class="text-danger"> *</span></label>
                                                                <select class="form-control" data-toggle="select2" data-placeholder="Select here" id="sttwork" name="sttwork">
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-12 area_select" style="display:none">
                                                            <div class="form-group">
                                                                <label class="t-action" id='input_detail'>Spare Part Detail <span class="text-danger"> *</span></label>
                                                                <textarea class="form-control" rows="3" id="area_select" name="area_select" placeholder="add description"></textarea>

                                                            </div>
                                                        </div>

                                                        <div class="col-xl-12 date_select" style="display:none">
                                                            <div class="form-group">
                                                                <label class="t-action" id="Date_sparepart">Date_select<span class="text-danger"> *</span></label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" placeholder="Select Date" id="d_sel" name="d_sel" data-provide="datepicker" readonly=''>
                                                                    <input type="text" class="form-control" id="spa" name="spa" readonly=''>
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- <div class="col-xl-12">
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btnUpdate" data-dismiss="modal">&nbsp;Cancel</button>
                                                                <button type="submit" class="btn btnUpdate-aktif" id="btnKirim">&nbsp;</button>
                                                            </div>
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 card-kanan-tiga">
                                            <div class="col-lg-12">
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btnUpdate" data-dismiss="modal">&nbsp;Cancel</button>
                                                    <button type="submit" class="btn btnUpdate-aktif" id="btnKirim">&nbsp;</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-12">
                                <!-- <div class="modal-footer">
                                    <button type="button" class="btn btnUpdate" data-dismiss="modal">&nbsp;Cancel</button>
                                    <button type="submit" class="btn btnUpdate-aktif" id="btnKirim">&nbsp;</button>
                                </div> -->
                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div><!-- /.modal -->

</form>


<?= $this->endSection(); ?>