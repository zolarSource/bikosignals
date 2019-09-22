(function($, undefined){
    $(function(){

        $('.amo-modal-form-btn').on('click', function (event) {
            event.preventDefault();
            var form = $(this).data('form');

            if(form == 'vip'){
                $('.amo-modal-body').load('layouts/formV.html');
            }else if(form == 'business'){
                $('.amo-modal-body').load('layouts/formB.html');
            }else if(form == 'standart'){
                $('.amo-modal-body').load('layouts/formS.html');
            }
            $('#amo_form').modal({backdrop: 'static'});
        });
        
        $('#amo_form').on('click', '.submit-amo-form-btn', function (event) {
            event.preventDefault();
            var form = $(this).parent('form');
            var chBox = $(".amo-form-cb");
            var flag = true;

            $(".amo-form-inp").each(function(indx, element){
                var elemVal = $(element).val()
                $(element).removeClass('is-invalid is-valid')
                if(elemVal == '' || elemVal == null){
                    $(element).addClass('is-invalid');
                    flag = false;
                }else{
                    $(element).addClass('is-valid');
                }
            });

            if(chBox.prop('checked')) {
                $('.hr-invalid').addClass('d-none');
            }else{
                $('.hr-invalid').removeClass('d-none');
                flag = false;
            }

            if(flag){
                form.submit();
            }
        });


        $('body').on('click', '.pp-form', function (event) {
            event.preventDefault();

            $('.bd-example-modal-lg').modal();
        });

        $('.bd-example-modal-lg').on('hidden.bs.modal', function (e) {
            $('body').addClass('modal-open');
        });

        $('body').on('click', '.pp-index', function (event) {
            event.preventDefault();

            $('.bd-pp-modal-lg').modal();
        });


    });
})(jQuery);
