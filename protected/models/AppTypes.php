<?php

/**
 * This is the model class for table "app_types".
 *
 * The followings are the available columns in table 'app_types':
 * @property string $id
 * @property string $title
 */
class AppTypes extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'app_types';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title', 'required'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title', 'safe', 'on'=>'search'),
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
			'orders'=>array(self::HAS_MANY, 'Orders','app_type_id'),
			'doctors'=>array(self::MANY_MANY, 'Doctors','lnk_app_doctors(app_id,doctor_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('title',$this->title,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AppTypes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Возвращает список типов приёма ко врачу.
	 * @param string $condition
	 * @param array $params
	 * @return static[]
	 */
	public static function getList($condition='',$params=array())
	{
		$criteria = self::model()->getCommandBuilder()->createCriteria($condition, $params);
		return self::model()->findAll($criteria);
	}

	/**
	 * Возвращает длительность конкретного типа приёма указанного врача
	 * @param int $doctor_id идентификатор доктора
	 * @param $app_id идентификатор типа приёма
	 * @return int длительность приёма в минутах
	 */
	public static function getDuration($doctor_id,$app_id) {
		$return = Yii::app()->db->createCommand()
			->select('duration')
			->from('lnk_app_doctors')
			->where('doctor_id=:doctor_id',array('doctor_id'=>$doctor_id))
			->andWhere('app_id=:app_id',array('app_id'=>$app_id))
			->queryRow();

		return $return['duration'];
	}
}
