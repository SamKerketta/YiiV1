<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_logs".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int $status
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class TablLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'password'], 'required'],
            [['status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'email', 'password'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    /**
     * Summary of saveLogs
     * @param mixed $logData
     * @return void
     */
    public function saveLogs($logData)
    {
        $log = new TablLog();
        $log->name      = $logData['name'];
        $log->email     = $logData['email'];
        $log->password  = $logData['password'];
        $log->save();
    }
}
