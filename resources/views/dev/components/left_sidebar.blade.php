<div class="">


    <!--logo and iconic logo end-->
    <div class="left-side-inner">

        <!--sidebar nav start-->
        <ul class="nav nav-pills nav-stacked custom-nav">
            <li class="active"><a href="{{url('/admin/dashboard')}}"><i class="lnr lnr-power-switch"></i><span>Dashboard</span></a>
            </li>
            <li class="menu-list">
                <a><i class="lnr lnr-cog"></i>
                    <span>Edit Content Generator</span></a>
                <ul class="sub-menu-list">
                    <li>
                        <a href='<?= url("/dev/generate_edit_content/show_all") ?>'>Show All Generators</a>
                    </li>

                    <li>
                        <a href="<?= url('/dev/generate_edit_content/save') ?>">Add New Generator</a>
                    </li>
                </ul>
            </li>

            <li class="menu-list">
                <a><i class="lnr lnr-cog"></i>
                    <span>Site Permissions</span></a>
                <ul class="sub-menu-list">
                    <li>
                        <a href='<?= url("dev/permissions/permissions_pages/show_all_permissions_pages") ?>'>Show All Permission pages</a>
                    </li>

                    <li>
                        <a href="<?= url('dev/permissions/permissions_pages/save') ?>">Add New Permission Page</a>
                    </li>

                    <li>
                        <a href="<?= url('dev/permissions/assign_permission_for_this_user') ?>">Edit Main User Permissions</a>
                    </li>

                </ul>
            </li>


        </ul>
        <!--sidebar nav end-->
    </div>
</div>