
@include('blocks.maximize_image_modal')

<!-- Footer Dark -->

@include('front.main_components.footer_block')

<!-- ... end Footer Dark -->

<div class="modal fade users_modal_content" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


@include('front.general_components.scripts')

<script src="<?= url('public_html/jscode/profile.js') ?>"></script>

<script>

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

</script>


<!-- <a class="back-to-top" href="#">
    <img src="{{url("/")}}/public_html/front/new_design/svg-icons/back-to-top.svg" alt="arrow" class="back-icon">
</a> -->

@yield('jspdf')

</body>

</html>
