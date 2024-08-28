//Prev & Next Btn
$(document).on('click', '.scHeader .nav_btn', function(e){
    $(this).addClass('clicked');
    $(this).siblings().removeClass('clicked');
});

//Pop Up ver-2 List cliked
$(document).on('click', '.pop-ver2 .list', function(e){
    $(this).addClass('clicked');
    $(this).siblings().removeClass('clicked');
});

//Item cliked
$(document).on('click', '.fieldWrap .diagFldCd', function(e){
    $(this).addClass('active');
    $(this).siblings().removeClass('active');
});

$(document).on('click', '.fieldWrap .vstPthCd', function(e){
    $(this).addClass('active');
    $(this).siblings().removeClass('active');
});

//Pagination cliked
$(document).on('click', '.fieldWrap .pg-btn', function(e){
    $(this).addClass('active');
    $(this).siblings().removeClass('active');
});

//scInfo Btn cliked
$(document).on('click', '.scInfo .acpt-btn', function(e){
    $(this).addClass('active');
});

$(document).on('click', '.scInfo .btn-pvcyView', function(e){
    $('.pvcy-detail').addClass('active');
    $('body').addClass('scrollLock');
});
$(document).on('click', '.scInfo .btn-pvcyClose', function(e){
    $('.pvcy-detail').removeClass('active');
    $('body').removeClass('scrollLock');
});

$(function() {
    var lnb = $("#scHeader").offset().top;
    $(window).scroll(function() {
        var window = $(this).scrollTop();

        if(lnb < window) {
        $("#scHeader").addClass("fixed");
        } else {
        $("#scHeader").removeClass("fixed");
        }
    })
});

function valueChk(value) {
    if (value) {
        $('.btn-next').prop('disabled', false);
        $('.btn-next').addClass('clicked');
    } else {
        $('.btn-next').prop('disabled', true);
        $('.btn-next').removeClass('clicked');
    }
}