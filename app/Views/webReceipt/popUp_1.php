<div id="kiosk" class="">
    <input type="hidden" id="search" name="search" value="<?=$search?>">
    <input type="hidden" id="patNm" name="patNm" value="<?=$patNm?>">
<div class="popUp">
    <div class="popWrap">
        <div class="pop-inner">
            <p>입력하신 정보가 존재합니다.</p>
            <div for="" class="cs-info">
                <span class="__name"><?=$patNm?></span>
                <span><?=$search?></span>
            </div>
            <p>으로 접수하시겠습니까?</p>
            <div class="btn-wrap">
                <button type="button" class="pop-btn __no" onclick="backPage();">아니오</button>
                <button type="button" class="pop-btn __yes" onclick="formWrite();">예</button>
            </div>
        </div>
    </div>
</div>
</div>
