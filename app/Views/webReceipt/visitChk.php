<?php include APPPATH.'Views/inc/top.php'; ?>

<body>
<div id="kiosk" >
    <header class="scHeader" id="scHeader">
        <div class="maxframe">
        </div>
    </header>
    <section class="scIntro">
        <div class="minframe">
            <div class="titleBox">
                <span>안녕하세요.</span>
                <h1>내원 여부 선택</h1>
            </div>
            <div class="btnWrap">
                <button type="button" class="btn-inner __first" onclick="receiptPage('first', 'name');">
                    <figure>
                        <img src="/img/i-first-w.svg" alt="처음 내원">
                    </figure>
                    <span class="btn-ttl">처음 내원</span>
                </button>
                <button type="button" class="btn-inner __second" onclick="receiptPage('after', 'name');">
                    <figure>
                        <img src="/img/i-sec-w.svg" alt="내원한 적 있음">
                    </figure>
                    <span class="btn-ttl">내원한 적 있음</span>
                </button>
            </div>
        </div>
    </section>
</div>

</body>
</html>

<script>
    function receiptPage(visitChk, getURL) {
        location.href = `${getURL}?visitChk=${visitChk}&ykiho=<?=$ykiho?>`;
    }
</script>
