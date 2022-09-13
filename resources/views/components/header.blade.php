<div class="container">
    <div class="d-flex justify-content-between">
        
        <div>
            <h1>Notes Project</h1>
        </div>

        <div class="row align-items-center">
            <div class="col-4">
                {{ Auth::user()->name }}
            </div>
            <div class="col-8">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <input type="submit" value="Logout" class="btn btn-danger">
                </form>
            </div>
        </div>
    </div>

   
</div>