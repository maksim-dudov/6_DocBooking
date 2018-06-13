<?php

/**
 * This is the model class for table "orders".
 *
 * The followings are the available columns in table 'orders':
 * @property string $id
 * @property string $client_id
 * @property string $doctor_id
 * @property string $datetime_app
 * @property string $datetime_creation
 * @property string $datetime_cancellation
 * @property integer $duration
 * @property string $app_type_id
 * @property integer $cancelled
 */
class Orders extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'orders';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('client_id, doctor_id, datetime_app, datetime_creation, app_type_id', 'required'),
			array('duration, cancelled', 'numerical', 'integerOnly'=>true),
			array('client_id, doctor_id, app_type_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, client_id, doctor_id, datetime_app, datetime_creation, datetime_cancellation, duration, app_type_id', 'safe', 'on'=>'search'),
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
			'doctor'=>array(self::BELONGS_TO, 'Doctors','doctor_id'),
			'client'=>array(self::BELONGS_TO, 'Clients','client_id'),
			'app'=>array(self::BELONGS_TO, 'AppTypes','app_type_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'client_id' => 'Client',
			'doctor_id' => 'Doctor',
			'datetime_app' => 'Datetime App',
			'datetime_creation' => 'Datetime Creation',
			'datetime_cancellation' => 'Datetime Cancellation',
			'duration' => 'Duration',
			'app_type_id' => 'App Type',
			'cancelled' => 'Cancelled',
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
		$criteria->compare('client_id',$this->client_id,true);
		$criteria->compare('doctor_id',$this->doctor_id,true);
		$criteria->compare('datetime_app',$this->datetime_app,true);
		$criteria->compare('datetime_creation',$this->datetime_creation,true);
		$criteria->compare('datetime_cancellation',$this->datetime_cancellation,true);
		$criteria->compare('duration',$this->duration);
		$criteria->compare('app_type_id',$this->app_type_id,true);
		$criteria->compare('cancelled',$this->cancelled);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Orders the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Добавляет запись ко врачу
	 * @param int $doctor_id идентификатор врача
	 * @param DateTime $datetime дата и время начала приёма
	 * @param int $app_type идентификатор типа приёма
	 * @return bool результат сохранения записи в базе
	 * @todo закрытие в Dashboard можно вынести в afterSave()
	 */
	public function makeOrder($doctor_id, DateTime $datetime, $app_type){
		$order = new Orders();

		$order->client_id=1;

		$order->doctor_id = $doctor_id;
		$order->datetime_app = $datetime->format('Y-m-d H:i:s');

		$datetime_creation = new DateTime('now');
		$order->datetime_creation = $datetime_creation->format('Y-m-d H:i:s');
		$order->app_type_id = $app_type;
		$order->cancelled = 0;

		$return = $order->save();

		$dashboard = new Dashboard();
		$dashboard->closeWindow($order->doctor_id,$order->datetime_creation,$order->app_type_id);

		return $return;
	}

	/**
	 * Отменяет запись ко врачу
	 * @param int $order_id идентификатор записи
	 */
	protected function cancelOrder(int $order_id){}

}
