<?php
use yii\bootstrap\Html;
//use yii\widgets\Menu;
use yii\bootstrap\Nav;
use dmstr\widgets\Menu;
use mdm\admin\components\Helper;

 $this->beginContent('@app/views/layouts/main.php'); 
 $module = $this->context->module->id;
$controller = Yii::$app->controller->id;

?>

<?php #if(Yii::$app->user->can('manage-leave')):?>
<div class="row ">
    <div class="col-md-3 hidden-print"> 
      
      <?php
      $active = false;
      switch($controller){
          case 'default':
          case 'commander':
          case 'inspactor':
          case 'director':
            $active = true;
          break;
      }
      
                    $menuItems = [];      
      
                    $menuItems[] =  [
                            'label' => Yii::t('andahrm/leave', 'Leave Day Offs'),
                            'url' => ["/{$module}/day-off/"],
                            'icon' => 'fa fa-sitemap'
                     ];
      
                    $menuItems[] =  [
                            'label' =>  Yii::t('andahrm/leave', 'Leave Types'),
                            'url' => ["/{$module}/type/"],
                            'icon' => 'fa fa-sitemap'
                    ];                 
                       
      
//                    $menuItems[] =  [
//                            'label' => Yii::t('andahrm/leave', 'Leave Conditions'),
//                            'url' => ["/{$module}/condition/"],
//                            'icon' => 'fa fa-sitemap'
//                     ];
      
                      $menuItems[] =  [
                           'label' => Yii::t('andahrm/leave', 'Leave Permissions'),
                            'url' => ["/{$module}/permission/"],
                            'icon' => 'fa fa-sitemap'
                     ];
      
                    $menuItems[] =  [
                            'label' =>  Yii::t('andahrm/leave', 'Leave Relateds'),
                            'url' => ["/{$module}/related/"],
                            'icon' => 'fa fa-sitemap'
                     ];
      
//                     $menuItems[] =  [
//                             'label' => Html::icon('inbox') . ' ' . Yii::t('andahrm/leave', 'Leave Related People'),
//                             'url' => ["/{$module}/related-person/"],
//                      ];
                    //print_r($menuItems);
                    //echo "<hr/>";
                    $menuItems = Helper::filter($menuItems);
                    $newMenu = [];
                    foreach($menuItems as $k=>$menu){
                      $newMenu[$k]=$menu;
                      $newMenu[$k]['url'][0] = rtrim($menu['url'][0], "/");
                    }
                    $menuItems=$newMenu;
                    //print_r($menuItems);
                    //$nav = new Navigate();
                    echo Menu::widget([
                        'options' => ['class' => 'nav nav-pills nav-stacked'],
                        'encodeLabels' => false,
                        //'activateParents' => true,
                        //'linkTemplate' =>'<a href="{url}">{icon} {label} {badge}</a>',
                        'items' => $menuItems,
                    ]);
                    ?>
      
      
     
      
    </div>

    <div class="col-md-9">
      
        <div class="x_panel tile">
            <div class="x_title">
                <h2><?= $this->title; ?></h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <?php echo $content; ?>
                <div class="clearfix"></div>
            </div>
        </div>
      
    </div>
</div>
<?php #endif;?>

<?php $this->endContent(); ?>
