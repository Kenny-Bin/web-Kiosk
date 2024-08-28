<?php include APPPATH.'Views/inc/top.php'; ?>

<body>
<div id="kiosk" class="">
    <header class="scHeader agreeChk" id="scHeader">
        <div class="maxframe">
            <nav>
                <button type="button" class="nav_btn btn-prev" onclick="prevPage()">이전</button>
                <span>입력한 정보 확인 후 개인정보 활용 및 수집 동의 체크</span>
            </nav>
        </div>
    </header>

<form id="schForm" name="schForm" method="post">
<section class="scInfo">
    <input type="hidden" id="visitChk" name="visitChk" value="<?=$visitChk?>">
    <input type="hidden" id="patNm" name="patNm" value="<?=$patNm?>">
    <input type="hidden" id="search" name="search" value="<?=$search?>">
    <input type="hidden" id="jno" name="jno" value="<?=$jno?>">
    <input type="hidden" id="fullAddress" name="fullAddress" value="<?=$fullAddress?>">
    <input type="hidden" id="addr" name="addr" value="<?=$addr?>">
    <input type="hidden" id="dtl_addr" name="dtl_addr" value="<?=$dtl_addr?>">
    <input type="hidden" id="diagFldCd" name="diagFldCd" value="<?=$diagFldCd?>">
    <input type="hidden" id="vstPthCd" name="vstPthCd" value="<?=$vstPthCd?>">
    <div class="minframe">
        <div class="titleBox">
            <h1>접수정보</h1>
        </div>
        <div class="info-wrap">
            <div class="info-inner">
                <div class="info-item">
                    <label for="" class="__ttl">이름</label>
                    <input type="text" id="patNm" name="patNm" value="<?=$patNm?>" readonly>
                </div>
                <div class="info-item">
                    <label for="" class="__ttl">휴대폰번호</label>
                    <input type="text" id="search" name="search" value="<?=$search?>" readonly>
                </div>
                <div class="info-item">
                    <label for="" class="__ttl">주민등록번호</label>
                    <input type="text" id="mkJno" name="mkJno" value="<?=$mkJno?>" readonly>
                </div>
                <div class="info-item">
                    <label for="" class="__ttl">주소</label>
                    <input type="text" id="fullAddress" name="fullAddress" value="<?=$fullAddress?>" readonly>
                </div>
                <div class="info-item">
                    <label for="" class="__ttl">진료분야</label>
                    <input type="text" id="diagFldCdName" name="diagFldCdName" value="<?=$diagFldCdName?>" readonly>
                </div>
                <div class="info-item">
                    <label for="" class="__ttl">내원경로</label>
                    <input type="text" id="vstPthCdName" name="vstPthCdName" value="<?=$vstPthCdName?>" readonly>
                </div>
            </div>
            <div class="info-item __msg">
                <div class="__ttl">문자수신</div>
                <div class="input-wrap">
                    <input type="radio" name="smsYn" id="rcv_agree" value="1" checked>
                    <label for="rcv_agree" class="__sub-ttl">수신</label>
                    <input type="radio" name="smsYn" id="rcv_disagree" value="0">
                    <label for="rcv_disagree" class="__sub-ttl">수신거부</label>
                </div>
            </div>
            <div class="aree-wrap __agree">
                <div class="info-item">
                    <div class="__ttl">이용약관동의</div>
                    <div class="input-wrap">
                        <input type="checkbox" class="__sub-ttl" id="full_agree" onclick="chkAll();">
                        <label for="full_agree" class="__sub-ttl">전체동의</label>
                    </div>
                </div>
                <div class="aree-inner">
                    <div class="agree-item">
                        <input type="checkbox" class="agree pvcy" id="pvcy_agree" name="prsnYn" value="1">
                        <label for="pvcy_agree" class="__sub-ttl">개인정보 활용 및 수집에 대한 처리 방침</label>
                        <span class="btn-pvcyView">전문보기</span>
                        <div class="pvcy-detail">
                            <div class="pvcy-wrap">
                                <h3 class="">개인정보 처리방침</h3>
                                <div class="minframe">
                                    <p>
                                        <트라이업>은 개인정보 보호법 제30조에 따라 정보주체(고객)의 개인 정보를 보호하고 이와 관련한 고충을 신속하고 원활하게 처리할 수 있도록 하기 위하여 다음과 같이 개인 정보 처리 지침을 수립·공개합니다.
                                            <br/>
                                            1. 개인 정보 수집 이용 목적 <트라이업>은 다음의 목적을 위하여 개인 정보를 처리하고 있으며, 다음의 목적 이외의 용도로는 이용하지 않습니다. 환자의 치료와 보험 청구 등의 업무 / 서비스 제공에 관한 계약 이행 및 서비스 제공에 따른 콘텐츠 제공 / 서비스 이용에 따른 본인확인, 불량 회원의 부정 이용 방지와 비인가 사용 방지, 불만 처리 등 민원처리, 고지사항  전달 / 마케팅및 광고에 활용 이벤트 및 광고 성 정보 제공 및 참여 기회 제공.
                                                <br/>
                                                임시내용 임시내용<br/>
                                                <트라이업>은 개인정보 보호법 제30조에 따라 정보주체(고객)의 개인 정보를 보호하고 이와 관련한 고충을 신속하고 원활하게 처리할 수 있도록 하기 위하여 다음과 같이 개인 정보 처리 지침을 수립·공개합니다.
                                    </p>
                                    <button type="button" class="btn-pvcyClose">닫기</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="agree-item">
                        <input type="checkbox" class="agree sms" id="sms_agree" name="adSmsYn" value="1">
                        <label for="sms_agree" class="__sub-ttl">이벤트 및 광고, 혜택 알림 SMS 수신(선택 동의)</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-wrap">
            <button type="button" class="acpt-btn" onclick="formWrite()" disabled>접수</button>
        </div>
    </div>
</section>
</form>
</div>
<div id="rectPopUp"></div>
<div id="smsPopUp" style="display: none;">
    <div id="kiosk" class="">
        <div class="popUp alerted-ver2">
            <div class="popWrap">
                <div class="pop-inner">
                    <p class="">
                        문자수신 거부 시 예약, 진료 관련 안내문자 수신도 거부됩니다.
                    </p>
                    <div class="btn-wrap">
                        <button type="button" class="pop-btn" onclick="closePopup()">확인</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

<script>
    $(function () {
        $('.agree').click(function () {
            if ( $('.agree:checked').length === 2) {
                $('#full_agree').prop('checked',true);
            } else {
                $('#full_agree').prop('checked',false);
            }

            if ($(this).hasClass('pvcy') == true) {
                if ($('.pvcy').prop('checked') == true) {
                    $('.acpt-btn').prop('disabled', false);
                    $('.acpt-btn').addClass('active');
                } else {
                    $('.acpt-btn').prop('disabled', true);
                    $('.acpt-btn').removeClass('active');
                }
            }

        })

        $('#rcv_disagree').click(function () {
            $('#smsPopUp').show();
        });

    })

    function prevPage() {
        var frm						=	document.schForm;

        frm.target					=	"_self";
        frm.action					=	"visitInfo?ykiho=<?=$ykiho?>";
        frm.submit();
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

    function closePopup() {
        $('#smsPopUp').hide();
    }

    function chkAll() {
        if ( $('#full_agree').prop('checked') == true) {
            $('.agree:checkbox').prop('checked', true);
            $('.acpt-btn').prop('disabled', false);
            $('.acpt-btn').addClass('active');
        } else {
            $('.agree:checkbox').prop('checked', false);
            $('.acpt-btn').prop('disabled', true);
            $('.acpt-btn').removeClass('active');
        }
    }
</script>
