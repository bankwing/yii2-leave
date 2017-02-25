<?php

namespace andahrm\leave\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use andahrm\structure\models\FiscalYear;


class Draft extends Leave
{
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'leave_type_id', 'start_part', 'end_part', 'acting_user_id', 'status', 'inspector_status', 'inspector_by', 'inspector_at', 'commander_status', 'commander_by', 'commander_at', 'director_status', 'director_by', 'director_at', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['to','date_start', 'date_end','commander_by', 'inspector_by','director_by','acting_user_id', 'contact'], 'required'],
        ];
    }
  
    public function scenarios(){
      $scenarios = parent::scenarios();
      
      $scenarios['insert'] = ['to','year','leave_type_id','contact', 'date_start', 'date_end','status','inspector_by','director_by','commander_by','start_part','end_part','contact','number_day'];
      $scenarios['create-vacation'] = ['to','year', 'leave_type_id','acting_user_id','contact', 'date_start', 'date_end','status','inspector_by','director_by','commander_by','start_part','end_part','contact','number_day'];
      $scenarios['create-sick'] = ['to','year','leave_type_id','reason','contact', 'date_start', 'date_end','start_part','date_end', 'inspector_by','director_by','commander_by','contact','number_day'];

      return $scenarios;
    }
    
    public function beforeValidate()
    {
         if($this->leave_type_id==1){
            $this->scenario = "create-vacation";
         }else{
            $this->scenario = "create-sick";
         }
         
         $this->number_day = self::calCountDays($this->date_start,$this->date_end,$this->start_part,$this->end_part);
         
          if(($this->date_start == $this->date_end) && ($this->start_part !== $this->end_part)){                   
                $this->addError('end_part',Yii::t('app','Not match! 1'));                  
            }
            
            if($this->date_start < $this->date_end){
                if(($this->start_part == Leave::HALF_DAY_MORNIG) || ($this->end_part ==  Leave::LATE_AFTERNOON)){                   
                 $this->addError('end_part',Yii::t('app','Not match! 2'));
                }
            }
         
         
         
        if (parent::beforeValidate()) {
            return true;
        }
        return false;
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Place your custom code here
            $this->number_day = self::calCountDays($this->date_start,$this->date_end,$this->start_part,$this->end_part);
            return true;
        } else {
            return false;
        }
    }
    
    
    public static function getReasonList(){
        $model = self::find()->select('reason')->distinct()->where([
            'created_by'=>Yii::$app->user->id
            ])->groupBy('reason')->all();
        return ArrayHelper::getColumn($model,'reason');
    }
    
     public static function getContactList(){
        $model = self::find()->select('contact')->distinct()->where(['created_by'=>Yii::$app->user->id])->groupBy('contact')->all();
        return ArrayHelper::getColumn($model,'contact');
    }
  
  
}
