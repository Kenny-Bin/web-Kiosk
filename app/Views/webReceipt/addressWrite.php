<?php include APPPATH.'Views/inc/top.php'; ?>

<?php include APPPATH.'Views/inc/nav.php'; ?>

    <form id="schForm" name="schForm" method="post">
    <section class="scAddress">
        <input type="hidden" id="visitChk" name="visitChk" value="<?=$visitChk?>">
        <input type="hidden" id="patNm" name="patNm" value="<?=$patNm?>">
        <input type="hidden" id="search" name="search" value="<?=$search?>">
        <input type="hidden" id="jno" name="jno" value="<?=$jno?>">
        <input type="hidden" id="mkJno" name="mkJno" value="<?=$mkJno?>">

        <div class="minframe">
            <div class="titleBox">
                <h1>주소 입력</h1>
                <span>지번(동/읍/면/리) 또는 도로명을 검색하여 상세 주소를 입력해 주세요.</span>
            </div>
            <label for="" class="contBox search-inner">
                <input type="text" id="schAddr_text" name="schAddr_text" placeholder="지번 / 도로명">
                <button type="button" class="sch-btn" onclick="openPostcode();">검색</button>
            </label>
            <label for="" class="contBox address-inner">
                <input type="text" id="addr" name="addr" placeholder="주소" readOnly value="<?=$addr?>">
            </label>
            <label for="" class="contBox">
                <input type="text" id="dtl_addr" name="dtl_addr" placeholder="상세주소" value="<?=$dtl_addr?>">
            </label>
        </div>
    </section>
    </form>
</div>

<script>
    $(function () {
        if ($('#addr').val()) {
            $('.btn-next').prop('disabled', false);
            $('.btn-next').addClass('clicked');
        }
    })

    function prevPage() {
        var frm						=	document.schForm;

        frm.target					=	"_self";
        frm.action					=	"jno?ykiho=<?=$ykiho?>";
        frm.submit();
    }

    function nextPage() {
        var frm						=	document.schForm;

        frm.target					=	"_self";
        frm.action					=	"visitInfo?ykiho=<?=$ykiho?>";
        frm.submit();
    }

    function openPostcode()
    {
        var frm						=	document.schForm;

        new daum.Postcode({
            oncomplete				:	function(data)
            {
                var userSelectedType		=	data.userSelectedType;

                if ( userSelectedType == 'R' ) {																		//	사용자가 도로명 주소를 선택한 경우
                    var address				=	data.roadAddress;
                } else {																								//	사용자가 지번 주소를 선택한 경우
                    var address				=	data.jibunAddress;
                }
                    frm.addr.value		=	address;
                    frm.dtl_addr.focus();

                valueChk(frm.addr.value, 'address');

            }
        }).open({
            q : frm.schAddr_text.value
        });
    }
</script>
