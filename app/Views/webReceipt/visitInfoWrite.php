<?php include APPPATH.'Views/inc/top.php'; ?>

<?php include APPPATH.'Views/inc/nav.php'; ?>

<script>
    function getAjaxData(divID, getURL, $pno)
    {
        var diagFldCd = $('#diagFldCd').val();
        var vstPthCd = $('#vstPthCd').val();
        $.ajax({
            url						:	getURL,
            type					:	"POST",
            data					:	{'pno' : $pno,
                                         'diagFldCd' : diagFldCd,
                                         'vstPthCd' : vstPthCd,
                                        },
            dataType				:	"html",
            contentType				:	"application/x-www-form-urlencoded; charset=UTF-8",
            async					:	true,
            success					:	function (data)
            {
                $( '#' + divID ).html(data);
            }
        });
    }
</script>

<form id="schForm" name="schForm" method="post">
<section class="scMediField">
    <input type="hidden" id="visitChk" name="visitChk" value="<?=$visitChk?>">
    <input type="hidden" id="patNm" name="patNm" value="<?=$patNm?>">
    <input type="hidden" id="search" name="search" value="<?=$search?>">
    <input type="hidden" id="jno" name="jno" value="<?=$jno?>">
    <input type="hidden" id="mkJno" name="mkJno" value="<?=$mkJno?>">
    <input type="hidden" id="addr" name="addr" value="<?=$addr?>">
    <input type="hidden" id="dtl_addr" name="dtl_addr" value="<?=$dtl_addr?>">
    <input type="hidden" id="diagFldCd" name="diagFldCd" value="<?=$diagFldCd?>">
    <input type="hidden" id="diagFldCdName" name="diagFldCdName" value="<?=$diagFldCdName?>">
    <input type="hidden" id="vstPthCd" name="vstPthCd" value="<?=$vstPthCd?>">
    <input type="hidden" id="vstPthCdName" name="vstPthCdName" value="<?=$vstPthCdName?>">

    <div class="maxframe">
        <div class="titleBox">
            <h1>진료분야·내원경로 선택</h1>
        </div>
        <div class="fieldWrap">
            <div class="field-inner" id="_treat">
                <script Language="javaScript">getAjaxData('_treat', 'treatment?ykiho=<?=$ykiho?>', 1)</script>
            </div>
            <div class="pagi-wrap">
                <?php
                for ($i = $treatmentPage['spno']; $i <= $treatmentPage['epno']; $i++)
                {
                    ?>
                    <?php if ( $i == $treatmentPage['pno'] ) { ?>
                    <span class="pg-btn active" onclick="getAjaxData('_treat', 'treatment?ykiho=<?=$ykiho?>', $i)"></span>
                <?php } else { ?>
                    <span class="pg-btn" onclick="getAjaxData('_treat', 'treatment?ykiho=<?=$ykiho?>', $i)"></span>
                <?php } ?>
                    <?php
                }
                ?>
<!--                <span class="pg-btn"></span>-->
<!--                <span class="pg-btn"></span>-->
<!--                <span class="pg-btn"></span>-->
<!--                <span class="pg-btn"></span>-->
            </div>

            <div class="field-inner __visit" id="_visit">
                <script Language="javaScript">getAjaxData('_visit', 'visitPath?ykiho=<?=$ykiho?>', 1)</script>
            </div>
            <div class="pagi-wrap __dark">
                <?php
                for ($i = $visitPage['spno']; $i <= $visitPage['epno']; $i++)
                {
                    ?>
                    <?php if ( $i == $visitPage['pno'] ) { ?>
                    <span class="pg-btn active" onclick="getAjaxData('_visit', 'visitPath?ykiho=<?=$ykiho?>', <?=$i?>)"></span>
                <?php } else { ?>
                    <span class="pg-btn" onclick="getAjaxData('_visit', 'visitPath?ykiho=<?=$ykiho?>', <?=$i?>)"></span>
                <?php } ?>
                    <?php
                }
                ?>
<!--                <span class="pg-btn"></span>-->
<!--                <span class="pg-btn"></span>-->
<!--                <span class="pg-btn"></span>-->
<!--                <span class="pg-btn"></span>-->
            </div>
        </div>
    </div>
</section>
</form>
</div>

</body>
</html>

<script>
    $(function () {
        let diagFldCd = $('#diagFldCd').val();
        let vstPthCd = $('#vstPthCd').val();

        if (diagFldCd && vstPthCd) {
            $('.btn-next').prop('disabled', false);
            $('.btn-next').addClass('clicked');
        }

        // $(document).on('click','.diagFldCd', function () {
        //     var diagFldCd = $(this).attr('data-value');
        //     var diagFldCdName = $(this).attr('data-name');
        //     $('#diagFldCd').val(diagFldCd);
        //     $('#diagFldCdName').val(diagFldCdName);
        // })
        //
        // $(document).on('click','.vstPthCd', function () {
        //     var vstPthCd = $(this).attr('data-value');
        //     var vstPthCdName = $(this).attr('data-name');
        //     $('#vstPthCd').val(vstPthCd);
        //     $('#vstPthCdName').val(vstPthCdName);
        // })

        $(document).on('click', '.__item', function () {
            var selCode = $(this).attr('data-value');
            var selCodeName = $(this).attr('data-name');
            var isSel = false;

            if ($(this).hasClass('diagFldCd') == true) {
                $('#diagFldCd').val(selCode);
                $('#diagFldCdName').val(selCodeName);
            } else if ($(this).hasClass('vstPthCd') == true) {
                $('#vstPthCd').val(selCode);
                $('#vstPthCdName').val(selCodeName);
            }

            if ($('#diagFldCd').val() && $('#vstPthCd').val()) isSel = true;

            valueChk(isSel, 'visit');
        })
    })

    function prevPage() {
        var frm						=	document.schForm;

        frm.target					=	"_self";
        frm.action					=	"address?ykiho=<?=$ykiho?>";
        frm.submit();
    }

    function nextPage() {
        var frm						=	document.schForm;

        frm.target					=	"_self";
        frm.action					=	"rectInfo?ykiho=<?=$ykiho?>";
        frm.submit();
    }
</script>
