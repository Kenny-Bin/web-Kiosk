<div class="__title __dark">내원<br/>분야</div>
<div class="fieldTable">
    <?php
    if ($visitList) {
        foreach ($visitList as $item)
        {
            $bse_cd = $item['BSE_CD'];
            $bse_cd_nm = $item['BSE_CD_NM'];
            ?>

            <div class="__item vstPthCd <?php if ($bse_cd == $vstPthCd) echo 'active'?>" data-value="<?=$bse_cd?>" data-name="<?=$bse_cd_nm?>"><?=$bse_cd_nm?></div>
            <?php
        }
        for ($i = 0; $i < $recordPerPage - count($visitList); $i++) {
        ?>
        <div class="__item"></div>
    <?php }
    }
    ?>
</div>