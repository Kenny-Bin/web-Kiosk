<div id="kiosk" class="">
    <div class="popUp pop-ver2">
        <div class="popWrap">
            <div class="pop-inner">
                <div class="listWrap">
                    <?php
                    if ($patInfo) {
                        foreach ($patInfo as $val) {
                            $patNm = $val['PAT_NM'];
                            $patBth = $val['PAT_BTH'];
                            $mobile = $val['MOBILE_NO'];
                    ?>
                        <div class="list patInfo">
                            <div class="__name" data-value="<?=$patNm?>"><?=$patNm?></div>
                            <div class="__birth" data-value="<?=$patBth?>"><?=$patBth?></div>
                            <div class="__phone" data-value="<?=$mobile?>"><?=$mobile?></div>
                        </div>
                    <?php
                        }
                    }
                    ?>
                </div>
                <div class="btn-wrap">
                    <button class="pop-btn __no" onclick="backPage();">취소</button>
                    <button class="pop-btn __yes" onclick="formWrite();">확인</button>
                </div>
            </div>
        </div>
    </div>
</div>
