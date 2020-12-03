$(function() {
    'user strict';
    // create function to deal with placeholder
    $('[placeholder]').focus(function() {
        $(this).attr('data-text', $(this).attr('placeholder')); // store placeholder value in datatext
        $(this).attr('placeholder', ''); // set placeholder value null
    }).blur(function() {
        $(this).attr('placeholder', $(this).attr('data-text')); // set datatext value to placeholder
    });

    // create function to add astriks (*) on required field
    $('input').each(function() {
        if ($(this).attr('required') === 'required') {
            $(this).after('<span class="astric">*</span>');
        }
    });

    // create function to deal with eye for password
    var passField = $('.password');
    $('.showpass').hover(function() {
        passField.attr('type', 'text');
    }, function() {
        passField.attr('type', 'password');
    });

    //create function to delete button
    $('.confirm').click(function() {
        return confirm(' هل انت متاكد من انك تريد حذف هذا العنصر المحدد ؟');
    });


    //creat function to select box all
    $('#selectAll').click(function() {

        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    //create function to show and hide buttons 
    $(window).scroll(function() {
        if ($(this).scrollTop() >= 100) { // If page is scrolled more than 50px
            $('#return-to-top ,#Rows-load').show(); // Fade in the arrow
        } else {
            $('#return-to-top ,#Rows-load').hide(); // Else fade out the arrow
        }
    });

    //creat function to load more table rows
    $('.main-table tbody tr:nth-child(n+1):nth-child(-n+20)').addClass('activeTR');
    $('#Rows-load').on('click', function(e) {
        e.preventDefault();
        let rows = $('.main-table tbody tr');
        let lastActiveIndex = rows.filter('.activeTR:last').index();
        rows.filter(':lt(' + (lastActiveIndex + 5) + ')').addClass('activeTR');
    });

    // creat function to scroll btn
    $('#return-to-top').click(function() { // When arrow is clicked
        $('body,html').animate({
            scrollTop: 0 // Scroll to top of body
        }, 500);
    });

    // create function for title on buttons
    $('[data-toggle="tooltip"]').tooltip();
});