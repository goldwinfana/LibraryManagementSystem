$(function () {

    // ------------------------------------------------------- //
    // Tooltips init
    // ------------------------------------------------------ //

    $('[data-toggle="tooltip"]').tooltip()

    // ------------------------------------------------------- //
    // Universal Form Validation
    // ------------------------------------------------------ //

    $('.form-validate').each(function() {
        $(this).validate({
            errorElement: "div",
            errorClass: 'is-invalid',
            validClass: 'is-valid',
            ignore: ':hidden:not(.summernote),.note-editable.card-block',
            errorPlacement: function (error, element) {
                // Add the `invalid-feedback` class to the error element
                error.addClass("invalid-feedback");
                //console.log(element);
                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.siblings("label"));
                }
                else {
                    error.insertAfter(element);
                }
            }
        });
    });

    // ------------------------------------------------------- //
    // Material Inputs
    // ------------------------------------------------------ //

    var materialInputs = $('input.input-material');

    // activate labels for prefilled values
    materialInputs.filter(function() { return $(this).val() !== ""; }).siblings('.label-material').addClass('active');

    // move label on focus
    materialInputs.on('focus', function () {
        $(this).siblings('.label-material').addClass('active');
    });

    // remove/keep label on blur
    materialInputs.on('blur', function () {
        $(this).siblings('.label-material').removeClass('active');

        if ($(this).val() !== '') {
            $(this).siblings('.label-material').addClass('active');
        } else {
            $(this).siblings('.label-material').removeClass('active');
        }
    });

    // ------------------------------------------------------- //
    // Footer
    // ------------------------------------------------------ //

    var pageContent = $('.page-content');

    $(document).on('sidebarChanged', function () {
        adjustFooter();
    });

    $(window).on('resize', function(){
        adjustFooter();
    })

    function adjustFooter() {
        var footerBlockHeight = $('.footer__block').outerHeight();
        pageContent.css('padding-bottom', footerBlockHeight + 'px');
    }

    // ------------------------------------------------------- //
    // Adding fade effect to dropdowns
    // ------------------------------------------------------ //
    $('.dropdown').on('show.bs.dropdown', function () {
        $(this).find('.dropdown-menu').first().stop(true, true).fadeIn(100).addClass('active');
    });
    $('.dropdown').on('hide.bs.dropdown', function () {
        $(this).find('.dropdown-menu').first().stop(true, true).fadeOut(100).removeClass('active');
    });


    // ------------------------------------------------------- //
    // Search Popup
    // ------------------------------------------------------ //
    $('.search-open').on('click', function (e) {
        e.preventDefault();
        $('.search-panel').fadeIn(100);
    })
    $('.search-panel .close-btn').on('click', function () {
        $('.search-panel').fadeOut(100);
    });


    // ------------------------------------------------------- //
    // Sidebar Functionality
    // ------------------------------------------------------ //
    $('.sidebar-toggle').on('click', function () {
        $(this).toggleClass('active');

        $('#sidebar').toggleClass('shrinked');
        $('.page-content').toggleClass('active');
        $(document).trigger('sidebarChanged');

        if ($('.sidebar-toggle').hasClass('active')) {
            $('.navbar-brand .brand-sm').addClass('visible');
            $('.navbar-brand .brand-big').removeClass('visible');
            $(this).find('i').attr('class', 'fa fa-long-arrow-right');
        } else {
            $('.navbar-brand .brand-sm').removeClass('visible');
            $('.navbar-brand .brand-big').addClass('visible');
            $(this).find('i').attr('class', 'fa fa-long-arrow-left');
        }
    });


    // ------------------------------------------------------ //
    // For demo purposes, can be deleted
    // ------------------------------------------------------ //

    if ($('#style-switch').length > 0) {
        var stylesheet = $('link#theme-stylesheet');
        $("<link id='new-stylesheet' rel='stylesheet'>").insertAfter(stylesheet);
        var alternateColour = $('link#new-stylesheet');

        if ($.cookie("theme_csspath")) {
            alternateColour.attr("href", $.cookie("theme_csspath"));
        }

        $("#colour").change(function () {

            if ($(this).val() !== '') {

                var theme_csspath = 'css/style.' + $(this).val() + '.css';

                alternateColour.attr("href", theme_csspath);

                $.cookie("theme_csspath", theme_csspath, {
                    expires: 365,
                    path: document.URL.substr(0, document.URL.lastIndexOf('/'))
                });

            }

            return false;
        });
    }

    // STrtas  ----------------------------------------//////////////////////////////////////////

    setTimeout(function () {
        $('.message-alert').fadeOut('slow');
    },8000);

    $('.category').on('click', function () {
        $('#add-category').modal('show');
    });


    $('.add-user').on('click', function () {
        $('#add-user').modal('show');
    });

    // books

    $('.add-book').on('click', function () {
        $('#add-book').modal('show');
    });

    $('.delete-book').on('click', function () {
        var id = this.id;
        $('#lbl-category').html('Confirm deletation of books with id: '+id+'?');
        $('input[name=delete-book]').val(id);
        $('#delete-book').modal('show');
    });

    $('.edit-book').on('click', function () {
        var id = this.id;

        $.ajax({
            type: 'POST',
            url: './sql.php',
            data: {
                getBook: id
            },
            dataType: 'json',
            success: function (response) {

                $('input[name=edit-book]').val(id);
                $('input[name=book]').val(response.bookName);
                $('input[name=author]').val(response.author);
                $('select[name=category]').val(response.categoryID);
                $('select[name=shelve]').val(response.shelveNumber);
                $('input[name=quantity]').val(response.quantity);
                $('input[name=price]').val(response.price);

            }});


        $('#edit-book').modal('show');
    });


    // students

    $('.delete-student').on('click', function () {
        var stuNo = this.id;
        $('#lbl-category').html('Confirm deletation of student with student no: '+stuNo+'?');
        $('input[name=delete-student]').val(stuNo);
        $('#delete-student').modal('show');
    });

    $('.edit-student').on('click', function () {
        var stuNo = this.id;

        $.ajax({
            type: 'POST',
            url: './sql.php',
            data: {
                getStudent: stuNo
            },
            dataType: 'json',
            success: function (response) {

                $('input[name=edit-student]').val(stuNo);
                $('input[name=edit-name]').val(response.name);
                $('input[name=edit-email]').val(response.email);
                $('input[name=edit-id_number]').val(response.id_number);
                $('input[name=edit-gender]').val(response.gender);
                $('input[name=edit-password]').val(response.password);

            }});


        $('#edit-student').modal('show');
    });

    // admins
    $('.delete-admin').on('click', function () {
        var id = this.id;
        $('#lbl-category').html('Confirm deletation of admin with id: '+id+'?');
        $('input[name=delete-admin]').val(id);
        $('#delete-admin').modal('show');
    });

    $('.edit-admin').on('click', function (){
        var id = this.id;

        $.ajax({
            type: 'POST',
            url: './sql.php',
            data: {
                getBook: id
            },
            dataType: 'json',
            success: function (response) {

                $('input[name=edit-admin]').val(id);
                $('input[name=edit-admin-name]').val(response.name);
                $('input[name=edit-admin-email]').val(response.email);
                $('input[name=edit-admin-id_number]').val(response.id_number);
                $('input[name=edit-admin-gender]').val(response.gender);
                $('input[name=edit-admin-password]').val(response.password);

            }});


        $('#edit-book').modal('show');
    });




    $('select[name=select-user]').on('change', function () {

        if($('select[name=select-user]').val() == 'Admins'){
            $('.admins').show();
            $('.students').hide();
        }else{
            $('.students').show();
            $('.admins').hide();
        }

    });
    //  Delete Post
    $('.delete-post').on('click', function () {
        var id = this.id;
        $('.postID').html('Confirm Delete For Post With ID:  <span class="text-danger mar-5">'+id+'</span> ?');
        $('input[name=postID]').val(id);
        $('#delete-post').modal('show');

    });

});

