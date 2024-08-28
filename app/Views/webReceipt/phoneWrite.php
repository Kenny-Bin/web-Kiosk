<?php include APPPATH.'Views/inc/top.php'; ?>

<?php include APPPATH.'Views/inc/nav.php'; ?>

<form id="schForm" name="schForm" method="post">
<section class="scEnterNum scBirthSearch">
    <input type="hidden" id="visitChk" name="visitChk" value="<?=$visitChk?>">
    <input type="hidden" id="patNm" name="patNm" value="<?=$patNm?>">
    <input type="hidden" id="moblie" name="moblie" value="">
    <div class="minframe">
        <div class="titleBox">
            <h1>생년월일(6자리) 또는 핸드폰번호 입력</h1>
        </div>
        <table class="numTable">
            <thead>
            <tr>
                <th colspan="3"><input type="text" class="phone-txt" placeholder="생년월일 또는 핸드폰번호를 입력해 주세요." name="search"></th>
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
                <td class="btnOk" onclick="nextPage()">OK</td>
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
        let birthMobile = '';

        if ($('.phone-txt').val()) {
            $('.btn-next').prop('disabled', false);
            $('.btn-next').addClass('clicked');
        }

        $(document).on('click', '.patInfo', function () {
            var patNm = $(this).find('.__name').attr('data-value');
            var mobile = $(this).find('.__phone').attr('data-value');
            $('#patNm').val(patNm);
            $('#moblie').val(mobile);
        })

        $('.number').click( function () {
            if ($('.phone-txt').val()) birthMobile = $('.phone-txt').val();

            if ($(this).attr('data-value') == 'del') {
                if (birthMobile.substr(0,3) == '010') {
                    if (birthMobile.length == 10 || birthMobile.length == 5) {
                        birthMobile = birthMobile.slice(0,(birthMobile.length-2));
                        $('[name=search]').val(birthMobile);
                    } else {
                        birthMobile = birthMobile.slice(0,(birthMobile.length-1));
                        $('[name=search]').val(birthMobile);
                    }
                } else {
                    birthMobile = birthMobile.slice(0,(birthMobile.length-1));
                    $('[name=search]').val(birthMobile);
                }
            } else {
                if (birthMobile.substr(0,3) == '010') {
                    if (birthMobile.length == 3 || birthMobile.length == 8) {
                        birthMobile += '-';
                    }
                }
                if (birthMobile.length >= 13) {
                    return false;
                }
                birthMobile += $(this).attr('data-value');
                $('[name=search]').val(birthMobile);
            }

            valueChk(birthMobile, 'text');
        })

        $('.phone-txt').keyup(function (e) {
            var birthMobile = this.value;

            if (e.keyCode === 8) {
                if (birthMobile.substr(0,3) == '010') {
                    if (birthMobile.length == 9 || birthMobile.length == 4) {
                        birthMobile = birthMobile.slice(0, (birthMobile.length - 1));
                        $('[name=search]').val(birthMobile);
                    } else {
                        birthMobile = birthMobile.slice(0, (birthMobile.length));
                        $('[name=search]').val(birthMobile);
                    }
                } else {
                    birthMobile = birthMobile.slice(0, (birthMobile.length));
                    $('[name=search]').val(birthMobile);
                }
            } else {
                if (birthMobile.substr(0,3) == '010') {
                    if (birthMobile.length == 3 || birthMobile.length == 8) {
                        birthMobile += '-';
                    }
                }
            }
            if (birthMobile.length > 13) {
                birthMobile = birthMobile.slice(0,(birthMobile.length-1));
                $('[name=search]').val(birthMobile);
            } else {
                $('[name=search]').val(birthMobile);
            }

            valueChk(birthMobile);
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
        var birthRule = /([0-9]{2}(0[1-9]|1[0-2])(0[1-9]|[1,2][0-9]|3[0,1]))/

        var frm						=	document.schForm;
        var postDate				=	$( "#schForm" ).serialize();
        var search                  =   frm.search.value;

        if (search.length == 6) {
            if (!birthRule.test(search)) {
                alert('입력한 정보를 다시 확인해주세요.');
                return false;
            }
        } else {
            if (!phoneRule.test(search)) {
                alert('입력한 정보를 다시 확인해 주세요.');
                return false;
            }
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
                    alert('입력한 정보를 다시 확인해 주세요.');
                }
            }
        });
    }

    function backPage() {
        $('#dupPopUp').html('');
    }

    function formWrite() {

        var form					=	document.querySelector("#schForm");
        form.search.value           =   $('#moblie').val();

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
