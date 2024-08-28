<?php include APPPATH.'Views/inc/top.php'; ?>

<?php include APPPATH.'Views/inc/nav.php'; ?>

    <form id="schForm" name="schForm" method="post">
    <section class="scEnterNum scPersonalId">
        <input type="hidden" id="visitChk" name="visitChk" value="<?=$visitChk?>">
        <input type="hidden" id="patNm" name="patNm" value="<?=$patNm?>">
        <input type="hidden" id="search" name="search" value="<?=$search?>">
        <input type="hidden" id="jno" name="jno" value="<?=$jno?>">
        <div class="minframe">
            <div class="titleBox">
                <span>건강보험 조회 시에만 사용</span>
                <h1>주민등록번호 입력</h1>
            </div>
            <table class="numTable">
                <thead>
                <tr>
                    <th colspan="3"><input type="text" class="jno-txt" maxlength='14' placeholder="주민등록번호를 입력해 주세요." name="mkJno" value="<?=$mkJno?>"></th>
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
                    <td class="rollback number" data-value="rollback">초기화</td>
                </tr>
                </tbody>
            </table>
        </div>
    </section>
    </form>
</div>
</body>
</html>

<script>
    $(function () {
        let jno = '';
        let orgJno = '';

        if ($('.jno-txt').val()) {
            $('.btn-next').prop('disabled', false);
            $('.btn-next').addClass('clicked');
        }

        $('.number').click(function () {
            if ($('.jno-txt').val()) jno = $('.jno-txt').val();
            if ($('[name=jno]').val()) orgJno = $('[name=jno]').val();

            ogJno = $(this).attr('data-value');
            msjno = $(this).attr('data-value');

            if ($(this).attr('data-value') == 'del') {
                if (jno.length == 8) {
                    jno = jno.slice(0,(jno.length-2));
                    orgJno = orgJno.slice(0,(orgJno.length-2));
                    $('[name=mkJno]').val(jno);
                    $('[name=jno]').val(orgJno);
                } else {
                    jno = jno.slice(0,(jno.length-1));
                    orgJno = orgJno.slice(0,(orgJno.length-1));
                    $('[name=mkJno]').val(jno);
                    $('[name=jno]').val(orgJno);
                }
            } else if ($(this).attr('data-value') == 'rollback') {
                jno = '';
                orgJno = '';
                $('[name=mkJno]').val(jno);
                $('[name=jno]').val(orgJno);
            } else {
                if (jno.length == 6) {
                    jno += '-';
                    orgJno += '-';
                }

                if (jno.length > 7) {
                    if (jno.length >= 14) {
                        return false;
                    }
                    msjno = "*";
                    ogJno = $(this).attr('data-value');
                }
                jno += msjno;
                orgJno += ogJno;
                $('[name=mkJno]').val(jno);
                $('[name=jno]').val(orgJno);
            }

            valueChk(jno, 'text');
        })

        $('.jno-txt').keyup(function (e) {
            const regExp = /[0-9]/g;
            var jno = this.value;

            if (e.keyCode === 8) {
                if (jno.length == 7) {
                    jno = jno.slice(0,(jno.length-1));
                    orgJno = orgJno.slice(0,(jno.length));
                    $('[name=mkJno]').val(jno);
                    $('[name=jno]').val(orgJno);
                } else {
                    jno = jno.slice(0,(jno.length));
                    orgJno = orgJno.slice(0,(jno.length));
                    $('[name=mkJno]').val(jno);
                    $('[name=jno]').val(orgJno);
                }
            } else {
                if (regExp.test(e.key)) {
                    orgJno += e.key
                }

                if (jno.length == 6) {
                    jno += '-';
                    orgJno += '-';
                }
                if (jno.length > 8) {
                    var pattern = /.{1}$/;
                    jno = jno.replace(pattern, "*")
                }
            }
            if (orgJno.length > 14) {
                orgJno = orgJno.slice(0,(jno.length));
            } else {
                $('[name=mkJno]').val(jno);
                $('[name=jno]').val(orgJno);
            }

            valueChk(jno);
        })
    })
    function prevPage() {
        var frm						=	document.schForm;

        frm.target					=	"_self";
        frm.action					=	"number?ykiho=<?=$ykiho?>";
        frm.submit();
    }

    function nextPage() {
        var frm						=	document.schForm;
        var jNo                     =   frm.jno.value;
        var jnoRule = /^(?:[0-9]{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[1,2][0-9]|3[0,1]))-[1-9][0-9]{6}$/;

        if (!jnoRule.test(jNo)) {
            alert('주민등록 번호를 확인해 주세요.');
            return false;
        }

        frm.target					=	"_self";
        frm.action					=	"address?ykiho=<?=$ykiho?>";
        frm.submit();
    }
</script>
