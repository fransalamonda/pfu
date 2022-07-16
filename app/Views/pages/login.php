<?= $this->extend('layout/template_login'); ?>

<?= $this->section('content'); ?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">
        <div class="card mt-4">

            <div class="card-body ">
                <!-- <div class="bg-overlay"></div> -->
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
                <form class="form-horizontal m-t-20" id="frm_login" action="#" method="post">
                    <input type="hidden" name="<?= csrf_token(); ?>" id="csrf" value="<?= csrf_hash(); ?>" />
                    <div class="form-group mb-3">
                        <label class="text-login" for="Employee ID">Employee ID</label>
                        <input class="form-control int-text userid" type="text" name="userid" required="" placeholder="Enter your employee id">
                    </div>

                    <div class="form-group mb-3">
                        <label class="text-login" for="Password">Password</label>
                        <input type="password" name="pass" autocomplete="current-password" required="" placeholder="Enter your Password" class="int-pass int-text saatulang">
                        <i class="far fa-eye pUlang" style="margin-left: -30px; cursor: pointer;"></i>

                    </div>
                    <a href="http://147.139.198.218/sso-reset-password/reset_password_sso/" target="_blank">Forgot Password
                        ?</a>
                    <div class="form-group text-center mt-5 mb-4">
                        <button class="btn btn-login-non btn-lg btn-block waves-effect waves-light _log" type="submit" id="_log"><i class="fa fa-sign-in"></i> Login</button>
                    </div>
                    <p class="mt-4">Belum punya akun? <a href="<?php echo site_url("Home/register") ?>" class="text-decoration-none btn-new">buat akun</a></p>
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