<?php include APPPATH.'Views/inc/top.php'; ?>

<?php include APPPATH.'Views/inc/nav.php'; ?>

    <form id="schForm" name="schForm" method="post">
    <section class="scEnterNum">
        <input type="hidden" id="visitChk" name="visitChk" value="<?=$visitChk?>">
        <input type="hidden" id="patNm" name="patNm" value="<?=$patNm?>">
        <div class="minframe">
            <div class="titleBox">
                <?php if ($visitChk == 'first') { ?>
                <span><?=$hosNm?>에서 메시지를 발송해 드립니다.</span>
                <?php } ?>
                <h1>휴대폰 번호 입력</h1>
            </div>
            <table class="numTable">
                <thead>
                <tr>
                    <th colspan="3"><input type="text" class="phone-txt" maxlength='13' placeholder="휴대폰 번호를 입력해 주세요." name="search" value="<?=$search?>""></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="number" data-value="1">1</td>
                    <td class="number" data-value="2">2</td>
                    <td class="number" data-value="3">3</td>
                </tr>
                <tr>
                    <td class="number" data-value="4">4</td>
                    <td class="number" data-value="5">5</td>
                    <td class="number" data-value="6">6</td>
                </tr>
                <tr>
                    <td class="number" data-value="7">7</td>
                    <td class="number" data-value="8">8</td>
                    <td class="number" data-value="9">9</td>
                </tr>
                <tr>
                    <td class="number" data-value="del"><img class="table-back" src="/img/i-back.svg" alt="back"></td>
                    <td class="number" data-value="0">0</td>
                    <td class="setNum number" data-value="010">010</td>
                </tr>
                </tbody>
            </table>
        </div>
    </section>
    </form>
</div>
<div id="dupPopUp"></div>
<div id="rectPopUp"></div>
</body>
</html>

<script>
    $(function () {
        let mobile = '';

        if ($('.phone-txt').val()) {
            $('.btn-next').prop('disabled', false);
            $('.btn-next').addClass('clicked');
        }

        $('.number').click(function () {
            if ($('.phone-txt').val()) mobile = $('.phone-txt').val();

            if ($(this).attr('data-value') == 'del') {
                if (mobile.length == 10 || mobile.length == 5) {
                    mobile = mobile.slice(0,(mobile.length-2));
                    $('[name=search]').val(mobile);
                } else {
                    mobile = mobile.slice(0,(mobile.length-1));
                    $('[name=search]').val(mobile);
                }
            } else {
                if (mobile.length == 3 || mobile.length == 8) {
                    mobile += '-';
                }
                if (mobile.length >= 13) {
                    return false;
                }
                mobile += $(this).attr('data-value');
                $('[name=search]').val(mobile);
            }

            valueChk(mobile, 'text');
        })

        $('.phone-txt').keyup(function (e) {
            var mobile = this.value;

            if (e.keyCode === 8) {
                if (mobile.length == 9 || mobile.length == 4) {
                    mobile = mobile.slice(0,(mobile.length-1));
                    $('[name=search]').val(mobile);
                } else {
                    mobile = mobile.slice(0,(mobile.length));
                    $('[name=search]').val(mobile);
                }
            } else {
                if (mobile.length == 3 || mobile.length == 8) {
                    mobile += '-';
                }
            }
            if (mobile.length > 13) {
                mobile = mobile.slice(0,(mobile.length-1));
                $('[name=search]').val(mobile);
            } else {
                $('[name=search]').val(mobile);
            }

            valueChk(mobile);
        })
    })

    function prevPage() {
        var frm						=	document.schForm;

        frm.target					=	"_self";
        frm.action					=	"name?ykiho=<?=$ykiho?>";
        frm.submit();
    }

    function nextPage() {
        var phoneRule = /01[016789]-[^0][0-9]{2,3}-[0-9]{3,4}/;

        var frm						=	document.schForm;
        var postDate				=	$( "#schForm" ).serialize();
        var search                  =   frm.search.value;

        if (!phoneRule.test(search)) {
            alert('입력한 정보를 다시 확인해 주세요.');
            return false;
        }

        $.ajax({
            url						:	"search?ykiho=<?=$ykiho?>",
            type					:	"POST",
            data					:	postDate,
            dataType				:	"json",
            contentType				:	"application/x-www-form-urlencoded; charset=UTF-8",
            async					:	true,
            success					:	function (data)
            {
                if (data.result == 'Y') {
                    $('#dupPopUp').html(data.data);
                } else {
                    frm.target					=	"_self";
                    frm.action					=	"jno?ykiho=<?=$ykiho?>";
                    frm.submit();
                }
            }
        });
    }

    function backPage() {
        $('#dupPopUp').html('');
    }

    function formWrite() {

        var form					=	document.querySelector("#schForm");
        var postDate				=	new FormData(form);

        $.ajax({
            url						:	"receipt?ykiho=<?=$ykiho?>",
            type					:	"POST",
            data					:	postDate,
            dataType				:	"json",
            async			        :	true,
            cache			        :	false,
            contentType		        :	false,
            processData		        :	false,
            success					:	function (data)
            {
                if (data.result == 'Y') {
                    $('#rectPopUp').html(data.data);
                } else {
                    alert("접수 실패");
                }
            }
        });
    }

    function reloadPage() {
        location.href = 'visitChk?ykiho=<?=$ykiho?>';
    }
</script>