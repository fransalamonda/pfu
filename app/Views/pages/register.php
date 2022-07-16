<?= $this->extend('layout/template_register'); ?>

<?= $this->section('content'); ?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="card mt-4">

            <div class="card-body ">
                <div class="row" style="display: flex; ">
                    <div class="col-lg-6 text-right" style="border-style:hidden; border-right: 2px solid #E7E7E7;
">
                        <h3 class=" text-form-login-satu">
                            WBI
                        </h3>
                    </div>
                    <div class="col-lg-6">
                        <h3 class="text-form-login-dua"> WALK BY</Br>INSPECTIONS</span>
                        </h3>
                    </div>

                </div>
                <div class="row" style="display: flex; ">
                    <div class="col-lg-12 text-center">
                        <h5 class=" text-center">
                            Action tracking system
                        </h5>
                    </div>
                </div>
            </div>

            <div class="card-body p-4 mt-2">
                <form class="form-horizontal m-t-20" id="frm_registrasi" action="#" method="post">
                    <input type="hidden" name="<?= csrf_token(); ?>" id="csrf" value="<?= csrf_hash(); ?>" />
                    <div class="form-group mb-3">
                        <label class="text-login" for="User Name">User Name</label>
                        <input class="form-control int-text " type="text" id="name_id" name="name_id" required="" placeholder="Enter your User Name">
                    </div>
                    <div class="form-group mb-3">
                        <label class="text-login" for="Employee ID">Employee ID</label>
                        <input class="form-control int-text userid" type="text" id="userid" name="userid" required="" placeholder="Enter your employee id">
                    </div>
                    <div class="form-group mb-3">
                        <label class="text-absolute-solution" for="Email">Email</label>
                        <input class="form-control int-text " type="email" id="email_id" name="email_id" required="" placeholder="Enter your Email">
                    </div>
                    <div class="vericode" style="display:none">
                        <label class="text" for="Employee ID">Verification Code</label>
                        <div class="input-group mt-3">
                            <input type="text" id="vrcode" name="vrcode" class="form-control vrcode" placeholder="Verification Code">
                            <span class="input-group-append">
                                <button type="button" class="btn waves-effect waves-light btn-primary btn-trb" id="btn-trouble">
                                    Trouble with the code?</button>
                            </span>
                        </div>
                        <p class="text-purple">A verification code was sent to the recovery email address. Please provide the 6-digit code.</p>
                    </div>
                    <div class="form-group text-center mt-5 mb-4 btn-ver">
                        <button class="btn btn-login-non btn-lg btn-block waves-effect waves-light _log" type="submit" id="btn-reg1"> Register</button>
                    </div>
                    <div class="form-group text-center mt-5 mb-4 btn-login" style="display:none">
                        <button class="btn btn-login-akt btn-lg btn-block waves-effect waves-light _log" type="submit" id="_log"><i class="fa fa-sign-in"></i> Register</button>
                    </div>

                    <div class="panel-body text-center">
                        <p>Powered by:</p>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-sm-12 text-center">
                            <a href="#"><img style=" height: 35px; width: 35px;" src="<?php base_url(); ?>/pfu/public/assets/images/icon_smp.png" alt="user-image" class="rounded-circle"></a>
                        </div>
                        <!-- <div class="col-sm-6">
                             <a href="#"><img src="<?php base_url(); ?>/pfu/public/assets/images/image_5.png" alt="user-image" class="rounded-circle"></a> 
                        </div> -->

                    </div>
                </form>

            </div>
            <!-- end card-body -->
        </div>
        <!-- end card -->

        <!-- end row -->

    </div>
    <!-- end col -->
</div>
<?= $this->endSection(); ?>