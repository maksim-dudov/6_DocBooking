<?php

/**
 * This is the model class for table "dashboard".
 *
 * The followings are the available columns in table 'dashboard':
 * @property string $id
 * @property string $doctor_id
 * @property string $datetime
 * @property string $duration
 */
class Dashboard extends CActiveRecord
{
	/**
	 * Час начала приёма врачей
	 * @var int
	 */
	protected $day_start_hour = 9;

	/**
	 * Час окончания приёма врачей
	 * @var int
	 */
	protected $day_end_hour = 21;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dashboard';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('doctor_id, datetime, duration', 'required'),
			array('doctor_id, duration', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, doctor_id, datetime, duration', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'doctor_id' => 'Doctor',
			'datetime' => 'Datetime',
			'duration' => 'Duration',
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
		$criteria->compare('doctor_id',$this->doctor_id,true);
		$criteria->compare('datetime',$this->datetime,true);
		$criteria->compare('duration',$this->duration,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Dashboard the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Возвращает список окон для записи к конкретному доктору на конкретную дату.
	 * @param int $doctor_id идентификатор доктора
	 * @param $date дата
	 */
	public static function getWindowsByDoctorAndDate($doctor_id, $date)	{
		$return = array();
		$is_day_open = $this->checkDayIsOpen($doctor_id,$date);
		// если день у врача полностью свободен - формируем стек последовательного времени для записи
		if($is_day_open) {
			$return = array_merge($return, $this->makeListForEmptyDay($doctor_id, $date));
		}
		// если уже какие-то записи есть, то перебираем имеющиеся окна и в каждом их них формируем последовательный
		// список времени для записи
		else {
			$windows = array();
			foreach($windows as $window) {
				$return = array_merge($return, $this->makeListForWindow($doctor_id, $window['start'], $window['end']));
			}
		}
		$return[] = new DateTime('2018-06-13 09:30:00');
		$return[] = new DateTime('2018-06-13 12:00:00');
		$return[] = new DateTime('2018-06-13 14:30:00');
		return $return;
	}

	/**
	 * Закрывает свободное окно записи ко врачу.
	 * NB! Закомментированный код - заглушка. Инвертировать логику: таблица хранит свободные окна.
	 * @param int $doctor_id идентификатор доктора
	 * @param Datetime $datetime метка даты и времени, начало закрываемого окна
	 * @param int $app_type идентификатор типа приёма.
	 * @todo если уходить от строгого списка типов приёма и переходить к созданию записи на произвольное время (а не
	 * строго определённое заранее по типам приёма), то параметр app_type надо заменить на int $duration в минутах
	 */
	public function closeWindow($doctor_id, $datetime, $app_type_id) {
// 		Заглушка - инвертировать логику
//		$window = new Dashboard();
//		$window->doctor_id = $doctor_id;
//		$window->datetime = $datetime;
//		$window->duration = AppTypes::getDuration($doctor_id,$app_type_id);
//		$window->save();

		$this->flushDashboardByDoctor($doctor_id);
	}

	/**
	 * Сбрасывает все данные по окнам и пересчитывает их заново на основании имеющихся записей.
	 * @return void
	 */
	public function flushDashboard() {
	}

	/**
	 * Сбрасывает данные по окнам записи к конкретному врачу
	 * @param int $doctor_id идентификатор доктора
	 * @return void
	 */
	protected function flushDashboardByDoctor($doctor_id) {
	}

	/**
	 * Производит оптимизацию окон на указанную дату к указанному врачу.
	 * Объединяет идущие подряд окна в одно большое общее.
	 * @param int $doctor_id идентификатор доктора
	 * @param Datetime $date дата, на какую производится оптимизация
	 * @todo пока что реализация простейшая, вероятно с развитием функционала нужно будет усложнять
	 */
	protected function optimizeDashboardDoctorDay($doctor_id, Datetime $date) {
	}

	/**
	 * Проверяет, есть ли записи за указанный день ко врачу.
	 * @param $doctor_id идентификатор доктора
	 * @param $date дата
	 * @return bool 1 - день полностью свободен, 0 - есть записи на этот день
	 */
	protected function checkDayIsOpen($doctor_id,$date){}

	/**
	 * Формирует список времени, на которое можно записаться по свободному дню.
	 * @param $doctor_id
	 * @param $date
	 * @return array список доступного времени в формате 'H:i'
	 */
	protected function makeListForEmptyDay($doctor_id,$date){}

	/**
	 * Формирует список времени, на которое можно записаться по указанному окну. Т.е. разбивает его
	 * @param $doctor_id идентификатор врача
	 * @param $window_start начало окна
	 * @param $window_end конец окна
	 * @return array список доступного времени в формате 'H:i'
	 */
	protected function makeListForWindow($doctor_id, $window_start, $window_end){}

	/**
	 * Пересчитывает окна по указанному врачу
	 * @param $doctor_id
	 * @return void
	 */
	protected function flushDashboardByDoctor($doctor_id)){}
}
