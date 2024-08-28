<div class="__title">진료<br/>분야</div>
<div class="fieldTable">
    <?php
    if ($treatmentList) {
        foreach ($treatmentList as $item)
        {
            $bse_cd = $item['BSE_CD'];
            $bse_cd_nm = $item['BSE_CD_NM'];
            ?>

            <div class="__item diagFldCd <?php if ($bse_cd == $diagFldCd) echo 'active'?>" data-value="<?=$bse_cd?>" data-name="<?=$bse_cd_nm?>"><?=$bse_cd_nm?></div>
            <?php
        }
        for ($i = 0; $i < $recordPerPage - count($treatmentList); $i++) {
        ?>
            <div class="__item"></div>
    <?php
        }
    }
    ?>
</div>