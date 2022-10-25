<div id="update-password-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Password</h5>
            </div>
            
            <div class="modal-body">
                <form action=""></form>
                <form id="update-pass-form" action="" method="POST">

                    <div class="row">
                        @csrf
                        @method('PUT')
                        <div class="col-12">
                            <label for="">Old Password</label>
                            <input type="password" name="old_password" class="form-control" required>
                        </div>

                        <div class="col-12">
                            <label for="">New Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="col-12">
                            <label for="">Comfirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <div class="col-12 my-3">
                            <input type="submit" class="btn btn-success" value="Save">
                        </div>

                    </div>

                </form>

            </div>
            
        </div>
    </div>
</div>