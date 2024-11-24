<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-capitalize">
                    {{ str_replace(['admin/', '-', 'agent/'], ' ', preg_replace('/\/[a-f0-9\-]+$/i', '', Request::path())) }}
                </h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">
                        {{ str_replace(['admin/', '-', 'agent/'], ' ', preg_replace('/\/[a-f0-9\-]+$/i', '', Request::path())) }}
                    </li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
