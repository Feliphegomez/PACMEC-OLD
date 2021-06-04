<?php
/**
 *
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @package    PACMEC
 * @category   AMS
 * @license    license.txt
 * @version    1.0.1
 */
function pacmec_section_memberships($atts, $content="")
{
  $args = \shortcode_atts([
    "class"   => "section",
    "title"   => false,
    "content"   => false,
    "limit"   => 3,
  ], $atts);
  $args['content'] = $args['content']==false && !empty($content) ? $content : ($args['content']==false) ? $args['content'] : \_autoT($args['content']).\do_shortcode($content);
  $memberships = \PACMEC\AMS\Memberships::allLoad($args['limit']);
  $items_html = "";
  foreach ($memberships as $m) {
    $pricing_currency_p = \PHPStrap\Util\Html::tag('p', (!empty($m->initial_payment)&&$m->initial_payment>0?\_autoT("memberships_initial_payment")." ".\formatMoney($m->initial_payment):\_autoT("memberships_not_initial_payment")), []);
    $pricing_currency_span = \PHPStrap\Util\Html::tag('span', "$", ['currency']);
    $label_period = ((int) $m->cycle_number>=2) ? _autoT("period_p_{$m->cycle_period}") : (((int) $m->cycle_number==1) ? _autoT("period_s_{$m->cycle_period}") : "");
    $pricing_currency_small = \PHPStrap\Util\Html::tag('small', " / {$m->cycle_number} ".$label_period, []);
    $pricing_price = \PHPStrap\Util\Html::tag('div', "{$pricing_currency_span} ".\formatMoneySingle($m->total_payment)." {$pricing_currency_small}{$pricing_currency_p}", ['pricing-price']);
    $pricing_title = \PHPStrap\Util\Html::tag('div', "$m->name", ['pricing-title']);
    $pricing_header = \PHPStrap\Util\Html::tag('div', $pricing_title.$pricing_price, ['pricing-header']);
    $sub_ul = "";
    $sub2_ul = "";
    foreach ($m->services as $service) {
      $more = "";
      $more .= (($service->limit_day!==null && !empty($service->limit_day)) ? $service->limit_day."&nbsp;"."x "._autoT("period_Day")." <br/>" : "")."";
      $more .= (($service->limit_week!==null && !empty($service->limit_week)) ? $service->limit_week."&nbsp;"."x "._autoT("period_Week")." <br/>" : "")."";
      $more .= (($service->limit_month!==null && !empty($service->limit_month)) ? $service->limit_month."&nbsp;"."x "._autoT("period_Month")." <br/>" : "")."";
      $more .= (($service->limit_year!==null && !empty($service->limit_year)) ? $service->limit_year."&nbsp;"."x "._autoT("period_Year")." <br/>" : "")."";
      if($service->days!==null && !empty($service->days)){
          if(count(explode(",", $service->days)) > 0){
            $more .= "Dias: ";
            $ds = [];
            foreach(explode(",", $service->days) as $d){ $ds[] = _autoT($d); }
            $more .= implode(',', $ds);
          }
      };
      $more .= ($service->req_reservation>=1) ? _autoT('req_reservation')." <br/>" : "";
      if(!empty($more)){
        $strong_1 = \PHPStrap\Util\Html::tag('strong', $service->service->name." \n <br/>", [],  []);
        $p_1 = \PHPStrap\Util\Html::tag('p', $more, [],  ["style"=>"text-align:justify;"]);
        $sub2_ul .= \PHPStrap\Util\Html::tag('li', ($strong_1.$p_1), [],  []);
      }
      $sub_icon = \PHPStrap\Util\Html::tag('i', '', [$service->service->icon],  ["data-toggle"=>"popover", "data-html"=>"true", "data-content"=>(!empty($more)?$more:"None"), "data-placement"=>"top", "title"=>_autoT("memberships_limits")]);
      $sub_title = \PHPStrap\Util\Html::tag('strong', $service->service->name, []);
      $sub_ul .= \PHPStrap\Util\Html::tag('li', $sub_icon.$sub_title._autoT("memberships_type_{$service->type}")." ", []);
    }
    $pricing_action_btn1 = \PHPStrap\Util\Html::tag('a', _autoT('order_now'), ['btn btn-default'], []);
    $pricing_action = \PHPStrap\Util\Html::tag('div', $pricing_action_btn1, ['pricing-action'], []);
    $pricing_content = \PHPStrap\Util\Html::tag('ul', $sub_ul, ['grid-md-2 grid-sm-2 grid-xs-2 pricing-content pricing-icon']);
    $pricing_content2 = \PHPStrap\Util\Html::tag('ul', $sub2_ul, ['pricing-content']);
    $pricing_plan = \PHPStrap\Util\Html::tag('div', $pricing_header.$pricing_content.$pricing_content2.$pricing_action, ['pricing-plan']);
    $items_html .= \PHPStrap\Util\Html::tag('li', $pricing_plan, [], []);
  }
  $ul = \PHPStrap\Util\Html::tag('ul', $items_html, ['grid-md-3 grid-sm-1 grid-xs-1 no-wrap'], []);
  $text = $args['content']==false?"":\PHPStrap\Util\Html::tag('p', ($args['content']), [], []);
  $title = $args['title']==false?"":\PHPStrap\Util\Html::tag('h3', _autoT($args['title']), ['no-margin'], []);
  $header = \PHPStrap\Util\Html::tag('div', $title.$text, ['heading-title no-border gap'], ["data-gap-bottom"=>"60"]);
  $col = \PHPStrap\Util\Html::tag('div', $header.$ul, ['col-md-12'], []);
  $row = \PHPStrap\Util\Html::tag('div', $col, ['row'], []);
  $container = \PHPStrap\Util\Html::tag('div', $row, ['container'], []);
  return \PHPStrap\Util\Html::tag('section', $container, [$args['class']], []);
}
add_shortcode('section-memberships', 'pacmec_section_memberships');
