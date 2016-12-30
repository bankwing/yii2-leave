<?php
use yii\bootstrap\Html;
//use yii\widgets\Menu;
use yii\bootstrap\Nav;
use dmstr\widgets\Menu;
use mdm\admin\components\Helper;

 $this->beginContent('@andahrm/leave/views/layouts/main.php'); 
 $module = $this->context->module->id;


                    $menuItems = [];
      
                    $menuItems[] =  [
                           'label' => '<i class="fa fa-sitemap"></i> ' . Yii::t('andahrm/leave', 'Self'),
                            'url' => ["/{$module}/default/"],
                     ]; 
                    
      
                    $menuItems[] =  [
                           'label' => '<i class="fa fa-sitemap"></i> ' . Yii::t('andahrm/leave', 'Commander').' <span class="badge bg-red">6</span>',
                            'url' => ["/{$module}/commander/"],
                     ];
      
      
                    $menuItems[] =  [
                            'label' => Html::icon('inbox') . ' ' . Yii::t('andahrm/leave', 'Inspactor'),
                            'url' => ["/{$module}/inspactor/"],
                     ];   
      
                     $menuItems[] =  [
                            'label' => Html::icon('inbox') . ' ' . Yii::t('andahrm/leave', 'Director'),
                            'url' => ["/{$module}/director/"],
                     ];
      
                    $menuItems = Helper::filter($menuItems);
                    $newMenu = [];
                    foreach($menuItems as $k=>$menu){
                      $newMenu[$k]=$menu;
                      $newMenu[$k]['url'][0] = rtrim($menu['url'][0], "/");
                    }
                    $menuItems=$newMenu;
?>

<div class="row hidden-print">
  <?php if(count($menuItems)>1):  
  ?>
    <div class="col-md-12">  
          <?php
          //$nav = new Navigate();
          echo Menu::widget([
              'options' => ['class' => 'nav nav-tabs'],
              'encodeLabels' => false,
              //'activateParents' => true,
              //'linkTemplate' =>'<a href="{url}">{icon} {label} {badge}</a>',
              'items' => $menuItems,
          ]);
          ?>
    </div>

  
    <div class="col-md-12">
      <div class="x_panel">
            <div class="x_content">
                <?php echo $content; ?>
                <div class="clearfix"></div>
            </div>
        </div>               
     </div>
  <?php else:?>
  <div class="col-md-12">
     <?php echo $content; ?>         
  </div>
  <?php endif;?>
</div>

<?php $this->endContent(); ?>