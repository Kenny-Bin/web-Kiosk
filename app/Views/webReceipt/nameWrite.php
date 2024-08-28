<?php include APPPATH.'Views/inc/top.php'; ?>

<?php include APPPATH.'Views/inc/nav.php'; ?>

    <form id="schForm" name="schForm" method="post">
        <section class="scNameSearch">
        <input type="hidden" id="patNm" name="patNm" value="<?=$patNm?>">
        <input type="hidden" id="visitChk" name="visitChk" value="<?=$visitChk?>">
            <div class="minframe">
                <div class="titleBox">
                    <?php if ($visitChk == 'first') { ?>
                        <span>신규 접수</span>
                    <?php } ?>
                    <h1>이름 입력</h1>
                </div>
                <label for="">
                    <input type="text" placeholder="이름을 입력해 주세요." class="name-txt" name="patNm" value="<?=$patNm?>" onkeyup="valueChk(this.value, 'text')">
                </label>
            </div>
        </section>
    </form>
</div>
</body>
</html>
<script>
    $(function () {
        if ($('.name-txt').val()) {
            $('.btn-next').prop('disabled', false);
            $('.btn-next').addClass('clicked');
        }

        $('.btn-prev').click(function () {
            location.href = `visitChk?ykiho=<?=$ykiho?>`;
        })
    })

    function nextPage() {
        var frm						=	document.schForm;

        if (frm.visitChk.value == 'first') {
            frm.action					=	"number?ykiho=<?=$ykiho?>";
        } else {
            frm.action					=	"phone?ykiho=<?=$ykiho?>";
        }
        frm.target					=	"_self";
        frm.submit();
    }

</script>
