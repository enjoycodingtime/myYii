<?php

/**
 * This is the model class for table "t_access_token".
 *
 * The followings are the available columns in table 't_access_token':
 * @property string $FuiId
 * @property string $FstrName
 * @property string $FstrAppid
 * @property string $FstrAppkey
 * @property string $FstrAccessToken
 * @property string $FstrJsApiTicket
 * @property string $FuiExpireTime
 * @property string $FuiCreateTime
 * @property string $FuiUpdateTime
 */
class AccessToken extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 't_access_token';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('FstrName, FstrAppid, FstrAppkey, FstrAccessToken, FuiExpireTime, FuiCreateTime, FuiUpdateTime', 'required'),
			array('FstrName, FstrAppid, FstrAppkey, FstrJsApiTicket', 'length', 'max'=>128),
			array('FstrAccessToken', 'length', 'max'=>1024),
			array('FuiExpireTime, FuiCreateTime, FuiUpdateTime', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('FuiId, FstrName, FstrAppid, FstrAppkey, FstrAccessToken, FstrJsApiTicket, FuiExpireTime, FuiCreateTime, FuiUpdateTime', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'FuiId' => 'Fui',
			'FstrName' => 'Fstr Name',
			'FstrAppid' => 'Fstr Appid',
			'FstrAppkey' => 'Fstr Appkey',
			'FstrAccessToken' => 'Fstr Access Token',
			'FstrJsApiTicket' => 'Fstr Js Api Ticket',
			'FuiExpireTime' => 'Fui Expire Time',
			'FuiCreateTime' => 'Fui Create Time',
			'FuiUpdateTime' => 'Fui Update Time',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('FuiId',$this->FuiId,true);
		$criteria->compare('FstrName',$this->FstrName,true);
		$criteria->compare('FstrAppid',$this->FstrAppid,true);
		$criteria->compare('FstrAppkey',$this->FstrAppkey,true);
		$criteria->compare('FstrAccessToken',$this->FstrAccessToken,true);
		$criteria->compare('FstrJsApiTicket',$this->FstrJsApiTicket,true);
		$criteria->compare('FuiExpireTime',$this->FuiExpireTime,true);
		$criteria->compare('FuiCreateTime',$this->FuiCreateTime,true);
		$criteria->compare('FuiUpdateTime',$this->FuiUpdateTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AccessToken the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
