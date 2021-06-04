<?php
/**
 *
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @package    PACMEC
 * @category   CRM
 * @license    license.txt
 * @version    1.0.1
 */
?>
<style>
[class^="grid-xs"]>li, [class*="grid-xs"]>li, [class^="grid-sm"]>li, [class*="grid-sm"]>li, [class^="grid-md"]>li, [class*="grid-md"]>li, [class^="grid-lg"]>li, [class*="grid-lg"]>li {
  height: 500px !important;
}
.feature {
  height: 430px !important;
  overflow-y: auto;
}
</style>
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3><?= _autoT('services'); ?></h3>
                <h4><?= _autoT('selected_service_continue'); ?></h4>
              </div>
                <ul class="grid-lg-3 grid-md-2 no-wrap">
                    <?php
                    foreach ($services as $service) {
                      ?>
                    <!--//
                      <li>
                          <div class="feature text-center">
                              <i class="<?= ($service->icon); ?>"></i>
                              <h4><?= ($service->name); ?></h4>
                              <p><?= ($service->description_short); ?></p>
                          </div>
                      </li>
                      services_page_promo_page_link
                    -->
                    <li style="cursor:pointer;" onclick="javascript:location.replace('<?= "?service_id={$service->id}"; ?>')">
                        <div class="feature icon-right">
                            <i class="<?= ($service->icon); ?>"></i>
                            <div class="feature-content">
                                <h5><?= ($service->name); ?></h5>
                                <p>
                                  <?= ($service->description_short); ?>
                                </p>
                            </div>
                        </div>
                    </li>
                      <?php
                    }
                    ?>
                </ul>
        </div>
    </div>
</section>
