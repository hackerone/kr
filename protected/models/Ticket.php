<?php

/**
 * This is the model class for table "ticket".
 *
 * The followings are the available columns in table 'ticket':
 * @property string $id
 * @property string $external_id
 * @property integer $status
 * @property string $assigned_date
 *
 * The followings are the available model relations:
 * @property TicketCheck[] $ticketChecks
 */
class Ticket extends CActiveRecord
{

	private $_tc;



	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ticket';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('external_id, status, checklist_id', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('external_id', 'length', 'max'=>16),
			array('ticketChecks', 'safe'),

			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, external_id, status, assigned_date', 'safe', 'on'=>'search'),
		);
	}

	public function afterFind()
	{
		$this->_tc = $this->ticketChecks;
		$tmp = [];

		foreach($this->ticketChecks as $item){
			$tmp[] = $item->checkitem_id;
		}
		$this->ticketChecks = $tmp;
	}


	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'ticketChecks' => array(self::HAS_MANY, 'TicketCheck', 'ticket_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'external_id' => 'External',
			'status' => 'Status',
			'assigned_date' => 'Assigned Date',
			'checklist_id' => 'Checklist',
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
		$criteria->compare('external_id',$this->external_id,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('assigned_date',$this->assigned_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Ticket the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getStates()
	{
		return [0 => 'Open', 1 => 'Closed', 2 => 'Exceptional Close'];
	}

	public function beforeSave()
	{
		if($this->isNewRecord){
			$this->assigned_date = new CDbExpression('NOW()');
		}

		$this->setStatus();

		return parent::beforeSave();
	}

	public function setStatus()
	{
		if($this->status == 2)
			return;

		$c = new CDbCriteria;
		$c->compare('list_id', $this->checklist_id);
		$c->compare('status', '>0');

		if(count($this->ticketChecks) < Checkitem::model()->count($c)){
			$this->status = 0;
		}else{
			$this->status = 1;
		}
	}

	public function afterSave()
	{
		TicketCheck::model()->updateChecks($this->id, $this->ticketChecks, Yii::app()->user->modelId);
		return parent::afterSave();
	}

	public function listChecks()
	{
		if(!$this->checklist_id)
			return [];
		$c = new CDbCriteria;
		$c->compare('list_id', $this->checklist_id);
		$c->compare('status', '>0');
		return CHtml::listData(Checkitem::model()->findAll($c), 'id', 'content');
	}
}
