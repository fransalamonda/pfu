<?= $this->extend('layout/template_beranda'); ?>

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
                <div class="col-sm-6 text-right mb-0">
                    <button type="button" class="btn btn-outline-secondary waves-effect b-tombol-font hist">Case History <i class="mdi mdi-history"></i></button>
                </div>
            </div>


            <div class="table-responsive _not_started">
                <table class="table table-striped mb-0" id="t_waiting">
                    <thead>
                        <tr class="font-tabel-tr">
                            <th>Condition<i></i></th>
                            <th>Inspection Date</th>
                            <th>Checkpoin </th>
                            <th>Checkpoin Detail</th>
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
        </div>
    </div>
</div>


<form id="form_add1">
    <div id="modal_add1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-full modal-dialog-form">
            <div class="modal-content">

                <div class="modal-body">
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
                                        </ul>
                                    </div>

                                </div>
                                </Br>
                                <div class="card-header card-kiri-dua">
                                    <div class="col-sm-6 col-lg-4 col-xl-12">
                                        <div class="card-kiri-dua-tulisan-satu"><i class="mdi mdi-file-document"></i> Checklist Notes</div>
                                        </br>
                                        <div class="card-kiri-dua-tulisan-dua" id="cn">x_x x_x xxxx xxxxx xxxx xxxxx xxxxx xxxx</div>
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
                                                    <input type="hidden" id="idw" name="idw" value="" />
                                                    <div class="row">
                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Departemen PIC<span class="text-danger"> *</span></label>
                                                                <select class="form-control input-sm required" data-toggle="select2" data-placeholder="Select here" id="field-2" name="icdepp" aria-readonly="" style="font-family: 'Inter';">
                                                                </select>
                                                            </div>

                                                        </div>

                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Staffs PIC<span class="text-danger"> *</span></label>

                                                                <select class="form-controlselect2-multiple" data-toggle="select2" multiple="multiple" data-placeholder="Choose" id="field-3" name="icpic">

                                                                </select>
                                                            </div>
                                                        </div>

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

                                                                    <input type="text" class="form-control  inp_sd" placeholder="Select Date" data-provide="datepicker" id="inp_sd" name="inp_sd">
                                                                    <input id="field-6" name="sd_time" type="text" class="form-control" readonly>
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

                                                                    <input id="field-8" name="en_time" type="text" class="form-control en_time" readonly=''>
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-12">
                                                            <div class="form-group">
                                                                <label class="t-action">Work Status<span class="text-danger"> *</span></label>
                                                                <select class="form-control" data-toggle="select2" data-placeholder="Select here" id="field-9" name="sttwork">
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-12 area_select" style="display:none">
                                                            <div class="form-group">
                                                                <label class="t-action" id='input_detail'>Spare Part Detail <span class="text-danger"> *</span></label>
                                                                <textarea class="form-control" rows="3" id="area_select" placeholder="add description"></textarea>

                                                            </div>
                                                        </div>

                                                        <div class="col-xl-12 date_select" style="display:none">
                                                            <div class="form-group">
                                                                <label class="t-action" id="Date_sparepart">Date_select<span class="text-danger"> *</span></label>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control slt_date" placeholder="Select Date" id="select_date" name="select_date" data-provide="datepicker" readonly=''>
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

                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-12">
                                <div class="modal-footer">
                                    <button type="button" class="btn btnUpdate" data-dismiss="modal">&nbsp;Cancel</button>
                                    <!-- <button type="submit" class="btn btnUpdate-aktif" id="  ">&nbsp;</button> -->
                                    <button type="submit" class="btn btnUpdate-aktif" id="btnKirim">&nbsp;</button>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div><!-- /.modal -->
</form>

<form id="ActionForm">

    <div id="mdl_dok_add" class="modal modal-action-form fade " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-full modal-dialog-form ">
            <div class="modal-content">

                <div class="modal-body">
                    <a type="button" class="close btnX" id="btnX" name="btnX">×</a>
                    <h5 class=" text-center text-form-judul">Action Form</h5>
                    <p class="text-center lead text-form-sub-judul">Complete Form to Update this case
                    </p>
                </div>

                <div class="modal-body">

                    <div class="container-fluid">
                        <!-- Row start -->
                        <div class="row">

                            <div class="col-lg-3">
                                <!-- <div class="card"> -->
                                <div class="card-header card-kiri-satu">
                                    <div class="col-sm-6 col-lg-4 col-xl-12">
                                        <i class="ion ion-md-alert"></i> General Information
                                    </div>
                                    </br>
                                    <!-- <div class="col-sm-6 col-lg-4 col-xl-12">
                                        <div class='card-kiri-tulisan-satu'>Condition</div>
                                        <div class="card-kiri-tulisan-dua">Need action</div>
                                        <div class="card-kiri-tulisan-satu">Inspection Date</div>
                                        <div class="card-kiri-tulisan-tiga">22</div>
                                    </div> -->
                                    <div class="col-xl-12">
                                        <!-- <div class="form-group">
                                            <p class='card-kiri-tulisan-satu'>Condition</p>
                                            <p class=" active"> Need Action</p>
                                        </div> -->
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
                                        </ul>
                                    </div>

                                </div>
                                </Br>
                                <!-- </div> -->
                                <!-- <div class="alert alert-warning alert-dismissible mb-0 fade show"> -->
                                <!-- <div class="card"> -->
                                <div class="card-header card-kiri-dua">
                                    <div class="col-sm-6 col-lg-4 col-xl-12">
                                        <div class="card-kiri-dua-tulisan-satu"><i class="mdi mdi-file-document"></i> Checklist Notes</div>
                                        </br>
                                        <div class="card-kiri-dua-tulisan-dua" id="cn">xxxx xxx xxxx xxxxx xxxx xxxxx xxxxx xxxx</div>
                                    </div>
                                    </br>
                                </div>
                            </div>

                            <div class="col-lg-9  card-kanan-satu">
                                <div class="row">

                                    <div class="col-lg-6 card-kanan-dua">
                                        <div class="">

                                            <div class="card-body">
                                                <input type="hidden" id="id" name="id" value="" />
                                                <div class="row">
                                                    <div class="col-xl-12">


                                                        <div class="form-group">
                                                            <label class="t-action">Departemen PIC<span class="text-danger"> *</span></label>
                                                            <select class="form-control input-sm" data-toggle="select2" data-placeholder="Select here" id="deppic" name="deppic" readonly="">
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-12">
                                                        <div class="form-group">
                                                            <label class="t-action">Staffs PIC<span class="text-danger"> *</span></label>
                                                            <select class="form-controlselect2-multiple" data-toggle="select2" multiple="multiple" data-placeholder="Choose" id="pic" name="pic" required="">

                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-12">
                                                        <div class="form-group">
                                                            <label class="t-action">Problem Detail<span class="text-danger"> *</span></label>
                                                            <textarea class="form-control" rows="3" id="detpro" name="detpro" required="" placeholder="add description"></textarea>

                                                        </div>
                                                    </div>

                                                    <div class="col-xl-12">
                                                        <div class="form-group">
                                                            <label class="t-action">Maintainance Plan<span class="text-danger"> *</span></label>
                                                            <textarea class="form-control" rows="3" id="maint" name="maint" placeholder="add description" required=""></textarea>

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
                                                                <input id="sd_time" type="text" class="form-control" readonly>
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

                                                                <!-- <input id="en_time" type="text" class="form-control en_time" readonly=''> -->
                                                                <input id="en_time" type="text" class="form-control en_time" readonly>
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
                                                            <textarea class="form-control" rows="3" id="area_select" placeholder="add description" required=""></textarea>

                                                        </div>
                                                    </div>

                                                    <div class="col-xl-12 date_select" style="display:none">
                                                        <div class="form-group">
                                                            <label class="t-action" id="Date_sparepart">Date_select<span class="text-danger"> *</span></label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control slt_date" placeholder="Select Date" id="select_date" data-provide="datepicker" required="" readonly=''>
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

                                    <div class="col-lg-12 card-kanan-empat">
                                        <div class="modal-footer">

                                            <button type="submit" class="btn btnUpdate" id="btnCancel">
                                                <!-- <i class="fas fa-times"></i> -->
                                                &nbsp;Cancel
                                            </button>
                                            <button type="submit" class="btn btnUpdate-aktif">&nbsp;Update Case</button>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <!-- card -->
                    </div>

                </div>

                <!-- End of Row -->


            </div>


        </div>
    </div>

</form>


<?= $this->endSection(); ?>