<?php
namespace jackh\dashboard\models;

use yii\db\ActiveRecord;
use yii\helpers\Url;
use common\models\User;
use nhkey\arh\managers\BaseManager;
/**
 * This is the model class for table "News".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $isShow
 * @property string $createtime
 * @property integer $type
 */
class ModelHistory extends ActiveRecord {

	public static function tableName() {
		return 'modelhistory';
	}

	public static function typeName() {
		return [
			BaseManager::AR_INSERT => '添加',
			BaseManager::AR_UPDATE => '更新',
			BaseManager::AR_DELETE => '删除',
			BaseManager::AR_UPDATE_PK => '主键变更',
		];
	}

	public function attributeLabels() {
		return [
			'id' => 'ID',
			'date' => '时间',
			'table' => '表名',
            'field_name' => '字段名',
            'field_id' => '字段编号',
            'old_value' => '旧值',
            'new_value' => '新值',
			'type' => '类型',
            'user_id' => '用户编号'
		];
	}

    public function getUser() {
        return $this->hasOne(User::className(), ["user_id" => "user_id"]);
    }
}
